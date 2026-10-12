<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CleanAprioriOrderSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan data pesanan lama agar database bersih dari data sampah
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrderDetail::truncate();
        Order::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ambil semua customer (role customer)
        $customers = User::where('role', 'customer')->get();
        if ($customers->isEmpty()) {
            $customers = User::all(); // Fallback jika role belum diset
        }

        // Ambil menu berdasarkan kategori
        $nasiBox = Menu::whereHas('category', function($q) {
            $q->where('name', 'like', '%Nasi%')
              ->orWhere('name', 'like', '%Box%');
        })->get();

        $snackBasah = Menu::whereHas('category', function($q) {
            $q->where('name', 'like', '%Basah%')
              ->orWhere('name', 'like', '%Snack%');
        })->get();

        $snackKering = Menu::whereHas('category', function($q) {
            $q->where('name', 'like', '%Kering%');
        })->get();

        $minuman = Menu::whereHas('category', function($q) {
            $q->where('name', 'like', '%Minuman%')
              ->orWhere('name', 'like', '%Es%');
        })->get();

        // Fallback jika kategori tidak spesifik, ambil acak dari semua menu
        $allMenus = Menu::all();
        if ($nasiBox->isEmpty()) $nasiBox = $allMenus;
        if ($snackBasah->isEmpty()) $snackBasah = $allMenus;
        if ($snackKering->isEmpty()) $snackKering = $allMenus;
        if ($minuman->isEmpty()) $minuman = $allMenus;

        // 3. Buat "Paket Unggulan" buatan (bundling) agar Apriori punya pola kuat
        $bundles = [
            [$nasiBox->random(), $snackBasah->random(), $minuman->random()],
            [$nasiBox->random(), $snackBasah->random(), $snackKering->random(), $minuman->random()],
            [$snackBasah->random(), $snackKering->random(), $minuman->random()],
            [$nasiBox->random(), $snackKering->random(), $minuman->random()],
        ];

        // 4. Generate 800 Transaksi Selesai
        $startDate = Carbon::now()->subDays(90); // Rentang 3 bulan ke belakang

        for ($i = 1; $i <= 800; $i++) {
            $customer = $customers->random();
            $orderCode = 'ORD-' . strtoupper(Str::random(8));
            $orderDate = $startDate->copy()->addHours(rand(8, 20));

            $selectedItems = [];
            if (rand(1, 100) <= 70) {
                $chosenBundle = $bundles[array_rand($bundles)];
                foreach ($chosenBundle as $item) {
                    if ($item && !in_array($item->id, collect($selectedItems)->pluck('id')->toArray())) {
                        $selectedItems[] = $item;
                    }
                }
            }

            $categoriesPool = [$nasiBox, $snackBasah, $snackKering, $minuman];
            foreach ($categoriesPool as $cat) {
                if ($cat->isNotEmpty() && rand(1, 100) <= 80) {
                    $item = $cat->random();
                    if ($item && !in_array($item->id, collect($selectedItems)->pluck('id')->toArray())) {
                        $selectedItems[] = $item;
                    }
                }
            }

            if (count($selectedItems) < 2) {
                $selectedItems[] = $nasiBox->random();
                $selectedItems[] = $minuman->random();
            }

            $totalPrice = 0;
            $orderDetailsData = [];

            foreach ($selectedItems as $menu) {
                if (!$menu) continue;
                
                $qty = rand(20, 50); // Kuantitas minimal 20 ke atas
                $subtotal = $menu->price * $qty;
                $totalPrice += $subtotal;

                $orderDetailsData[] = [
                    'menu_id' => $menu->id,
                    'package_id' => null,
                    'qty' => $qty, // Menggunakan kolom 'qty'
                    'price' => $menu->price,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }

            $order = Order::create([
                'user_id' => $customer->id,
                'order_code' => $orderCode,
                'total_price' => $totalPrice,
                'status' => 'Selesai',
                'order_date' => $orderDate->format('Y-m-d'),
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            foreach ($orderDetailsData as &$detail) {
                $detail['order_id'] = $order->id;
            }
            unset($detail);

            OrderDetail::insert($orderDetailsData);
        }

        $this->command->info('Berhasil menggenerate 800 transaksi bersih untuk analisis Apriori!');
    }
}