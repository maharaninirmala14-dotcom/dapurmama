<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Services\AprioriService;
use App\Models\Menu;
use App\Models\Setting;

class AprioriRecommendations extends Component
{
    public $recommendations = [];
    public $rules = [];
    public bool $hasData = false;
    public bool $personalized = false;
    public $totalTransactions = 0;

    /** Input kebutuhan pelanggan (mengikuti arahan Pak Lilik) */
    public $totalBudget = null;   // total budget acara
    public $jumlahPax = null;     // jumlah orang / paket
    public $targetPerPaket = null; // hasil hitung: total budget ÷ jumlah pax
    public $budgetNote = null;     // catatan bila budget di luar jangkauan paket

    protected $queryString = [
        'totalBudget' => ['as' => 'budget', 'except' => ''],
        'jumlahPax'   => ['as' => 'pax', 'except' => ''],
    ];

    public function mount()
    {
        $this->totalBudget = request()->query('budget', $this->totalBudget);
        $this->jumlahPax   = request()->query('pax', $this->jumlahPax);
        $this->loadRecommendations();
    }

    public function updatedTotalBudget()
    {
        $this->loadRecommendations();
    }

    public function updatedJumlahPax()
    {
        $this->loadRecommendations();
    }

    public function search()
    {
        $this->loadRecommendations();
    }

    /**
     * Tentukan slot kategori sebuah menu (dipakai untuk menyusun paket).
     * Klasifikasi memakai SLUG kategori agar tidak tertukar dengan nama kategori.
     */
    private function slotOf(?Menu $menu): ?string
    {
        $slug = strtolower($menu->category?->slug ?? '');

        if (str_contains($slug, 'nasi') || str_contains($slug, 'makan')) return 'nasi';
        if (str_contains($slug, 'basah'))                          return 'basah';
        if (str_contains($slug, 'kering'))                         return 'kering';
        if (str_contains($slug, 'minum'))                          return 'minum';

        return null;
    }

    protected function loadRecommendations(): void
    {
        $service = new AprioriService();

        // Personalisasi: pelanggan yang login dianalisis dari riwayat transaksinya sendiri.
        // Jika riwayatnya belum cukup, service otomatis fallback ke data global.
        $result  = $service->run(auth()->id());

        $rules = $result['rules'] ?? [];
        $frequentItemsets = $result['frequent_itemsets'] ?? [];

        $this->personalized = (bool) ($result['personalized'] ?? false);
        $this->totalTransactions = $result['total_transactions'] ?? 0;

        // ── Normalisasi input pelanggan ──────────────────────────────
        $this->totalBudget = ($this->totalBudget !== null && $this->totalBudget !== '')
            ? (float) preg_replace('/[^0-9]/', '', (string) $this->totalBudget)
            : null;
        $this->jumlahPax = ($this->jumlahPax !== null && $this->jumlahPax !== '')
            ? (int) preg_replace('/[^0-9]/', '', (string) $this->jumlahPax)
            : null;

        // ── Target harga per paket = total budget ÷ jumlah pax ───────
        // (inti alur yang diminta Pak Lilik)
        $target = ($this->totalBudget && $this->jumlahPax)
            ? $this->totalBudget / $this->jumlahPax
            : null;
        $this->targetPerPaket = $target;

        if (empty($rules) && empty($frequentItemsets)) {
            $this->hasData = false;
            $this->recommendations = [];
            $this->rules = [];
            return;
        }

        $this->hasData = true;

        // ── Ambil semua menu + kategori sekali saja ──────────────────
        $allMenus = Menu::with('category')->get();
        $menuById = $allMenus->keyBy('id');

        // Kelompokkan menu ke tiap slot kategori (untuk menyusun komposisi paket)
        $slots = ['nasi' => [], 'basah' => [], 'kering' => [], 'minum' => []];
        foreach ($allMenus as $menu) {
            $slot = $this->slotOf($menu);
            if ($slot) {
                $slots[$slot][] = $menu;
            }
        }

        // Peta Support dari seluruh frequent itemset (untuk badge "pola riwayat")
        $supportMap = [];
        foreach ([2, 3, 4] as $size) {
            foreach (($frequentItemsets[$size] ?? []) as $info) {
                $ids = array_values($info['items'] ?? []);
                sort($ids);
                $supportMap[implode(',', $ids)] = round(($info['support'] ?? 0) * 100, 1);
            }
        }

        // ── Bangun KANDIDAT paket ────────────────────────────────────
        $pool = [];
        $seen = [];

        // (1) Kandidat dari frequent itemset Apriori (pola riwayat pelanggan)
        foreach ([4, 3, 2] as $size) {
            foreach (($frequentItemsets[$size] ?? []) as $info) {
                $ids = array_values($info['items'] ?? []);
                sort($ids);
                $key = implode(',', $ids);
                if (isset($seen[$key])) continue;

                $menus = $menuById->only($ids);
                if ($menus->count() !== count($ids)) continue;

                $seen[$key] = true;
                $pool[] = [
                    'ids'     => $ids,
                    'price'   => (float) $menus->sum('price'),
                    'support' => round(($info['support'] ?? 0) * 100, 1),
                    'source'  => 'apriori',
                ];
            }
        }

        // (2) Kandidat dari komposisi kategori: 1 Nasi + 1 Snack Basah + 1 Snack Kering + 1 Minuman
        if ($slots['nasi'] && $slots['basah'] && $slots['kering'] && $slots['minum']) {
            foreach ($slots['nasi'] as $nasi) {
                foreach ($slots['basah'] as $basah) {
                    foreach ($slots['kering'] as $kering) {
                        foreach ($slots['minum'] as $minum) {
                            $ids = [$nasi->id, $basah->id, $kering->id, $minum->id];
                            sort($ids);
                            $key = implode(',', $ids);
                            if (isset($seen[$key])) continue;

                            $seen[$key] = true;
                            $pool[] = [
                                'ids'     => $ids,
                                'price'   => (float) ($nasi->price + $basah->price + $kering->price + $minum->price),
                                'support' => $supportMap[$key] ?? null,
                                'source'  => 'komposisi',
                            ];
                        }
                    }
                }
            }
        }

        // ── Urutkan kandidat ─────────────────────────────────────────
        $this->budgetNote = null;
        if ($target !== null && !empty($pool)) {
            $prices = array_column($pool, 'price');
            $minP = min($prices);
            $maxP = max($prices);

            if ($target > $maxP) {
                $this->budgetNote = 'Budget per paket Rp ' . number_format($target, 0, ',', '.')
                    . ' lebih tinggi dari paket termahal yang bisa disusun (Rp ' . number_format($maxP, 0, ',', '.')
                    . '). Ditampilkan paket terbaik yang tersedia.';
            } elseif ($target < $minP) {
                $this->budgetNote = 'Budget per paket Rp ' . number_format($target, 0, ',', '.')
                    . ' lebih rendah dari paket paling ekonomis (Rp ' . number_format($minP, 0, ',', '.')
                    . '). Ditampilkan paket paling ekonomis.';
            }
        }

        if ($target !== null) {
            // UTAMA: paling mendekati harga per paket yang diinginkan pelanggan
            // SEKUNDER: pola yang lebih kuat (Support Apriori) didahulukan bila selisih sama
            usort($pool, function ($a, $b) use ($target) {
                $diff = abs($a['price'] - $target) <=> abs($b['price'] - $target);
                if ($diff !== 0) return $diff;
                return ($b['support'] ?? 0) <=> ($a['support'] ?? 0);
            });
        } else {
            // Tanpa input budget: ikut kekuatan pola Apriori (Support tertinggi)
            usort($pool, function ($a, $b) {
                return ($b['support'] ?? 0) <=> ($a['support'] ?? 0);
            });
        }

        $maxPackages = 6;
        $chosen = array_slice($pool, 0, $maxPackages);

        // ── Bentuk kartu paket untuk kandidat terpilih ───────────────
        $dynamicPackages = [];
        $id = 1;
        foreach ($chosen as $c) {
            $menus = $menuById->only($c['ids']);

            if ($target !== null) {
                $selisih = abs($c['price'] - $target);
                $description = 'Selisih Rp ' . number_format($selisih, 0, ',', '.')
                    . ' dari budget per paket Anda (Rp ' . number_format($target, 0, ',', '.') . ').';
                if ($c['source'] === 'apriori') {
                    $description .= ' Kombinasi ini juga termasuk pola langganan Anda.';
                }
            } else {
                $description = 'Kombinasi menu yang sering dipesan bersama, dihitung dari riwayat transaksi Anda.';
            }

            $pkg = $this->buildStructuredPackage(
                $menus,
                (float) ($c['support'] ?? 0),
                'Paket ' . $id,
                $description,
                $id
            );
            $pkg['price']   = $c['price'];
            $pkg['support'] = $c['support'];
            $pkg['source']  = $c['source'];
            $pkg['target']  = $target;

            $dynamicPackages[] = $pkg;
            $id++;
        }

        $this->recommendations = $dynamicPackages;

        // ── Aturan asosiasi (tetap ditampilkan sebagai hasil Apriori) ─
        $sortedRules = collect($rules)->sortByDesc('confidence')->values();
        $formattedRules = [];

        foreach ($sortedRules as $r) {
            $allMenuIds = array_unique(array_merge($r['antecedent'], $r['consequent']));
            $menusObj = $menuById->only($allMenuIds);

            $formattedRules[] = [
                'antecedent'  => $menusObj->whereIn('id', $r['antecedent'])->pluck('name')->toArray(),
                'consequent'  => $menusObj->whereIn('id', $r['consequent'])->pluck('name')->toArray(),
                'confidence'  => round($r['confidence'] * 100, 1),
                'support'     => round($r['support'] * 100, 1),
                'lift'        => $r['lift'],
                'total_price' => (float) $menusObj->sum('price'),
            ];
        }

        $this->rules = $formattedRules;
    }

    /**
     * Susun daftar menu ke slot paket terstruktur (Nasi, Basah, Kering, Minum).
     */
    private function buildStructuredPackage($menusObj, float $score, string $name, string $description, int $id): array
    {
        $structuredMenus = [
            'nasi'   => null,
            'basah'  => null,
            'kering' => null,
            'minum'  => null,
        ];

        $usedMenuIds = [];

        foreach ($menusObj as $menu) {
            $slot = $this->slotOf($menu);
            if ($slot && !$structuredMenus[$slot]) {
                $structuredMenus[$slot] = $menu;
                $usedMenuIds[] = $menu->id;
            }
        }

        // Fallback slot kosong: pakai menu yang belum kepake, biar tidak duplikat
        $remainingMenus = $menusObj->reject(fn ($menu) => in_array($menu->id, $usedMenuIds))->values()->all();
        foreach (['nasi', 'basah', 'kering', 'minum'] as $slot) {
            if (!$structuredMenus[$slot] && !empty($remainingMenus)) {
                $structuredMenus[$slot] = array_shift($remainingMenus);
            }
        }

        return [
            'id'          => $id,
            'name'        => $name,
            'description' => $description,
            'price'       => (float) $menusObj->sum('price'),
            'image'       => null,
            'structured'  => $structuredMenus,
            'score'       => $score,
        ];
    }

    /**
     * Masukkan paket rekomendasi ke keranjang.
     * Jumlah pax mengikuti input pelanggan (default 20 pax).
     */
    public function selectPackage(array $menuIds, $pax = null)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $minimumPax = 20;
        $qty = ($pax !== null && (int) $pax > 0) ? (int) $pax : $minimumPax;
        $cart = session()->get('cart', []);

        foreach ($menuIds as $menuId) {
            if (!empty($menuId) && is_numeric($menuId) && $menuId > 0) {
                $menu = Menu::find($menuId);
                if ($menu) {
                    $cartKey = $menu->id; // Menggunakan ID menu sebagai key session cart

                    if (isset($cart[$cartKey])) {
                        $cart[$cartKey]['qty'] = max($cart[$cartKey]['qty'], $qty);
                    } else {
                        $cart[$cartKey] = [
                            'id'    => $menu->id,
                            'name'  => $menu->name,
                            'price' => $menu->price,
                            'qty'   => $qty,
                            'image' => $menu->image ?? null,
                        ];
                    }
                }
            }
        }

        session()->put('cart', $cart);
        session()->flash('success', "Paket rekomendasi berhasil ditambahkan ke keranjang ({$qty} pax)!");

        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.customer.apriori-recommendations', [
            'recommendations' => $this->recommendations,
            'rules' => $this->rules,
            'hasData' => $this->hasData,
            'personalized' => $this->personalized,
            'totalTransactions' => $this->totalTransactions,
            'totalBudget' => $this->totalBudget,
            'jumlahPax' => $this->jumlahPax,
            'targetPerPaket' => $this->targetPerPaket,
            'budgetNote' => $this->budgetNote,
            // Ditampilkan saja di halaman (bukan parameter hitung — lihat AprioriService)
            'minSupport' => (float) Setting::getValue('min_support', 0.2),
            'minConfidence' => (float) Setting::getValue('min_confidence', 0.5),
        ])->layout('layouts.customer');
    }
}
