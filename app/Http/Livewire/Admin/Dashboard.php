<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Menu;
use App\Models\Package;
use App\Models\Order;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function markAsReadAndRedirect($orderId)
    {
        $order = Order::find($orderId);
        
        if ($order) {
            // Ubah status is_read menjadi true dan simpan permanen ke database
            $order->is_read = true;
            $order->save();
        }

        // Redirect instan ke halaman kelola pesanan agar notifikasi langsung hilang & badge berkurang
        return redirect()->route('admin.orders'); 
    }

    public function render()
    {
        // 1. Stat Cards (Statistik Utama)
        $totalMenus = Menu::count();
        $totalPackages = Package::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['Selesai', 'Diproses'])->sum('total_price');

        // 2. Data Grafik Pesanan (6 Bulan Terakhir dari Database Asli)
        $chartData = [];
        $chartLabels = [];
        $monthlyOrders = []; 

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $label = $month->translatedFormat('M'); 
            $count = Order::whereYear('created_at', $month->year)
                          ->whereMonth('created_at', $month->month)
                          ->count();

            $chartLabels[] = $label;
            $chartData[] = $count;

            $monthlyOrders[] = [
                'label' => $label,
                'count' => $count,
            ];
        }

        // 3. Top 5 menus by order count (Menu Terlaris)
        $topMenus = Menu::withCount(['orderDetails as orders_count'])->orderByDesc('orders_count')->take(5)->get();

        // 4. Latest 5 orders (Pesanan Terbaru)
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        // 5. Notifikasi Pesanan Masuk (Hanya yang benar-benar is_read = false / 0)
        $recentOrders = Order::where('is_read', false)->latest()->take(5)->get();
        $unreadCount = Order::where('is_read', false)->count();

        return view('livewire.admin.dashboard', compact(
            'totalMenus', 
            'totalPackages', 
            'totalCustomers', 
            'totalOrders',
            'totalRevenue', 
            'chartData', 
            'chartLabels', 
            'monthlyOrders', 
            'topMenus', 
            'latestOrders',
            'recentOrders',
            'unreadCount'
        ))->layout('layouts.admin');
    }
}