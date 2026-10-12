<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Package;
use App\Services\AprioriService;

class HomeController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index(AprioriService $aprioriService)
    {
        $featuredFromApriori = false;

        try {
            // Mengambil 3 rekomendasi menu teratas berdasarkan analisis Apriori
            $featuredMenus = $aprioriService->getRecommendations(3);
            $featuredFromApriori = $featuredMenus->isNotEmpty();
        } catch (\Throwable $e) {
            $featuredMenus = collect();
        }

        // Jika hasil Apriori kosong, ambil menu populer (ditandai agar tidak
        // menyamar sebagai hasil analisis Apriori)
        if ($featuredMenus->isEmpty()) {
            $featuredMenus = Menu::with('category')->take(3)->get();
            $featuredFromApriori = false;
        }

        try {
            $packages = Package::take(3)->get();
        } catch (\Throwable $e) {
            $packages = collect();
        }

        return view('welcome', compact('featuredMenus', 'packages', 'featuredFromApriori'));
    }
}