<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

// =========================================================
// PUBLIC ROUTES
// =========================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog & Paket Publik
Route::get('/catalog', \App\Http\Livewire\Customer\Catalog::class)->name('catalog');
Route::get('/packages', \App\Http\Livewire\Customer\Packages::class)->name('packages');

// Rute Rekomendasi Menu Publik / Akses Cepat Customer
Route::get('/rekomendasi-menu', \App\Http\Livewire\Customer\AprioriRecommendations::class)->name('customer.recommendations.public');

// Rute SPK Rencana Menu Berdasarkan Budget (dikelompokkan: Makanan, Minuman, Snack)
Route::get('/budget-planner', \App\Http\Livewire\Customer\BudgetPlanner::class)->name('customer.budget-planner');

// Rute Halaman Kontak & Pemesanan
Route::view('/contact-pesan', 'customer.contact')->name('contact.order');

// Auth Routes (Login, Register, & Lupa Password)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Rute Lupa Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================
// CUSTOMER ROUTES (Authenticated, role: customer)
// =========================================================

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', \App\Http\Livewire\Customer\Dashboard::class)->name('customer.dashboard');
    Route::get('/customer/cart', \App\Http\Livewire\Customer\Cart::class)->name('cart');
    Route::get('/checkout', \App\Http\Livewire\Customer\Checkout::class)->name('checkout');
    Route::get('/customer/orders', \App\Http\Livewire\Customer\CustomerOrders::class)->name('customer.orders');
    
    // Rute Rekomendasi Apriori (Internal Customer)
    Route::get('/customer/recommendations', \App\Http\Livewire\Customer\AprioriRecommendations::class)->name('customer.recommendations');
    
    // Profil & Notifikasi
    Route::get('/customer/profile', [AuthController::class, 'showProfile'])->name('customer.profile');
    Route::post('/customer/profile', [AuthController::class, 'updateProfile'])->name('customer.profile.update');

    Route::prefix('customer/notifications')->name('customer.notifications.')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'notifications' => Notification::where('user_id', Auth::id())->latest()->take(5)->get(),
                'unreadCount' => Notification::where('user_id', Auth::id())->where('is_read', false)->count()
            ]);
        })->name('json');
        
        Route::post('/read-all', function () {
            Notification::where('user_id', Auth::id())->update(['is_read' => true]);
            return response()->json(['success' => true]);
        })->name('read');
        
        Route::post('/{id}/delete', function ($id) {
            Notification::where('id', $id)->where('user_id', Auth::id())->delete();
            return response()->json(['success' => true]);
        })->name('delete');
    });
});

// =========================================================
// ADMIN ROUTES (Authenticated, role: admin)
// =========================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Http\Livewire\Admin\Dashboard::class)->name('dashboard');
    
    // Menggunakan string kelas penuh Livewire untuk menghindari konflik rute admin
    Route::get('/categories', \App\Http\Livewire\Admin\Categories::class)->name('categories');
    
    Route::get('/menus', \App\Http\Livewire\Admin\Menus::class)->name('menus');
    Route::get('/packages', \App\Http\Livewire\Admin\AdminPackages::class)->name('packages');
    Route::get('/customers', \App\Http\Livewire\Admin\Customers::class)->name('customers');
    
    // Rute Pesanan (Fokus Operasional / Dapur / Pengiriman)
    Route::get('/orders', \App\Http\Livewire\Admin\Orders::class)->name('orders');
    
    // Rute Transaksi (Fokus Keuangan & Rekap Pembayaran)
    Route::get('/transactions', \App\Http\Livewire\Admin\Transactions::class)->name('transactions');

    Route::get('/apriori', \App\Http\Livewire\Admin\Apriori::class)->name('apriori');
    Route::get('/apriori-proof', \App\Http\Livewire\Admin\AprioriProof::class)->name('apriori.proof');
    Route::get('/reports', \App\Http\Livewire\Admin\Reports::class)->name('reports');
    
    // Pengaturan Admin
    Route::get('/settings', \App\Http\Livewire\Admin\AdminProfileSettings::class)->name('settings');

    // Rute Membaca Notifikasi Pesanan Masuk Admin
    Route::get('/notifications/read/{id}', function ($id) {
        $order = Order::findOrFail($id);
        $order->update(['is_read' => true]); 
        
        return redirect()->route('admin.orders');
    })->name('notification.read');
});