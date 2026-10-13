<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire; // <--- Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // DAFTARKAN KOMPONEN LIVEWIRE DI SINI
        Livewire::component('customer.apriori-recommendations', \App\Http\Livewire\Customer\AprioriRecommendations::class);
    }
}