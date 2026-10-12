<?php

namespace App\Services;

use App\Models\Menu;

/**
 * BudgetRecommendationService — Mesin SPK (Sistem Penunjang Keputusan)
 * rekomendasi menu berdasarkan budget pelanggan.
 *
 * Cara kerja singkat:
 *  1. Semua menu dikelompokkan menjadi 3 kelompok: Makanan, Minuman, Snack.
 *  2. Budget dibagi ke tiap kelompok memakai proporsi alokasi (default 60/25/15).
 *  3. Untuk setiap kelompok dipilih 1 menu terbaik yang harganya tidak melebihi
 *     alokasi kelompok tersebut (memanfaatkan budget semaksimal mungkin).
 *  4. Total ketiga menu dijamin tidak melebihi budget.
 */
class BudgetRecommendationService
{
    /**
     * Alokasi budget default per kelompok. Total harus = 1.0.
     * Dapat diubah dari pemanggil bila ingin komposisi berbeda.
     */
    public const DEFAULT_ALLOCATION = [
        'makanan' => 0.60,
        'minuman' => 0.15,
        'snack'   => 0.25,
    ];

    /**
     * Metadata tiap kelompok untuk ditampilkan di UI.
     * Urutan array = urutan tampil (Makanan, Minuman, Snack).
     */
    public const GROUPS = [
        'makanan' => [
            'label'       => 'Makanan',
            'emoji'       => '🍚',
            'description' => 'Nasi box, tumpeng, & hidangan utama',
        ],
        'minuman' => [
            'label'       => 'Minuman',
            'emoji'       => '🥤',
            'description' => 'Aneka minuman segar',
        ],
        'snack'   => [
            'label'       => 'Snack',
            'emoji'       => '🍪',
            'description' => 'Snack basah & snack kering',
        ],
    ];

    /**
     * Kata kunci untuk memetakan nama/slug kategori ke kelompok SPK.
     * Dicocokkan case-insensitive berdasarkan urutan array ini
     * (Makanan didahulukan agar "nasi box" tidak tertangkap sebagai snack).
     */
    protected const KEYWORDS = [
        'makanan' => ['nasi', 'box', 'tumpeng', 'prasmanan', 'makanan', 'lauk'],
        'minuman' => ['minum', 'drink', 'beverage', 'jus'],
        'snack'   => ['snack', 'kering', 'basah', 'dessert', 'jajanan', 'kue', 'camilan'],
    ];

    /**
     * Tentukan kelompok SPK sebuah menu berdasarkan kategori.
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
     * Susun rekomendasi menu berdasarkan budget.
     *
     * @param  float       $budget     Budget yang diinput pelanggan (Rupiah).
     * @param  int|null    $pax        Jumlah porsi/orang. Bila diisi, budget dihitung per pax.
     * @param  array|null  $allocation Proporsi alokasi per kelompok (opsional).
     * @return array
     */
    public function recommend(float $budget, ?int $pax = null, ?array $allocation = null): array
    {
        $budget = max(0.0, $budget);
        $allocation = $this->normalizeAllocation($allocation);
        $pax = ($pax !== null && $pax > 0) ? (int) $pax : null;

        // Budget per set (per porsi/orang). Bila pax kosong, budget dianggap untuk satu set.
        $perSetBudget = $pax ? ($budget / $pax) : $budget;

        // Ambil semua menu + kategori sekali saja (hindari N+1).
        $menus = Menu::with('category')->get();

        // Kelompokkan menu ke 3 bucket.
        // PENTING: tiap bucket harus objek Collection terpisah (array_fill_keys
        // akan memakai satu instance yang sama untuk semua key -> bug).
        $buckets = [];
        foreach (array_keys(self::GROUPS) as $groupKey) {
            $buckets[$groupKey] = collect();
        }

        foreach ($menus as $menu) {
            $group = $this->groupOfCategory($menu->category?->name, $menu->category?->slug);
            if ($group !== null) {
                $buckets[$group]->push($menu);
            }
        }

        $groups = [];
        $totalPerSet = 0.0;
        $notes = [];

        foreach (self::GROUPS as $key => $meta) {
            $targetSpend = $perSetBudget * $allocation[$key];
            $allInGroup = $buckets[$key];

            // Urutkan menu harga menurun.
            $sorted = $allInGroup->sortByDesc(fn (Menu $m) => (float) $m->price)->values();

            // Kandidat yang masuk alokasi kelompok, termahal dulu (memanfaatkan budget maksimal).
            $within = $sorted
                ->filter(fn (Menu $m) => (float) $m->price <= $targetSpend + 0.001)
                ->values();

            $withinAllocation = $within->isNotEmpty();

            if ($withinAllocation) {
                $options = $within->take(4);
            } else {
                // Tidak ada menu yang muat di alokasi -> pakai menu termurah agar tetap ada rekomendasi.
                $cheapest = $sorted->last();
                $options = $cheapest ? collect([$cheapest]) : collect();
            }

            $primary = $options->first();
            $alternatives = $options;

            $reason = null;
            if ($primary instanceof Menu) {
                $price = (float) $primary->price;
                if ($withinAllocation) {
                    $reason = sprintf(
                        'Harga Rp %s memakai %.0f%% dari alokasi %s (Rp %s).',
                        number_format($price, 0, ',', '.'),
                        $allocation[$key] * 100,
                        $meta['label'],
                        number_format($targetSpend, 0, ',', '.')
                    );
                } else {
                    $reason = sprintf(
                        'Alokasi %s (Rp %s) belum cukup; dipilih menu termurah Rp %s.',
                        $meta['label'],
                        number_format($targetSpend, 0, ',', '.'),
                        number_format($price, 0, ',', '.')
                    );
                    $notes[] = sprintf(
                        'Budget terlalu kecil untuk kelompok %s, sistem memakai menu termurah.',
                        $meta['label']
                    );
                }

                $totalPerSet += $price;
            } else {
                $notes[] = sprintf('Belum ada menu terdaftar untuk kelompok %s.', $meta['label']);
            }

            $groups[$key] = [
                'key'                => $key,
                'label'              => $meta['label'],
                'emoji'              => $meta['emoji'],
                'description'        => $meta['description'],
                'allocation'         => $targetSpend,
                'allocation_percent' => $allocation[$key] * 100,
                'primary'            => $primary,
                'alternatives'       => $alternatives,
                'within_allocation'  => $withinAllocation,
                'reason'             => $reason,
                'menu_count'         => $allInGroup->count(),
            ];
        }

        // Pengaman terakhir: pastikan total tidak melebihi budget per set.
        $totalPerSet = $this->enforceBudget($groups, $perSetBudget, $totalPerSet, $notes);

        $totalAll = $totalPerSet * ($pax ?? 1);
        $remainingPerSet = max(0.0, $perSetBudget - $totalPerSet);
        $remainingAll = max(0.0, $budget - $totalAll);

        return [
            'budget'            => $budget,
            'pax'               => $pax,
            'per_set_budget'    => $perSetBudget,
            'allocation'        => $allocation,
            'groups'            => $groups,
            'total_per_set'     => $totalPerSet,
            'total_all'         => $totalAll,
            'remaining_per_set' => $remainingPerSet,
            'remaining_all'     => $remainingAll,
            'has_result'        => $totalPerSet > 0,
            'has_menu'          => $buckets['makanan']->isNotEmpty()
                                    || $buckets['minuman']->isNotEmpty()
                                    || $buckets['snack']->isNotEmpty(),
            'notes'             => array_values(array_unique($notes)),
        ];
    }

    /**
     * Pastikan total harga tidak melebihi budget per set.
     * Bila melebihi (mis. karena ada kelompok yang alokasinya terlalu kecil),
     * tukar item dengan menu yang lebih murah secara iteratif.
     */
    protected function enforceBudget(array &$groups, float $perSetBudget, float $totalPerSet, array &$notes): float
    {
        if ($totalPerSet <= $perSetBudget + 0.001) {
            return $totalPerSet;
        }

        $guard = 0;
        while ($totalPerSet > $perSetBudget + 0.001 && $guard < 20) {
            $guard++;

            // Cari kelompok dengan harga primary termahal untuk diturunkan dulu.
            $candidateKey = null;
            $candidatePrice = -1.0;
            foreach ($groups as $key => $group) {
                if (!($group['primary'] instanceof Menu)) {
                    continue;
                }
                $price = (float) $group['primary']->price;
                if ($price > $candidatePrice) {
                    $candidatePrice = $price;
                    $candidateKey = $key;
                }
            }

            if ($candidateKey === null) {
                break;
            }

            // Ambil menu termurah di kelompok tersebut.
            $group = $groups[$candidateKey];
            $cheapest = collect($group['alternatives'])->last();
            if (!$cheapest instanceof Menu) {
                break;
            }

            $cheapestPrice = (float) $cheapest->price;
            if ($cheapestPrice >= $candidatePrice) {
                break; // tidak ada penurunan
            }

            $totalPerSet -= ($candidatePrice - $cheapestPrice);
            $groups[$candidateKey]['primary'] = $cheapest;
            $groups[$candidateKey]['reason'] = sprintf(
                'Disesuaikan: dipilih menu Rp %s agar total tetap sesuai budget.',
                number_format($cheapestPrice, 0, ',', '.')
            );
        }

        if ($totalPerSet > $perSetBudget + 0.001) {
            $notes[] = 'Total terbaik yang bisa disusun sedikit melebihi budget; silakan tambah budget.';
        }

        return max(0.0, $totalPerSet);
    }

    /**
     * Normalisasi proporsi alokasi agar selalu berjumlah 1.0.
     */
    protected function normalizeAllocation(?array $allocation): array
    {
        $allocation = $allocation ?: self::DEFAULT_ALLOCATION;

        $normalized = [];
        foreach (array_keys(self::GROUPS) as $key) {
            $normalized[$key] = max(0.0, (float) ($allocation[$key] ?? 0));
        }

        $sum = array_sum($normalized);
        if ($sum <= 0) {
            return self::DEFAULT_ALLOCATION;
        }

        foreach ($normalized as $key => $value) {
            $normalized[$key] = $value / $sum;
        }

        return $normalized;
    }
}
