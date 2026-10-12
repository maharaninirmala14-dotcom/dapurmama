<?php

namespace App\Providers;
namespace App\Http\Livewire\Customer;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire; // <-- Pastikan ini diimport

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Daftarkan komponen customer.cart-badge secara eksplisit
        Livewire::component('customer.cart-badge', \App\Http\Livewire\Customer\CartBadge::class);
    }
}