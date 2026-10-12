<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\User;

class AprioriService
{
    /**
     * Status order yang dianggap transaksi valid untuk analisis Apriori.
     */
    public const INCLUDED_STATUSES = ['Selesai', 'Diproses', 'Menunggu', 'completed', 'processing'];

    protected float $minSupport;
    protected float $minConfidence;

    public function __construct()
    {
        $settingSupport = Setting::getValue('min_support', 0.2);
        $this->minSupport = (float) $settingSupport;
        
        $this->minConfidence = (float) Setting::getValue('min_confidence', 0.5);
    }

    public function setParameters(float $minSupport, float $minConfidence): void
    {
        $this->minSupport = $minSupport;
        $this->minConfidence = $minConfidence;
    }

    /**
     * Mengambil menu rekomendasi berdasarkan hasil aturan asosiasi Apriori.
     *
     * @param  int        $limit   Banyaknya menu rekomendasi yang dikembalikan.
     * @param  int|null   $userId  Jika diisi, analisis hanya memakai transaksi milik
     *                             pelanggan tersebut (rekomendasi personal).
     * @return \Illuminate\Support\Collection<int, \App\Models\Menu>
     */
    public function getRecommendations($limit = 3, ?int $userId = null)
    {
        $limit = max(1, (int) $limit);
        $result = $this->run($userId);
        $rules = $result['rules'] ?? [];

        // Urutkan rules berdasarkan lift tertinggi, lalu confidence tertinggi, lalu support tertinggi
        usort($rules, function($a, $b) {
            $liftA = $a['lift'] ?? 0;
            $liftB = $b['lift'] ?? 0;

            if ($liftB != $liftA) {
                return $liftB <=> $liftA; // Lift tertinggi di atas
            }
            if ($b['confidence'] == $a['confidence']) {
                return $b['support'] <=> $a['support'];
            }
            return $b['confidence'] <=> $a['confidence'];
        });

        $recommendedMenuIds = [];
        foreach ($rules as $rule) {
            foreach ($rule['consequent'] as $menuId) {
                if (!in_array($menuId, $recommendedMenuIds)) {
                    $recommendedMenuIds[] = $menuId;
                }
            }
        }

        if (empty($recommendedMenuIds)) {
            // Hasil Apriori kosong -> kembalikan koleksi kosong.
            // Pemanggil (controller) yang memutuskan fallback-nya, agar tidak
            // menyamar sebagai rekomendasi hasil analisis Apriori.
            return Menu::with('category')->whereIn('id', [])->get();
        }

        // Batasi ID sesuai $limit, ambil datanya, lalu urutkan di PHP
        // (FIELD() khusus MySQL dan tidak portable ke SQLite saat testing)
        $ids = array_slice($recommendedMenuIds, 0, $limit);
        $menus = Menu::with('category')->whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $menus->get($id))
            ->filter()
            ->values();
    }

    /**
     * Rekomendasi berbasis isi keranjang pelanggan (market basket personal).
     *
     * Rules yang antecedent-nya cocok dengan menu di keranjang dipakai untuk
     * merekomendasikan consequent-nya. Jika keranjang kosong atau tidak ada
     * yang cocok, dipakai rules terbaik (lift tertinggi).
     *
     * @return array{
     *     recommended_menus: \Illuminate\Support\Collection,
     *     recommended_packages: array,
     *     explanation_rules: array,
     *     personalized: bool
     * }
     */
    public function getRecommendationsForCart(array $cartMenuIds, ?int $userId = null): array
    {
        $cartMenuIds = array_values(array_unique(array_map('intval', $cartMenuIds)));

        $result = $this->run($userId);
        $rules = collect($result['rules'] ?? []);

        // Prioritaskan rules yang antecedent-nya ada di keranjang
        $matchedRules = $cartMenuIds
            ? $rules->filter(fn ($r) => array_intersect($r['antecedent'], $cartMenuIds))->values()
            : $rules->values();

        // Fallback: kalau tidak ada yang cocok, pakai rules terbaik
        if ($matchedRules->isEmpty()) {
            $matchedRules = $rules->take(5)->values();
        }

        $recommendedMenuIds = [];
        foreach ($matchedRules as $rule) {
            foreach ($rule['consequent'] as $menuId) {
                if (!in_array($menuId, $cartMenuIds) && !in_array($menuId, $recommendedMenuIds)) {
                    $recommendedMenuIds[] = $menuId;
                }
            }
        }

        $menus = Menu::with('category')
            ->whereIn('id', $recommendedMenuIds)
            ->get()
            ->keyBy('id');

        $recommendedMenus = collect($recommendedMenuIds)
            ->map(fn ($id) => $menus->get($id))
            ->filter()
            ->values();

        // Paket = gabungan antecedent + consequent dari rules yang cocok
        $packages = [];
        $seenPackageKeys = [];
        foreach ($matchedRules as $rule) {
            $menuIds = array_values(array_unique(array_merge($rule['antecedent'], $rule['consequent'])));
            sort($menuIds);
            $key = implode(',', $menuIds);
            if (isset($seenPackageKeys[$key]) || count($menuIds) < 2) {
                continue;
            }
            $seenPackageKeys[$key] = true;

            $packageMenus = Menu::with('category')->whereIn('id', $menuIds)->get();
            if ($packageMenus->count() !== count($menuIds)) {
                continue;
            }

            $packages[] = [
                'menu_ids' => $menuIds,
                'menus'    => $packageMenus,
                'name'     => 'Paket ' . $packageMenus->pluck('name')->implode(' + '),
                'price'    => (float) $packageMenus->sum('price'),
                'support'  => round($rule['support'] * 100, 1),
                'lift'     => $rule['lift'],
            ];
        }

        // Penjelasan aturan (support / confidence / lift) untuk ditampilkan
        $explanationRules = [];
        foreach ($matchedRules->slice(0, 10) as $rule) {
            $allIds = array_values(array_unique(array_merge($rule['antecedent'], $rule['consequent'])));
            $allMenus = Menu::whereIn('id', $allIds)->get();

            $explanationRules[] = [
                'antecedent'  => $allMenus->whereIn('id', $rule['antecedent'])->pluck('name')->values()->toArray(),
                'consequent'  => $allMenus->whereIn('id', $rule['consequent'])->pluck('name')->values()->toArray(),
                'confidence'  => round($rule['confidence'] * 100, 1),
                'support'     => round($rule['support'] * 100, 1),
                'lift'        => $rule['lift'],
                'total_price' => (float) $allMenus->sum('price'),
            ];
        }

        return [
            'recommended_menus'    => $recommendedMenus,
            'recommended_packages' => $packages,
            'explanation_rules'    => $explanationRules,
            'personalized'         => (bool) ($result['personalized'] ?? false),
        ];
    }

    /**
     * Menjalankan Apriori.
     *
     * @param  int|null  $userId  Jika diisi, analisis hanya memakai transaksi milik
     *                             pelanggan tersebut. Bila riwayat pelanggan belum
     *                             cukup untuk membentuk frequent itemset, otomatis
     *                             fallback ke seluruh transaksi (global).
     */
    public function run(?int $userId = null): array
    {
        if ($userId !== null) {
            $personal = $this->analyze($userId, true);

            // Fallback: riwayat pelanggan belum menghasilkan itemset apa pun
            if (!empty($personal['frequent_itemsets']) || !empty($personal['rules'])) {
                return $personal;
            }
        }

        return $this->analyze(null, false);
    }

    /**
     * Menyusun "bukti kerja Apriori" untuk satu pelanggan.
     *
     * Semua isi hasil dihitung ulang dari transaksi pelanggan tersebut
     * (tidak ada hardcode): riwayat transaksi -> frequent itemset ->
     * aturan asosiasi (support/confidence/lift) -> rekomendasi yang muncul.
     *
     * @return array{
     *     user: array|null,
     *     personalized: bool,
     *     transaction_count: int,
     *     unique_items: int,
     *     item_ids: array,
     *     analyzed_transactions: int,
     *     min_support: float,
     *     min_confidence: float,
     *     transactions: array,
     *     frequent_itemsets: array,
     *     rules: array,
     *     recommendations: array,
     *     recommended_menu_ids: array
     * }
     */
    public function getProofForUser(int $userId): array
    {
        $user = User::find($userId);
        $result = $this->run($userId);

        $personalized = (bool) ($result['personalized'] ?? false);
        $analyzed = (int) ($result['total_transactions'] ?? 0);

        // 1. Riwayat transaksi pelanggan = dataset yang dipelajari Apriori
        $orders = Order::where('user_id', $userId)
            ->whereIn('status', self::INCLUDED_STATUSES)
            ->with('orderDetails')
            ->latest()
            ->get();

        $uniqueItems = $orders
            ->flatMap(fn ($order) => $order->orderDetails->pluck('menu_id'))
            ->filter()
            ->unique()
            ->count();

        $itemIds = $orders
            ->flatMap(fn ($order) => $order->orderDetails->pluck('menu_id'))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        $transactions = $orders
            ->take(12)
            ->map(function ($order) {
                $itemIds = $order->orderDetails->pluck('menu_id')->filter()->unique()->sort()->values();

                return [
                    'order_code' => $order->order_code,
                    'order_date' => $order->order_date
                        ? \Illuminate\Support\Carbon::parse($order->order_date)->format('d M Y')
                        : null,
                    'menu_ids'   => $itemIds->all(),
                    'menu_names' => $this->menuNames($itemIds->all()),
                    'item_count' => $itemIds->count(),
                ];
            })
            ->values()
            ->all();

        // 2. Frequent itemsets (hasil langkah 1 & 2 Apriori)
        $frequentItemsets = [];
        foreach ($result['frequent_itemsets'] ?? [] as $level => $itemsets) {
            $frequentItemsets[$level] = collect($itemsets)
                ->take(10)
                ->map(fn ($itemset) => [
                    'menu_ids'   => $itemset['items'],
                    'menu_names' => $this->menuNames($itemset['items']),
                    'support'    => round($itemset['support'] * 100, 1),
                    'count'      => (int) ($itemset['count'] ?? 0),
                    'total'      => $analyzed,
                ])
                ->values()
                ->all();
        }

        // 3. Aturan asosiasi + metrik (support / confidence / lift) lengkap
        //    dengan angka pendukungnya supaya rumusnya bisa ditunjukkan
        $rules = collect($result['rules'] ?? [])
            ->take(10)
            ->map(fn ($rule) => [
                'antecedent_names' => $this->menuNames($rule['antecedent']),
                'consequent_names' => $this->menuNames($rule['consequent']),
                'support'          => round($rule['support'] * 100, 1),
                'confidence'       => round($rule['confidence'] * 100, 1),
                'lift'             => (float) $rule['lift'],
                'support_count'    => (int) ($rule['support_count'] ?? 0),
                'antecedent_count' => (int) ($rule['antecedent_count'] ?? 0),
                'consequent_count' => (int) ($rule['consequent_count'] ?? 0),
                'total'            => $analyzed,
            ])
            ->values()
            ->all();

        // 4. Rekomendasi yang muncul (logika yang sama dengan halaman customer)
        $recommendations = $this->buildRecommendationsFromResult($result, $analyzed);

        return [
            'user'                 => $user
                ? ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]
                : null,
            'personalized'         => $personalized,
            'transaction_count'    => $orders->count(),
            'unique_items'         => $uniqueItems,
            'item_ids'             => $itemIds,
            'analyzed_transactions'=> $analyzed,
            'min_support'          => (float) ($result['min_support'] ?? $this->minSupport),
            'min_confidence'       => (float) ($result['min_confidence'] ?? $this->minConfidence),
            'transactions'         => $transactions,
            'frequent_itemsets'    => $frequentItemsets,
            'rules'                => $rules,
            'recommendations'      => $recommendations,
            'recommended_menu_ids' => collect($recommendations)
                ->flatMap(fn ($rec) => $rec['menu_ids'])
                ->unique()
                ->values()
                ->all(),
        ];
    }

    /**
     * Menurunkan rekomendasi dari hasil Apriori (sama seperti halaman customer):
     * utamakan frequent itemset ukuran 4, bila tidak ada pakai rules terbaik.
     *
     * @return array<int, array{source: string, menu_ids: array, menu_names: array, support: float, count: int, total: int, description: string, confidence?: float, lift?: float}>
     */
    private function buildRecommendationsFromResult(array $result, int $total): array
    {
        $recommendations = [];
        $seenKeys = [];

        foreach ($result['frequent_itemsets'][4] ?? [] as $itemset) {
            $ids = array_values($itemset['items']);
            $map = $this->menuNameMap($ids);

            if (count($ids) < 4 || $map->count() !== count($ids)) {
                continue; // menu sudah tidak ada / bukan paket 4 menu
            }

            $key = implode(',', $ids);
            if (isset($seenKeys[$key])) {
                continue;
            }
            $seenKeys[$key] = true;

            $count = (int) ($itemset['count'] ?? 0);
            $recommendations[] = [
                'source'      => 'frequent_itemset',
                'menu_ids'    => $ids,
                'menu_names'  => $this->menuNames($ids),
                'support'     => round($itemset['support'] * 100, 1),
                'count'       => $count,
                'total'       => $total,
                'description' => "Pola langganan: ke-4 menu ini dibeli bersamaan dalam {$count} dari {$total} transaksi pelanggan.",
            ];
        }

        // Fallback: gabungan antecedent + consequent dari rules terbaik (lift tertinggi)
        if (empty($recommendations)) {
            foreach (array_slice($result['rules'] ?? [], 0, 5) as $rule) {
                $ids = array_values(array_unique(array_merge($rule['antecedent'], $rule['consequent'])));
                sort($ids);
                $map = $this->menuNameMap($ids);

                if (count($ids) < 2 || $map->count() !== count($ids)) {
                    continue;
                }

                $key = implode(',', $ids);
                if (isset($seenKeys[$key])) {
                    continue;
                }
                $seenKeys[$key] = true;

                $antecedent = implode(', ', $this->menuNames($rule['antecedent']));
                $consequent = implode(', ', $this->menuNames($rule['consequent']));

                $recommendations[] = [
                    'source'      => 'rule',
                    'menu_ids'    => $ids,
                    'menu_names'  => $this->menuNames($ids),
                    'support'     => round($rule['support'] * 100, 1),
                    'confidence'  => round($rule['confidence'] * 100, 1),
                    'lift'        => (float) $rule['lift'],
                    'count'       => (int) ($rule['support_count'] ?? 0),
                    'total'       => $total,
                    'description' => "Jika membeli {$antecedent}, cenderung menambah {$consequent}.",
                ];
            }
        }

        return array_slice($recommendations, 0, 6);
    }

    /**
     * Nama menu sesuai urutan ID (ID yang tidak ada datanya dilewati).
     *
     * @return array<int, string>
     */
    private function menuNames(array $menuIds): array
    {
        $map = $this->menuNameMap($menuIds);

        return collect($menuIds)
            ->map(fn ($id) => $map->get($id))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function menuNameMap(array $menuIds)
    {
        if (empty($menuIds)) {
            return collect();
        }

        return Menu::whereIn('id', $menuIds)->pluck('name', 'id');
    }

    /**
     * Inti algoritma Apriori pada kumpulan transaksi tertentu.
     */
    private function analyze(?int $userId, bool $personalized): array
    {
        $query = Order::whereIn('status', self::INCLUDED_STATUSES);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        $orders = $query
            ->with('orderDetails')
            ->latest()
            ->limit(1000)
            ->get();

        $transactions = [];
        foreach ($orders as $order) {
            $items = [];
            foreach ($order->orderDetails as $detail) {
                if ($detail->menu_id) {
                    $items[] = (int) $detail->menu_id;
                }
            }
            $items = array_unique($items);
            sort($items);
            if (!empty($items)) {
                $transactions[] = $items;
            }
        }

        $totalTransactions = count($transactions);
        if ($totalTransactions === 0) {
            return [
                'total_transactions' => 0,
                'personalized' => $personalized,
                'user_id' => $userId,
                'min_support' => $this->minSupport,
                'min_confidence' => $this->minConfidence,
                'frequent_itemsets' => [],
                'rules' => []
            ];
        }

        $frequentItemsets = [];
        $allFrequentWithSupport = [];

        // Step 1: 1-Itemset (L1)
        $itemCounts = [];
        foreach ($transactions as $tx) {
            foreach ($tx as $item) {
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1;
            }
        }

        $L1 = [];
        foreach ($itemCounts as $item => $count) {
            $support = $count / $totalTransactions;
            if ($support >= $this->minSupport) {
                $L1[] = ['items' => [$item], 'support' => $support, 'count' => $count];
                $allFrequentWithSupport[$item] = $support;
            }
        }

        if (empty($L1)) {
            return [
                'total_transactions' => $totalTransactions,
                'personalized' => $personalized,
                'user_id' => $userId,
                'min_support' => $this->minSupport,
                'min_confidence' => $this->minConfidence,
                'frequent_itemsets' => [],
                'rules' => []
            ];
        }

        // [MODIFIKASI] Mengurutkan 1-itemset dari yang terbesar ke terkecil
        usort($L1, fn($a, $b) => $b['support'] <=> $a['support']);

        $frequentItemsets[1] = array_slice($L1, 0, 50);
        $k = 2;
        $maxK = 4;

        // Step 2: Loop Lk (k = 2 sampai 4)
        while ($k <= $maxK) {
            $prevFrequentSets = array_map(function ($itemset) {
                return $itemset['items'];
            }, $frequentItemsets[$k - 1] ?? []);

            $candidates = $this->generateCandidates($prevFrequentSets, $k);
            if (empty($candidates)) {
                break;
            }

            $Lk = [];
            foreach ($candidates as $cand) {
                $count = 0;
                foreach ($transactions as $tx) {
                    if ($this->isSubset($cand, $tx)) {
                        $count++;
                    }
                }

                $support = $count / $totalTransactions;
                $isAllowed = ($support >= $this->minSupport);

                if ($isAllowed) {
                    sort($cand);
                    $Lk[] = ['items' => $cand, 'support' => $support, 'count' => $count];
                    $key = implode(',', $cand);
                    $allFrequentWithSupport[$key] = $support;
                }
            }

            if (empty($Lk)) {
                break;
            }

            usort($Lk, fn($a, $b) => $b['support'] <=> $a['support']);
            $frequentItemsets[$k] = array_slice($Lk, 0, 50);
            $k++;
        }

        // Step 3: Association Rules (Dilengkapi Perhitungan Lift)
        $rules = [];
        foreach ($frequentItemsets as $size => $levelItemsets) {
            if ($size < 2) {
                continue;
            }

            foreach ($levelItemsets as $itemsetInfo) {
                $items = $itemsetInfo['items'];
                $itemsetSupport = $itemsetInfo['support'];

                $subsets = $this->getSubsets($items);

                foreach ($subsets as $antecedent) {
                    $consequent = array_diff($items, $antecedent);
                    sort($antecedent);
                    sort($consequent);

                    if (empty($consequent)) {
                        continue;
                    }

                    $antecedentKey = count($antecedent) === 1 ? $antecedent[0] : implode(',', $antecedent);
                    $consequentKey = count($consequent) === 1 ? $consequent[0] : implode(',', $consequent);

                    $antecedentSupport = $allFrequentWithSupport[$antecedentKey] ?? 0;
                    $consequentSupport = $allFrequentWithSupport[$consequentKey] ?? 0;

                    if ($antecedentSupport > 0 && $consequentSupport > 0) {
                        $confidence = $itemsetSupport / $antecedentSupport;
                        
                        // Rumus Lift = Support(A U B) / (Support(A) * Support(B))
                        $lift = $itemsetSupport / ($antecedentSupport * $consequentSupport);

                        if ($confidence >= $this->minConfidence && $itemsetSupport >= $this->minSupport) {
                            $rules[] = [
                                'antecedent' => $antecedent,
                                'consequent' => $consequent,
                                'support' => $itemsetSupport,
                                'support_percentage' => round($itemsetSupport * 100, 2),
                                'confidence' => $confidence,
                                'confidence_percentage' => round($confidence * 100, 2),
                                'lift' => round($lift, 4),
                                // Jumlah transaksi pendukung (dipakai untuk menjelaskan
                                // rumus Support/Confidence secara konkret saat demo)
                                'support_count' => (int) $itemsetInfo['count'],
                                'antecedent_count' => (int) round($antecedentSupport * $totalTransactions),
                                'consequent_count' => (int) round($consequentSupport * $totalTransactions),
                            ];
                        }
                    }
                }
            }
        }

        // Urutkan rules secara global berdasarkan Lift tertinggi (Sangat Kuat -> Kuat -> Lemah)
        usort($rules, function($a, $b) {
            $liftA = $a['lift'] ?? 0;
            $liftB = $b['lift'] ?? 0;

            if ($liftB != $liftA) {
                return $liftB <=> $liftA;
            }
            if ($b['confidence'] == $a['confidence']) {
                return $b['support'] <=> $a['support'];
            }
            return $b['confidence'] <=> $a['confidence'];
        });

        return [
            'total_transactions' => $totalTransactions,
            'personalized' => $personalized,
            'user_id' => $userId,
            'min_support' => $this->minSupport,
            'min_confidence' => $this->minConfidence,
            'frequent_itemsets' => $frequentItemsets,
            'rules' => $rules
        ];
    }

    private function generateCandidates(array $prevFrequentSets, int $k): array
    {
        $candidates = [];
        $candidateKeys = [];
        $count = count($prevFrequentSets);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $set1 = $prevFrequentSets[$i];
                $set2 = $prevFrequentSets[$j];

                $join = array_unique(array_merge($set1, $set2));
                if (count($join) === $k) {
                    sort($join);
                    $key = implode(',', $join);
                    if (!isset($candidateKeys[$key])) {
                        $candidateKeys[$key] = true;
                        $candidates[] = $join;
                    }
                }
            }
        }

        return $candidates;
    }

    private function isSubset(array $subset, array $parent): bool
    {
        return count(array_diff($subset, $parent)) === 0;
    }

    private function getSubsets(array $array): array
    {
        $result = [[]];
        foreach ($array as $element) {
            foreach ($result as $combination) {
                $result[] = array_merge($combination, [$element]);
            }
        }

        $subsets = [];
        $fullCount = count($array);
        foreach ($result as $subset) {
            $subCount = count($subset);
            if ($subCount > 0 && $subCount < $fullCount) {
                sort($subset);
                $subsets[] = $subset;
            }
        }

        $uniqueSubsets = array_map("unserialize", array_unique(array_map("serialize", $subsets)));
        return array_values($uniqueSubsets);
    }
}