<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;

class CartBadge extends Component
{
    public $count = 0;

    protected $listeners = ['cartUpdated' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = collect(session('cart', []))->sum('qty');
    }

    public function render()
    {
        return view('livewire.customer.cart-badge');
    }
}