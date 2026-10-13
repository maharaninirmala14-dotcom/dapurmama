<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * BudgetRecommendationService — Sistem Rekomendasi Menu DapurMama
 * Menggunakan Algoritma Data Mining Apriori (Market Basket Analysis).
 *
 * Logika Rekomendasi Dinamis & Algoritma Apriori:
 * 1. Mengambil data transaksi aktual dari database MySQL (orders & order_details).
 * 2. Membentuk keranjang belanja (market basket) per transaksi.
 * 3. Menghitung Frequent Itemsets (L1 s.d. L4) berdasarkan Minimum Support.
 * 4. Membentuk Aturan Asosiasi (Association Rules: X -> Y) berdasarkan Minimum Confidence.
 * 5. Menghitung metrik asosiasi:
 *    - Support(X) = Count(X) / N * 100%
 *    - Confidence(X -> Y) = Count(X U Y) / Count(X) * 100%
 *    - Lift(X -> Y) = Confidence(X -> Y) / Support(Y)
 * 6. Mengintegrasikan Rata-rata Budget per Porsi (Total Budget / Jumlah Porsi):
 *    - Strict Budget Constraint: Total harga paket <= Total budget dan Harga per porsi <= Rata-rata budget.
 *    - Skoring Gabungan: 50% Bobot Apriori (Confidence, Lift, Support, Direct Rule) + 50% Kesesuaian Anggaran.
 *    - Ketika budget meningkat: kandidat paket menu dengan tier harga lebih tinggi dipertimbangkan dan diprioritaskan.
 *    - Ketika porsi meningkat atau budget menurun: sistem otomatis beralih ke kombinasi menu yang lebih ekonomis.
 * 7. Diversifikasi paket: Paket 1, Paket 2, Paket 3, ... memiliki kombinasi makanan, minuman, dan snack
 *    yang bervariasi sesuai database dan harga dihitung riil dari database MySQL.
 */
class BudgetRecommendationService
{
    /**
     * Metadata kelompok menu.
     */
    public const GROUPS = [
        'makanan' => [
            'label'       => 'Makanan',
            'emoji'       => '🍚',
            'icon'        => 'fa-bowl-food',
            'description' => 'Nasi Box, Tumpeng, & Olahan Lauk Utama',
        ],
        'minuman' => [
            'label'       => 'Minuman',
            'emoji'       => '🥤',
            'icon'        => 'fa-wine-glass',
            'description' => 'Aneka Minuman Segar & Hangat',
        ],
        'snack'   => [
            'label'       => 'Snack',
            'emoji'       => '🍪',
            'icon'        => 'fa-cookie-bite',
            'description' => 'Snack Basah Tradisional & Snack Kering Gurih',
        ],
    ];

    /**
     * Kata kunci pemetaan kategori ke kelompok sistem.
     */
    protected const KEYWORDS = [
        'makanan' => ['nasi', 'box', 'tumpeng', 'prasmanan', 'makanan', 'lauk', 'ayam', 'lele', 'nila', 'mie', 'pecel', 'bandeng', 'telur'],
        'minuman' => ['minum', 'drink', 'beverage', 'jus', 'teh', 'kopi', 'es', 'vit', 'cokelat', 'susu'],
        'snack'   => ['snack', 'kering', 'basah', 'dessert', 'jajanan', 'kue', 'camilan', 'keripik', 'roti', 'pastel'],
    ];

    /**
     * Petakan kategori menu ke salah satu kelompok (makanan, minuman, snack).
     */
    public function groupOfCategory(?string $name, ?string $slug = null): ?string
    {
        $haystack = strtolower(trim(($slug ?? '') . ' ' . ($name ?? '')));

        if ($haystack === '') {
            return null;
        }

        foreach (self::KEYWORDS as $group => $keywords) {
            foreach ($keywords as $keyword) {
                if ($keyword !== '' && str_contains($haystack, $keyword)) {
                    return $group;
                }
            }
        }

        return null;
    }

    /**
     * Ambil seluruh menu dari database yang dikelompokkan ke Makanan, Minuman, dan Snack.
     *
     * @return array<string, Collection>
     */
    public function getMenusByGroup(): array
    {
        $menus = Menu::with('category')->whereNull('deleted_at')->orderBy('price', 'desc')->get();

        $buckets = [
            'makanan' => collect(),
            'minuman' => collect(),
            'snack'   => collect(),
        ];

        foreach ($menus as $menu) {
            $group = $this->groupOfCategory($menu->category?->name, $menu->category?->slug);
            if ($group !== null && isset($buckets[$group])) {
                $buckets[$group]->push($menu);
            }
        }

        return $buckets;
    }

    /**
     * Ambil daftar seluruh menu untuk 3 tabel daftar menu (Makanan, Minuman, Snack).
     *
     * @return array<string, Collection>
     */
    public function getAllMenusTable(): array
    {
        return $this->getMenusByGroup();
    }

    /**
     * Rekomendasi Menu per Kategori yang sesuai budget per porsi.
     */
    public function getCategoryRecommendations(float $budget): array
    {
        $budget = max(0.0, $budget);
        $buckets = $this->getMenusByGroup();
        $results = [];

        foreach (self::GROUPS as $key => $meta) {
            $allInGroup = $buckets[$key];

            $validMenus = $allInGroup
                ->filter(fn (Menu $m) => (float) $m->price <= ($budget + 0.001))
                ->sortByDesc(fn (Menu $m) => (float) $m->price)
                ->values()
                ->map(function (Menu $m) use ($budget) {
                    $price = (float) $m->price;
                    $remaining = max(0.0, $budget - $price);

                    return [
                        'id'               => $m->id,
                        'name'             => $m->name,
                        'price'            => $price,
                        'description'      => $m->description,
                        'image'            => $m->image,
                        'category_name'    => $m->category?->name ?? 'Umum',
                        'remaining_budget' => $remaining,
                        'budget_usage'     => $budget > 0 ? round(($price / $budget) * 100, 1) : 0,
                        'model'            => $m,
                    ];
                });

            $results[$key] = [
                'key'         => $key,
                'label'       => $meta['label'],
                'emoji'       => $meta['emoji'],
                'icon'        => $meta['icon'],
                'description' => $meta['description'],
                'items'       => $validMenus,
                'count'       => $validMenus->count(),
                'total_menus' => $allInGroup->count(),
                'min_price'   => $allInGroup->isNotEmpty() ? (float) $allInGroup->min('price') : 0.0,
                'max_price'   => $validMenus->isNotEmpty() ? (float) $validMenus->max('price') : 0.0,
            ];
        }

        return $results;
    }

    /**
     * Rekomendasi Paket Menu Dinamis Menggunakan Algoritma Apriori Berdasarkan Alokasi Budget & Porsi.
     *
     * @param  float       $budget          Alokasi rata-rata budget per porsi (atau budget per paket)
     * @param  int|null    $limit           Maksimal jumlah paket rekomendasi yang dikembalikan
     * @param  int|null    $selectedMenuId  ID menu makanan utama yang dipilih pengguna (opsional)
     * @param  bool        $includeSnack    Sertakan snack (Paket Lengkap) atau hanya Makanan + Minuman
     * @param  int|null    $userId          ID pelanggan jika ingin rekomendasi personal Apriori
     * @param  int         $pax             Jumlah porsi / paket yang dipesan (default 1)
     * @return array
     */
    public function getAprioriPackageRecommendations(
        float $budget,
        ?int $limit = 20,
        ?int $selectedMenuId = null,
        bool $includeSnack = true,
        ?int $userId = null,
        int $pax = 1
    ): array {
        $budget = max(0.0, $budget);
        $pax    = max(1, $pax);
        $limit  = ($limit !== null && $limit > 0) ? $limit : 20;
        $totalBudget = $budget * $pax;

        $buckets = $this->getMenusByGroup();
        $foods   = $buckets['makanan']->keyBy('id');
        $drinks  = $buckets['minuman']->keyBy('id');
        $snacks  = $buckets['snack']->keyBy('id');

        if ($foods->isEmpty() || $drinks->isEmpty()) {
            return [];
        }

        // 1. Eksekusi Algoritma Apriori pada Transaksi Historis Database MySQL
        $apriori = new AprioriService();
        $aprioriResult = $apriori->run($userId);
        $rules = $aprioriResult['rules'] ?? [];

        // 2. Kumpulkan Seluruh Kandidat Kombinasi Menu dari Aturan Asosiasi Apriori
        $candidates = [];
        $seenCombos = [];

        foreach ($rules as $rule) {
            $allIds = array_values(array_unique(array_merge($rule['antecedent'], $rule['consequent'])));

            // Jika pengguna memilih makanan tertentu, aturan harus relevan dengan menu tersebut
            if ($selectedMenuId !== null && !in_array($selectedMenuId, $allIds)) {
                continue;
            }

            // Makanan yang ada dalam rule
            $rFoods = [];
            foreach ($allIds as $id) {
                if ($foods->has($id)) {
                    if ($selectedMenuId !== null && $id !== $selectedMenuId) {
                        continue;
                    }
                    $rFoods[] = $foods->get($id);
                }
            }

            // Minuman yang ada dalam rule
            $rDrinks = [];
            foreach ($allIds as $id) {
                if ($drinks->has($id)) {
                    $rDrinks[] = $drinks->get($id);
                }
            }

            // Snack yang ada dalam rule
            $rSnacks = [];
            if ($includeSnack) {
                foreach ($allIds as $id) {
                    if ($snacks->has($id)) {
                        $rSnacks[] = $snacks->get($id);
                    }
                }
            }

            $supportVal    = round(($rule['support'] ?? 0) * 100, 2);
            $confidenceVal = round(($rule['confidence'] ?? 0) * 100, 2);
            $liftVal       = round((float) ($rule['lift'] ?? 0), 2);
            $antNames      = Menu::whereIn('id', $rule['antecedent'])->pluck('name')->all();
            $conNames      = Menu::whereIn('id', $rule['consequent'])->pluck('name')->all();

            // KASUS 1: Rule mengandung Makanan dan Minuman secara langsung (Asosiasi Utama Apriori)
            if (!empty($rFoods) && !empty($rDrinks)) {
                foreach ($rFoods as $f) {
                    foreach ($rDrinks as $d) {
                        if (!empty($rSnacks)) {
                            // Rule juga mengandung snack
                            foreach ($rSnacks as $s) {
                                $this->collectCandidate(
                                    $candidates, $seenCombos, $f, $d, $s,
                                    true, true, true,
                                    $confidenceVal, $liftVal, $supportVal,
                                    $antNames, $conNames, $budget, $totalBudget, $pax
                                );
                            }
                        } else {
                            // Rule belum mengandung snack: lengkapi dengan variasi snack database yang muat anggaran
                            if ($includeSnack && $snacks->isNotEmpty()) {
                                $curPrice = (float) $f->price + (float) $d->price;
                                $maxSnack = $budget > 0 ? max(0.0, $budget - $curPrice) : PHP_FLOAT_MAX;
                                $eligibleSnacks = $snacks->filter(fn (Menu $sn) => (float) $sn->price <= ($maxSnack + 0.001));

                                foreach ($eligibleSnacks as $s) {
                                    $this->collectCandidate(
                                        $candidates, $seenCombos, $f, $d, $s,
                                        true, true, false,
                                        $confidenceVal, $liftVal, $supportVal,
                                        $antNames, $conNames, $budget, $totalBudget, $pax
                                    );
                                }
                            } else {
                                $this->collectCandidate(
                                    $candidates, $seenCombos, $f, $d, null,
                                    true, true, false,
                                    $confidenceVal, $liftVal, $supportVal,
                                    $antNames, $conNames, $budget, $totalBudget, $pax
                                );
                            }
                        }
                    }
                }
            }

            // KASUS 2: Rule mengandung Makanan dan Snack secara langsung (tanpa minuman di rule)
            if (!empty($rFoods) && empty($rDrinks) && !empty($rSnacks) && $includeSnack) {
                foreach ($rFoods as $f) {
                    foreach ($rSnacks as $s) {
                        $curPrice = (float) $f->price + (float) $s->price;
                        $maxDrink = $budget > 0 ? max(0.0, $budget - $curPrice) : PHP_FLOAT_MAX;
                        $eligibleDrinks = $drinks->filter(fn (Menu $dr) => (float) $dr->price <= ($maxDrink + 0.001));

                        foreach ($eligibleDrinks as $d) {
                            $this->collectCandidate(
                                $candidates, $seenCombos, $f, $d, $s,
                                true, false, true,
                                $confidenceVal, $liftVal, $supportVal,
                                $antNames, $conNames, $budget, $totalBudget, $pax
                            );
                        }
                    }
                }
            }

            // KASUS 3: Rule mengandung Minuman dan Snack (tanpa makanan di rule)
            // Relevan jika pengguna belum mengunci menu makanan tertentu
            if (empty($rFoods) && !empty($rDrinks) && !empty($rSnacks) && $selectedMenuId === null && $includeSnack) {
                foreach ($rDrinks as $d) {
                    foreach ($rSnacks as $s) {
                        $curPrice = (float) $d->price + (float) $s->price;
                        $maxFood  = $budget > 0 ? max(0.0, $budget - $curPrice) : PHP_FLOAT_MAX;
                        $eligibleFoods = $foods->filter(fn (Menu $fd) => (float) $fd->price <= ($maxFood + 0.001));

                        foreach ($eligibleFoods as $f) {
                            $this->collectCandidate(
                                $candidates, $seenCombos, $f, $d, $s,
                                true, false, true,
                                $confidenceVal, $liftVal, $supportVal,
                                $antNames, $conNames, $budget, $totalBudget, $pax
                            );
                        }
                    }
                }
            }
        }

        // 3. Fallback Dinamis Apabila Belum Ada Pola Asosiasi Apriori yang Cocok
        // Sesuai Instruksi: "Jika tidak ditemukan aturan asosiasi yang cocok,
        // tampilkan informasi yang sesuai, bukan membuat rekomendasi palsu."
        if (empty($candidates)) {
            $targetFood = ($selectedMenuId !== null) ? $foods->get($selectedMenuId) : null;
            $candidateFoods = $targetFood ? collect([$targetFood]) : $foods->sortBy('price');

            foreach ($candidateFoods as $f) {
                if (!$f) continue;
                foreach ($drinks->sortBy('price') as $d) {
                    if ($includeSnack && $snacks->isNotEmpty()) {
                        foreach ($snacks->sortBy('price') as $s) {
                            $this->collectCandidate(
                                $candidates, $seenCombos, $f, $d, $s,
                                false, false, false,
                                15.0, 1.0, 1.0,
                                [$f->name], [$d->name], $budget, $totalBudget, $pax
                            );
                        }
                    } else {
                        $this->collectCandidate(
                            $candidates, $seenCombos, $f, $d, null,
                            false, false, false,
                            15.0, 1.0, 1.0,
                            [$f->name], [$d->name], $budget, $totalBudget, $pax
                        );
                    }
                }
            }
        }

        // 4. Hitung Skor Gabungan (Relevansi Apriori 50% + Kesesuaian Anggaran 50%)
        // Memastikan budget dan jumlah porsi secara dinamis mempengaruhi urutan rekomendasi!
        foreach ($candidates as &$cand) {
            $pricePerPax = $cand['price_per_pax'];
            // Rasio utilitas anggaran per porsi (0.0 s.d 1.0)
            $budgetUtilization = $budget > 0 ? min(1.0, $pricePerPax / $budget) : 0.5;

            $confNorm = min(1.0, ($cand['confidence'] ?? 0) / 100.0);
            $liftNorm = min(1.0, max(0.0, (($cand['lift'] ?? 1.0) - 1.0) / 15.0));
            $supNorm  = min(1.0, ($cand['support'] ?? 0) / 10.0);

            $aprioriScore = (0.50 * $confNorm) + (0.35 * $liftNorm) + (0.15 * $supNorm);
            if ($cand['is_direct_drink']) {
                $aprioriScore += 0.15;
            }
            if ($cand['is_direct_snack']) {
                $aprioriScore += 0.10;
            }

            // Komposisi: 50% Kekuatan Asosiasi Apriori + 50% Kesesuaian Anggaran Pengguna
            // Apabila rata-rata budget per porsi meningkat, paket dengan harga per porsi yang lebih tinggi
            // memiliki budgetUtilization lebih tinggi sehingga dapat diprioritaskan.
            // Apabila rata-rata budget per porsi menurun, kandidat yang lebih mahal tereliminasi/tersaring.
            $compositeScore = (0.50 * $aprioriScore) + (0.50 * $budgetUtilization);
            $cand['score'] = round($compositeScore, 4);
            $cand['budget_utilization'] = round($budgetUtilization * 100, 1);
        }
        unset($cand);

        // 5. Urutkan Kandidat:
        //    (1) Skor gabungan tertinggi (Apriori + Kesesuaian Anggaran)
        //    (2) Aturan asosiasi langsung Food <-> Drink diutamakan
        //    (3) Nilai Confidence tertinggi
        //    (4) Nilai Lift tertinggi
        //    (5) Nilai Support tertinggi
        usort($candidates, function ($a, $b) {
            if (abs($b['score'] - $a['score']) > 0.0001) {
                return $b['score'] <=> $a['score'];
            }
            if ($a['is_direct_drink'] !== $b['is_direct_drink']) {
                return $b['is_direct_drink'] <=> $a['is_direct_drink'];
            }
            if (abs($b['confidence'] - $a['confidence']) > 0.0001) {
                return $b['confidence'] <=> $a['confidence'];
            }
            if (abs($b['lift'] - $a['lift']) > 0.0001) {
                return $b['lift'] <=> $a['lift'];
            }
            return $b['support'] <=> $a['support'];
        });

        // 6. Diversifikasi Paket Rekomendasi (Variasi Makanan, Minuman, dan Snack antarpaket)
        $packages = [];
        $usedDrinkIds = [];
        $usedFoodIds  = [];
        $usedSnackIds = [];
        $usedCombos   = [];

        // Putaran 1: Prioritaskan paket yang memiliki Makanan, Minuman, DAN Snack yang berbeda
        foreach ($candidates as $cand) {
            $fId = $cand['food']->id;
            $dId = $cand['drink']->id;
            $sId = $cand['snack'] ? $cand['snack']->id : 0;
            $comboKey = $fId . '_' . $dId . '_' . $sId;

            $drinkUsed = in_array($dId, $usedDrinkIds, true);
            $foodUsed  = in_array($fId, $usedFoodIds, true);
            $snackUsed = $sId ? in_array($sId, $usedSnackIds, true) : false;

            if (!$drinkUsed && !$foodUsed && (!$snackUsed || count($usedSnackIds) >= $snacks->count())) {
                $rank = count($packages) + 1;
                $packages[] = $this->formatPackage($cand, $rank, $totalBudget, $budget, $pax);
                $usedDrinkIds[] = $dId;
                $usedFoodIds[]  = $fId;
                if ($sId) $usedSnackIds[] = $sId;
                $usedCombos[$comboKey] = true;
                if (count($packages) >= $limit) break;
            }
        }

        // Putaran 2: Tambahkan variasi pasangan unik makanan & minuman jika kuota limit belum terpenuhi
        if (count($packages) < $limit) {
            foreach ($candidates as $cand) {
                $fId = $cand['food']->id;
                $dId = $cand['drink']->id;
                $sId = $cand['snack'] ? $cand['snack']->id : 0;
                $comboKey = $fId . '_' . $dId . '_' . $sId;

                if (!isset($usedCombos[$comboKey])) {
                    if (!in_array($dId, $usedDrinkIds, true) || !in_array($fId, $usedFoodIds, true)) {
                        $usedCombos[$comboKey] = true;
                        $rank = count($packages) + 1;
                        $packages[] = $this->formatPackage($cand, $rank, $totalBudget, $budget, $pax);
                        $usedDrinkIds[] = $dId;
                        $usedFoodIds[]  = $fId;
                        if ($sId) $usedSnackIds[] = $sId;
                        if (count($packages) >= $limit) break;
                    }
                }
            }
        }

        // 7. Urutkan seluruh paket yang terpilih berdasarkan skor terbaik & tetapkan label
        usort($packages, function ($a, $b) {
            if (abs($b['score'] - $a['score']) > 0.0001) {
                return $b['score'] <=> $a['score'];
            }
            return $b['confidence'] <=> $a['confidence'];
        });

        foreach ($packages as $i => &$pkg) {
            $rank = $i + 1;
            $pkg['id'] = $rank;
            $pkg['package_number'] = $rank;
            $pkg['rank'] = $rank;
            $pkg['is_best'] = ($rank === 1);
            $pkg['label'] = ($rank === 1) ? 'Rekomendasi Terbaik' : ('Paket ' . $rank);
            $pkg['saw_score'] = max(0.01, round($pkg['score'] - ($i * 0.001), 4));
        }
        unset($pkg);

        return $packages;
    }

    /**
     * Kumpulkan dan saring kandidat kombinasi menu sesuai aturan asosiasi & batas anggaran.
     */
    private function collectCandidate(
        array &$candidates,
        array &$seenCombos,
        Menu $food,
        Menu $drink,
        ?Menu $snack,
        bool $isAprioriMatch,
        bool $isDirectDrink,
        bool $isDirectSnack,
        float $conf,
        float $lift,
        float $sup,
        array $ant,
        array $con,
        float $avgBudget,
        float $totalBudget,
        int $pax
    ): void {
        $pricePerPax = (float) $food->price + (float) $drink->price + ($snack ? (float) $snack->price : 0.0);
        $totalPkgPrice = $pricePerPax * $pax;

        // Constraint Ketat Anggaran (Budget sebagai batas maksimal, bukan informasi):
        // Total harga paket <= total budget pengguna DAN harga per porsi <= rata-rata budget per porsi
        if ($avgBudget > 0 && $pricePerPax > ($avgBudget + 0.001)) {
            return;
        }
        if ($totalBudget > 0 && $totalPkgPrice > ($totalBudget + 0.001)) {
            return;
        }

        $snackId = $snack ? $snack->id : 0;
        $comboKey = $food->id . '_' . $drink->id . '_' . $snackId;

        if (isset($seenCombos[$comboKey])) {
            if ($conf > $seenCombos[$comboKey]['confidence']) {
                $seenCombos[$comboKey]['confidence'] = $conf;
                $seenCombos[$comboKey]['lift'] = $lift;
                $seenCombos[$comboKey]['support'] = $sup;
            }
            return;
        }

        $ruleDescription = $isAprioriMatch
            ? sprintf(
                "Pola Asosiasi Apriori: Konsumen yang memesan %s cenderung memesan %s (Confidence: %.1f%%, Lift: %.2f, Support: %.1f%%)",
                !empty($ant) ? implode(', ', $ant) : $food->name,
                !empty($con) ? implode(', ', $con) : $drink->name,
                $conf,
                $lift,
                $sup
            )
            : sprintf(
                "Pola asosiasi Apriori belum ditemukan pada data transaksi historis untuk menu '%s'. Menampilkan kombinasi menu alternatif yang tersedia sesuai anggaran.",
                $food->name
            );

        $candData = [
            'food'                 => $food,
            'drink'                => $drink,
            'snack'                => $snack,
            'is_apriori_match'     => $isAprioriMatch,
            'is_direct_drink'      => $isDirectDrink,
            'is_direct_snack'      => $isDirectSnack,
            'confidence'           => $isAprioriMatch ? $conf : null,
            'lift'                 => $isAprioriMatch ? $lift : null,
            'support'              => $isAprioriMatch ? $sup : null,
            'price_per_pax'        => $pricePerPax,
            'total_pkg_price'      => $totalPkgPrice,
            'antecedent_names'     => $ant,
            'consequent_names'     => $con,
            'rule_description'     => $ruleDescription,
        ];

        $seenCombos[$comboKey] = &$candData;
        $candidates[] = &$candData;
    }

    /**
     * Bentuk struktur data paket rekomendasi final yang lengkap.
     */
    private function formatPackage(
        array $cand,
        int $rank,
        float $totalBudget,
        float $avgBudget,
        int $pax
    ): array {
        $price = $cand['price_per_pax'];
        $totalPrice = $price * $pax;
        $f = $cand['food'];
        $d = $cand['drink'];
        $s = $cand['snack'];
        $score = $cand['score'] ?? max(0.01, 1.0 - ($rank * 0.05));

        return [
            'id'                   => $rank,
            'package_number'       => $rank,
            'label'                => ($rank === 1) ? 'Rekomendasi Terbaik' : ('Paket ' . $rank),
            'rank'                 => $rank,
            'is_best'              => ($rank === 1),
            'is_apriori_match'     => (bool) ($cand['is_apriori_match'] ?? false),
            'makanan'              => $f,
            'minuman'              => $d,
            'snack'                => $s,
            'price_per_pax'        => $price,
            'total_price'          => $price, // satuan per porsi (kompatibilitas)
            'total_package_price'  => $totalPrice, // total untuk seluruh N porsi
            'budget_entered'       => $totalBudget,
            'avg_budget_per_pax'   => $avgBudget,
            'remaining_budget'     => max(0.0, $totalBudget - $totalPrice),
            'remaining_per_pax'    => max(0.0, $avgBudget - $price),
            'budget_usage_percent' => $totalBudget > 0 ? round(($totalPrice / $totalBudget) * 100, 1) : 0,
            'support'              => $cand['support'],
            'confidence'           => $cand['confidence'],
            'lift'                 => $cand['lift'],
            'score'                => $score,
            'saw_score'            => $score, // kompatibilitas atribut scoring
            'antecedent_names'     => $cand['antecedent_names'] ?? [],
            'consequent_names'     => $cand['consequent_names'] ?? [],
            'rule_description'     => $cand['rule_description'],
        ];
    }

    /**
     * Alias kompatibilitas untuk getAprioriPackageRecommendations.
     * Menerima $selectedMenuOrPreference baik berupa integer menu_id, string preference, atau null.
     */
    public function getPackageRecommendations(
        float $budget,
        ?int $limit = 20,
        $selectedMenuOrPreference = null,
        bool $includeSnack = true,
        ?int $userId = null,
        int $pax = 1
    ): array {
        $selectedMenuId = null;

        if (is_numeric($selectedMenuOrPreference)) {
            $selectedMenuId = (int) $selectedMenuOrPreference;
        } elseif (is_string($selectedMenuOrPreference) && $selectedMenuOrPreference !== '' && $selectedMenuOrPreference !== 'semua') {
            $buckets = $this->getMenusByGroup();
            if ($selectedMenuOrPreference === 'hemat') {
                $match = $buckets['makanan']->sortBy('price')->first();
            } else {
                $match = $buckets['makanan']->filter(function (Menu $m) use ($selectedMenuOrPreference) {
                    return str_contains(strtolower($m->name), strtolower($selectedMenuOrPreference));
                })->sortBy('price')->first();
            }
            if ($match) {
                $selectedMenuId = $match->id;
            }
        }

        return $this->getAprioriPackageRecommendations($budget, $limit, $selectedMenuId, $includeSnack, $userId, $pax);
    }

    /**
     * Hitung rekomendasi lengkap berdasarkan alokasi budget, porsi, dan pilihan menu.
     *
     * @param  float          $budget                   Total budget pemesan
     * @param  int|null       $pax                      Jumlah porsi / orang
     * @param  int|null       $limit                    Maksimal jumlah paket
     * @param  int|string|null $selectedMenuOrPreference Pilihan menu ID atau preferensi
     * @param  bool           $includeSnack             Sertakan snack atau tidak
     * @param  int|null       $userId                   ID user login (opsional)
     * @return array
     */
    public function recommend(
        float $budget,
        ?int $pax = null,
        ?int $limit = 20,
        $selectedMenuOrPreference = null,
        bool $includeSnack = true,
        ?int $userId = null
    ): array {
        $totalBudget = max(0.0, $budget);
        $pax = ($pax !== null && $pax > 0) ? (int) $pax : 1;

        // Rata-rata budget per porsi = Total Budget / Jumlah Porsi
        $effectiveBudget = $pax > 0 ? ($totalBudget / $pax) : $totalBudget;

        $buckets    = $this->getMenusByGroup();
        $categories = $this->getCategoryRecommendations($effectiveBudget);
        $packages   = $this->getPackageRecommendations(
            $effectiveBudget,
            $limit,
            $selectedMenuOrPreference,
            $includeSnack,
            $userId,
            $pax
        );

        $selectedMenuId = is_numeric($selectedMenuOrPreference) ? (int) $selectedMenuOrPreference : null;
        $selectedMenu = $selectedMenuId ? Menu::find($selectedMenuId) : null;

        $minFood  = $buckets['makanan']->isNotEmpty() ? (float) $buckets['makanan']->min('price') : 0.0;
        $minDrink = $buckets['minuman']->isNotEmpty() ? (float) $buckets['minuman']->min('price') : 0.0;
        $minSnack = $includeSnack && $buckets['snack']->isNotEmpty() ? (float) $buckets['snack']->min('price') : 0.0;
        $minPackagePrice = $minFood + $minDrink + $minSnack;

        $notes = [];
        if ($totalBudget > 0 && empty($packages) && $effectiveBudget < $minPackagePrice) {
            $notes[] = sprintf(
                'Alokasi budget Rp %s per porsi belum mencukupi untuk paket minimal (Rp %s/porsi). Silakan naikkan Total Budget atau sesuaikan Jumlah Porsi.',
                number_format($effectiveBudget, 0, ',', '.'),
                number_format($minPackagePrice, 0, ',', '.')
            );
        }

        $minSupport = (float) Setting::getValue('min_support', 0.01);
        $minConfidence = (float) Setting::getValue('min_confidence', 0.05);

        return [
            'budget'               => $totalBudget,
            'total_budget'         => $totalBudget,
            'pax'                  => $pax,
            'effective_budget'     => $effectiveBudget,
            'average_budget'       => $effectiveBudget,
            'selected_menu_id'     => $selectedMenuId,
            'selected_menu'        => $selectedMenu,
            'algorithm'            => 'Apriori',
            'min_support'          => $minSupport,
            'min_confidence'       => $minConfidence,
            'include_snack'        => $includeSnack,
            'all_menus'            => $buckets,
            'categories'           => $categories,
            'packages'             => $packages,
            'best_package'         => $packages[0] ?? null,
            'has_packages'         => !empty($packages),
            'has_result'           => (!empty($packages) || !empty($categories['makanan']['items'])),
            'min_package_price'    => $minPackagePrice,
            'notes'                => $notes,
            'weights'              => [
                'support'    => $minSupport,
                'confidence' => $minConfidence,
                'lift'       => 1.0,
            ],
        ];
    }
}
