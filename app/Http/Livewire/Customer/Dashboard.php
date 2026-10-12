<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Menu;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.customer')]
class Dashboard extends Component
{
    public function addToCart($menuId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $menu = Menu::find($menuId);
        if (!$menu) {
            session()->flash('error', 'Menu tidak ditemukan.');
            return;
        }

        $cart = Cart::where('user_id', Auth::id())
                    ->where('menu_id', $menuId)
                    ->first();

        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'menu_id' => $menuId,
                'quantity' => 1,
                'price' => $menu->price
            ]);
        }

        $this->dispatch('cartUpdated');
        session()->flash('message', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function render()
    {
        // 1. Ambil data menu rekomendasi untuk disinkronkan dengan halaman rekomendasi
        $recommendedMenus = Menu::where('is_recommended', true)
                                    ->take(4)
                                    ->get();

        // 2. Ambil data pesanan terakhir milik customer yang sedang login diurutkan dari yang terbaru (latest)
        $recentOrders = Auth::check() 
            ? Auth::user()->orders()->latest()->take(3)->get() 
            : collect();

        return view('livewire.customer.dashboard', [
            'recommendedMenus' => $recommendedMenus,
            'recentOrders' => $recentOrders
        ]);
    }
}