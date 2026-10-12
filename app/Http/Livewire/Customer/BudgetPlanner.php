<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Menu;
use App\Services\BudgetRecommendationService;

/**
 * BudgetPlanner — Halaman SPK untuk pelanggan.
 *
 * Pelanggan menginput budget (opsional beserta jumlah pax), lalu sistem
 * menampilkan rekomendasi menu yang dikelompokkan menjadi Makanan, Minuman,
 * dan Snack. Tiap kelompok diisi satu menu terbaik sesuai alokasi budget.
 */
class BudgetPlanner extends Component
{
    /** Budget yang diinput pelanggan. */
    public $budget = null;

    /** Jumlah porsi/orang (opsional). */
    public $pax = null;

    /** Hasil perhitungan SPK. */
    public array $result = [];

    protected $queryString = [
        'budget' => ['as' => 'budget', 'except' => ''],
        'pax'    => ['as' => 'pax', 'except' => ''],
    ];

    public function mount(): void
    {
        $this->budget = request()->query('budget', $this->budget);
        $this->pax    = request()->query('pax', $this->pax);

        // Hitung otomatis bila budget sudah dibawa lewat URL.
        if ($this->budget !== null && $this->budget !== '') {
            $this->recommend();
        }
    }

    /**
     * Hitung ulang rekomendasi berdasarkan input pelanggan.
     */
    public function recommend(): void
    {
        $this->resetErrorBag();
        $this->result = [];

        $budget = (float) preg_replace('/[^0-9]/', '', (string) $this->budget);
        $paxRaw = preg_replace('/[^0-9]/', '', (string) $this->pax);
        $pax    = ($paxRaw !== '') ? (int) $paxRaw : null;

        if ($budget <= 0) {
            $this->addError('budget', 'Masukkan budget yang valid (lebih dari 0).');
            return;
        }

        $service = new BudgetRecommendationService();
        $this->result = $service->recommend($budget, $pax);

        if (empty($this->result['has_result'])) {
            $this->dispatch('notify', message: 'Belum ada menu yang cocok dengan budget tersebut.', type: 'warning');
        }
    }

    /**
     * Minimal pemesanan mengikuti aturan yang dipakai di Katalog/Keranjang.
     */
    private function minQtyFor(Menu $menu): int
    {
        $name = strtolower(trim($menu->category?->name ?? ''));
        $slug = strtolower(trim($menu->category?->slug ?? ''));

        if (str_contains($name, 'nasi') || str_contains($name, 'box') || $slug === 'nasi-box') return 20;
        if (str_contains($name, 'basah') || $slug === 'snack-basah') return 20;
        if (str_contains($name, 'kering') || $slug === 'snack-kering') return 1;
        if (str_contains($name, 'minuman') || $slug === 'minuman') return 20;

        return 1;
    }

    private function resolvePax(): int
    {
        $paxRaw = preg_replace('/[^0-9]/', '', (string) $this->pax);

        return ($paxRaw !== '') ? max(1, (int) $paxRaw) : 1;
    }

    /**
     * Tambahkan satu menu rekomendasi ke keranjang.
     */
    public function addMenuToCart(int $menuId): void
    {
        $menu = Menu::with('category')->find($menuId);
        if (!$menu) {
            $this->dispatch('notify', message: 'Menu tidak ditemukan.', type: 'error');
            return;
        }

        $minQty = $this->minQtyFor($menu);
        $qty = max($this->resolvePax(), $minQty);

        $cart = session()->get('cart', []);
        $cartKey = 'menu_' . $menu->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                'id'            => $menu->id,
                'name'          => $menu->name,
                'price'         => (float) $menu->price,
                'type'          => 'menu',
                'qty'           => $qty,
                'image'         => $menu->image,
                'category_name' => $menu->category?->name ?? 'Umum',
                'min_qty'       => $minQty,
            ];
        }

        session()->put('cart', $cart);
        session()->save();

        $this->dispatch('cartUpdated', count: (int) collect($cart)->sum('qty'));
        $this->dispatch('notify', message: "\"{$menu->name}\" ditambahkan ke keranjang ({$qty} porsi).", type: 'success');
    }

    /**
     * Tambahkan seluruh set rekomendasi (1 makanan + 1 minuman + 1 snack).
     */
    public function addSetToCart(): void
    {
        $groups = $this->result['groups'] ?? [];

        if (empty($groups)) {
            $this->dispatch('notify', message: 'Cari rekomendasi terlebih dahulu sebelum menambahkan.', type: 'error');
            return;
        }

        $pax   = $this->resolvePax();
        $cart  = session()->get('cart', []);
        $added = 0;

        foreach ($groups as $group) {
            $menu = $group['primary'] ?? null;
            if (!$menu instanceof Menu) {
                continue;
            }

            $minQty  = $this->minQtyFor($menu);
            $qty     = max($pax, $minQty);
            $cartKey = 'menu_' . $menu->id;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['qty'] += $qty;
            } else {
                $cart[$cartKey] = [
                    'id'            => $menu->id,
                    'name'          => $menu->name,
                    'price'         => (float) $menu->price,
                    'type'          => 'menu',
                    'qty'           => $qty,
                    'image'         => $menu->image,
                    'category_name' => $menu->category?->name ?? 'Umum',
                    'min_qty'       => $minQty,
                ];
            }

            $added++;
        }

        session()->put('cart', $cart);
        session()->save();

        $this->dispatch('cartUpdated', count: (int) collect($cart)->sum('qty'));
        $this->dispatch('notify', message: "{$added} menu set berhasil ditambahkan ke keranjang ({$pax} porsi).", type: 'success');
    }

    public function render()
    {
        return view('livewire.customer.budget-planner', [
            'result' => $this->result,
        ])->layout('layouts.customer');
    }
}
