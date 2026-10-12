<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Menu;

class Cart extends Component
{
    public $cart = [];
    public $notes = '';

    public bool $showClearModal = false;
    public bool $showRemoveModal = false;
    public $removeKey = null;
    public string $removeName = '';

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $rawCart = session()->get('cart', []);
        $this->cart = [];

        foreach ($rawCart as $key => $item) {
            $menu = Menu::with('category')->find($item['id']);
            $categoryName = $menu && $menu->category ? strtolower($menu->category->name) : '';

            $minQty = 1; // Default Tumpeng (bebas)
            if (str_contains($categoryName, 'nasi box') || str_contains($categoryName, 'nasi')) {
                $minQty = 20; // Nasi Box minimal 20 kotak
            } elseif (str_contains($categoryName, 'snack basah') || str_contains($categoryName, 'basah')) {
                $minQty = 20; // Snack Basah minimal 20 pcs
            } elseif (str_contains($categoryName, 'snack kering') || str_contains($categoryName, 'kering')) {
                $minQty = 1;  // Snack Kering minimal 1 kg
            } elseif (str_contains($categoryName, 'minuman')) {
                $minQty = 20; // Minuman minimal 20 gelas
            }

            $item['min_qty'] = $minQty;
            $item['category_name'] = $menu && $menu->category ? $menu->category->name : 'Umum';

            if ($item['qty'] < $minQty) {
                $item['qty'] = $minQty;
            }

            $this->cart[$key] = $item;
        }

        session()->put('cart', $this->cart);
    }

    private function cartCount(): int
    {
        return (int) collect($this->cart)->sum('qty');
    }

    public function increment($key)
    {
        if (isset($this->cart[$key])) {
            $this->cart[$key]['qty'] += 1;
            session()->put('cart', $this->cart);
            $this->dispatch('cartUpdated', count: $this->cartCount());
        }
    }

    public function decrement($key)
    {
        if (isset($this->cart[$key])) {
            $minQty = $this->cart[$key]['min_qty'] ?? 1;

            if ($this->cart[$key]['qty'] > $minQty) {
                $this->cart[$key]['qty'] -= 1;
                session()->put('cart', $this->cart);
                $this->dispatch('cartUpdated', count: $this->cartCount());
            } else {
                $this->dispatch('notify', message: "Maaf, minimal pemesanan untuk menu ini adalah {$minQty}.", type: 'error');
            }
        }
    }

    // Fungsi baru untuk menangani input angka langsung dari kolom jumlah pesanan
    public function updateQty($key, $value)
    {
        if (isset($this->cart[$key])) {
            $minQty = $this->cart[$key]['min_qty'] ?? 1;
            $parsedQty = (int) $value;

            // Pastikan tidak kurang dari batas minimal pemesanan
            $newQty = max($minQty, $parsedQty);

            if ($parsedQty < $minQty) {
                $this->dispatch('notify', message: "Jumlah disesuaikan ke minimal pemesanan: {$minQty}.", type: 'warning');
            }

            $this->cart[$key]['qty'] = $newQty;
            session()->put('cart', $this->cart);
            $this->dispatch('cartUpdated', count: $this->cartCount());
        }
    }

    public function confirmRemoveItem($key)
    {
        if (isset($this->cart[$key])) {
            $this->removeKey = $key;
            $this->removeName = $this->cart[$key]['name'] ?? 'item ini';
            $this->showRemoveModal = true;
        }
    }

    public function closeRemoveModal()
    {
        $this->showRemoveModal = false;
        $this->removeKey = null;
        $this->removeName = '';
    }

    public function removeItem()
    {
        $key = $this->removeKey;

        if ($key !== null && isset($this->cart[$key])) {
            unset($this->cart[$key]);
            session()->put('cart', $this->cart);
            $this->dispatch('cartUpdated', count: $this->cartCount());
        }

        $this->closeRemoveModal();
    }

    public function confirmClearCart()
    {
        $this->showClearModal = true;
    }

    public function closeClearModal()
    {
        $this->showClearModal = false;
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->cart = [];
        $this->dispatch('cartUpdated', count: 0);
        $this->dispatch('cartCleared');
        $this->closeClearModal();
    }

    public function render()
    {
        $subtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        // Ongkos kirim diset 0 di halaman keranjang (akan dihitung saat checkout/pilih wilayah)
        $shippingCost = 0; 
        $total = $subtotal + $shippingCost;

        return view('livewire.customer.cart', [
            'cartItems' => $this->cart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'total' => $total,
        ])->layout('layouts.customer');
    }
}