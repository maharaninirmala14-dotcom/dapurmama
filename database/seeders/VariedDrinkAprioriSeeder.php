<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * VariedDrinkAprioriSeeder — Menghasilkan Transaksi Historis dengan Variasi Seluruh Minuman Database
 * untuk Memastikan Algoritma Apriori Menghasilkan Pola Asosiasi Makanan & Beragam Minuman.
 */
class VariedDrinkAprioriSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan seluruh minuman aktif (tidak terhapus)
        Menu::withTrashed()->where('category_id', 4)->restore();

        // 2. Ambil seluruh pelanggan dan menu
        $customers = User::where('role', 'customer')->get();
        if ($customers->isEmpty()) {
            $this->command->warn('Pelanggan belum ada.');
            return;
        }

        $allDrinks = Menu::where('category_id', 4)->get();
        $allFoods  = Menu::where('category_id', 1)->get();
        $allSnacks = Menu::whereIn('category_id', [2, 3])->get();

        if ($allDrinks->isEmpty() || $allFoods->isEmpty()) {
            $this->command->warn('Menu makanan atau minuman kosong.');
            return;
        }

        $menusByName = Menu::all()->keyBy('name');

        // Pola belanja variatif makanan dengan berbagai jenis minuman
        $pairings = [
            'Es Teh'        => ['Nasi Ayam Goreng', 'Nasi Ayam Bakar', 'Nasi Goreng', 'Mie Goreng', 'Nasi Ayam Kremes'],
            'Teh Hangat'    => ['Nasi Ayam Goreng', 'Nasi Ayam Bakar', 'Nasi Goreng', 'Mie Goreng', 'Nasi Pecel Telur'],
            'Es Jeruk'      => ['Nasi Ayam Bakar', 'Nasi Ayam Goreng', 'Mie Goreng', 'Nasi Goreng', 'Nasi Nila Bakar'],
            'Jeruk Hangat'  => ['Nasi Ayam Bakar', 'Nasi Lele Bakar', 'Nasi Nila Bakar', 'Nasi Ayam Goreng'],
            'Es Sirup'      => ['Nasi Ayam Goreng', 'Nasi Nila Goreng', 'Nasi Bancakan', 'Nasi Ayam Kremes'],
            'Es Cokelat'    => ['Nasi Ayam Goreng', 'Nasi Ayam Gongso', 'Nasi Kuning Bento', 'Nasi Ayam Kentucky'],
            'Es Lemon Tea'  => ['Nasi Ayam Bakar', 'Nasi Ayam Goreng', 'Mie Goreng', 'Nasi Ayam Kentucky'],
            'Es Milo'       => ['Nasi Ayam Gongso', 'Nasi Ayam Kentucky', 'Nasi Goreng', 'Nasi Kuning Bento'],
            'Es Susu'       => ['Nasi Kuning Bento', 'Nasi Ayam Goreng', 'Nasi Ayam Bakar', 'Nasi Goreng'],
            'Es Cappuccino' => ['Nasi Ayam Kentucky', 'Nasi Ayam Gongso', 'Nasi Goreng', 'Mie Goreng'],
            'Vit Gelas'     => ['Nasi Ayam Goreng', 'Nasi Ayam Bakar', 'Nasi Pecel Telur', 'Nasi Bandeng', 'Nasi Telur'],
        ];

        $created = 0;
        foreach ($pairings as $drinkName => $foods) {
            $drink = $menusByName->get($drinkName);
            if (!$drink) continue;

            foreach ($foods as $foodName) {
                $food = $menusByName->get($foodName);
                if (!$food) continue;

                for ($k = 0; $k < 3; $k++) {
                    $cust = $customers->random();
                    $orderDate = Carbon::now()->subDays(rand(2, 60));
                    $snack = $allSnacks->random();

                    $order = Order::create([
                        'user_id'          => $cust->id,
                        'order_code'       => 'ORD-' . strtoupper(Str::random(8)),
                        'order_date'       => $orderDate->toDateString(),
                        'delivery_time'    => '12:00:00',
                        'recipient_name'   => $cust->name,
                        'phone_number'     => $cust->phone ?? '081234567890',
                        'shipping_address' => $cust->address ?? 'Surakarta',
                        'payment_method'   => 'Transfer Bank',
                        'payment_status'   => 'paid',
                        'status'           => 'Selesai',
                        'total_price'      => 0,
                        'is_read'          => 1,
                        'notes'            => '[transaksi-menu-variatif]',
                        'created_at'       => $orderDate,
                        'updated_at'       => $orderDate,
                    ]);

                    $items = collect([$food, $drink, $snack]);
                    $totalPrice = 0;

                    foreach ($items->unique('id') as $item) {
                        $qty = rand(5, 20);
                        $subtotal = (float)$item->price * $qty;
                        $totalPrice += $subtotal;

                        OrderDetail::create([
                            'order_id'   => $order->id,
                            'menu_id'    => $item->id,
                            'package_id' => null,
                            'qty'        => $qty,
                            'price'      => $item->price,
                            'total'      => $subtotal,
                            'created_at' => $orderDate,
                            'updated_at' => $orderDate,
                        ]);
                    }

                    $order->update(['total_price' => $totalPrice]);
                    $created++;
                }
            }
        }

        $this->command->info("Berhasil membuat {$created} transaksi historis dengan variasi seluruh minuman database.");
    }
}
