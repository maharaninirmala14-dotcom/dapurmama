<?php

namespace App\Http\Livewire\Customer;


use Livewire\Component;
use App\Models\Package;
use App\Models\Menu;
use App\Services\AprioriService;

class Packages extends Component
{
    /**
     * Add a package to the cart.
     */
    public function addPackageToCart(int $id): void
    {
        $package = Package::find($id);
        if (!$package) {
            $this->dispatch('notify', message: 'Paket tidak ditemukan.', type: 'error');
            return;
        }

        $cart = session('cart', []);
        $cartKey = 'package_' . $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += 1;
        } else {
            $cart[$cartKey] = [
                'id' => $package->id,
                'name' => $package->name,
                'price' => (float) $package->price,
                'type' => 'package',
                'qty' => 1,
                'image' => $package->image,
            ];
        }

        session(['cart' => $cart]);

        $this->dispatch('cartUpdated', count: (int) collect($cart)->sum('qty'));
        $this->dispatch('notify', message: "\"{$package->name}\" ditambahkan ke keranjang!", type: 'success');
    }

    /**
     * Add a recommended menu to the cart.
     */
    public function addMenuToCart(int $id): void
    {
        $menu = Menu::find($id);
        if (!$menu) {
            $this->dispatch('notify', message: 'Menu tidak ditemukan.', type: 'error');
            return;
        }

        $cart = session('cart', []);
        $cartKey = 'menu_' . $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += 1;
        } else {
            $cart[$cartKey] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'type' => 'menu',
                'qty' => 1,
                'image' => $menu->image,
            ];
        }

        session(['cart' => $cart]);

        $this->dispatch('cartUpdated', count: (int) collect($cart)->sum('qty'));
        $this->dispatch('notify', message: "\"{$menu->name}\" ditambahkan ke keranjang!", type: 'success');
    }

    public function render()
    {
        // 1. Fetch Packages
        $packages = Package::with('menus')->get();

        // 2. Fetch Apriori recommendations based on current cart + riwayat pelanggan login
        $cart = session('cart', []);
        
        // Extract menu IDs currently in cart
        $cartMenuIds = [];
        foreach ($cart as $item) {
            if (($item['type'] ?? null) === 'menu') {
                $cartMenuIds[] = (int) $item['id'];
            }
        }

        // Rekomendasi berbasis cart: rules yang antecedent-nya cocok dengan isi keranjang.
        // Pelanggan login dianalisis dari riwayat transaksinya sendiri (fallback ke global).
        $aprioriService = new AprioriService();
        $recommendations = $aprioriService->getRecommendationsForCart($cartMenuIds, auth()->id());

        return view('livewire.customer.packages', [
            'packages' => $packages,
            'recommendedMenus' => $recommendations['recommended_menus'] ?? [],
            'recommendedPackages' => $recommendations['recommended_packages'] ?? [],
            'explanationRules' => $recommendations['explanation_rules'] ?? [],
            'cartMenuIds' => $cartMenuIds
        ])->layout('layouts.app');
    }
}
