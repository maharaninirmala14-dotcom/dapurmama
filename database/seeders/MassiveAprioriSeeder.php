<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MassiveAprioriSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan database secara total (matikan foreign key sebentar)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrderDetail::truncate();
        Order::truncate();
        // Hapus semua user ber-role customer agar data lama hilang bersih
        User::where('role', 'customer')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ambil ID dari 8 menu spesifik pilihanmu
        $targetNames = [
            'Nasi Ayam Goreng', 
            'Nasi Ayam Bakar', 
            'Samosa', 
            'Risol Mayo', 
            'Tahu Isi', 
            'Pudding', 
            'Es Teh', 
            'Vit Gelas'
        ];

        $menuIds = Menu::whereIn('name', $targetNames)->pluck('id')->toArray();

        if (count($menuIds) < 4) {
            $menuIds = Menu::limit(8)->pluck('id')->toArray();
        }

        // 3. Generate kombinasi 4 item secara matematis dari menu tersebut (C(n, 4))
        $allCombinations = [];
        $n = count($menuIds);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                for ($k = $j + 1; $k < $n; $k++) {
                    for ($l = $k + 1; $l < $n; $l++) {
                        $allCombinations[] = [$menuIds[$i], $menuIds[$j], $menuIds[$k], $menuIds[$l]];
                    }
                }
            }
        }

        shuffle($allCombinations);
        $activePackages = array_slice($allCombinations, 0, min(45, count($allCombinations)));

        // 4. Daftar nama pelanggan dari data mahasiswa Informatika
        $rawNames = [
            'Nirmala Maharani', 'Arum Sari Setia Budi', 'Aradea Pinkan Kartiningtyas', 
            'Viona Leny Anjani', 'Dila', 'Melynda', 'Shavira Azra', 'Bayu Ade Prasetiya', 
            'Yasir Galuh Adi Saputro', 'Syamsul Dahlan', 'M Reno Afrido Arwinto', 'Mikola', 
            'Hanafi', 'Zidan', 'Naufal Hanan Jati Asmara', 'Aulia', 'Domy', 'Davina Yela Gainta', 
            'Nanda Rahma Anggyta', 'Indi Najwa Alifia', 'Devytha Nur Alfi', 'Depita', 'Havid Danggo Pamungkas', 
            'Rangga Hutama Putra', 'Ilham Abiansyah', 'Oktafian Dyah Pangesti', 'Hanifah Muthi', 
            'Liling Daru Suryawati', 'Tegar Risky Nugrahanto', 'Irsyad Badruddin', 'Irul', 
            'Arami Rizki Gunawan', 'Ulfah Nur Uzlifah', 'Shena', 'Satria Tabah Raharja', 
            'Muhammad \'Izzul Islam', 'Irhash Zim Zam Dito Prayoga', 'Khoirunnisa', 'Vani', 
            'Aca', 'Dyah Putri Anggraini', 'Nuari Dwi Utama'
        ];

        $streetPool = [
            'Jl. Slamet Riyadi No.', 'Jl. Ir. Soekarno, Solo Baru', 'Jl. Adi Sucipto No.', 
            'Gonilan, Kartasura', 'Pabelan, Kartasura', 'Manahan, Banjarsari', 
            'Laweyan', 'Jebres', 'Kentingan', 'Gumpang, Kartasura', 
            'Banjarsari', 'Pasar Kliwon', 'Serengan', 'Punggawan'
        ];

        $userIds = [];
        foreach ($rawNames as $index => $name) {
            $emailSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
            // Diubah domainnya menjadi @gmail.com sesuai permintaan
            $email = $emailSlug . ($index + 101) . '@gmail.com';
            $phone = '08' . rand(11, 89) . rand(10000000, 99999999);
            $address = $streetPool[array_rand($streetPool)] . ' ' . rand(1, 200) . ', Surakarta';
            $joinDate = Carbon::now()->subDays(rand(10, 400))->setTime(rand(8, 18), rand(0, 59), rand(0, 59));

            $user = User::create([
                'name' => ucwords($name),
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => $phone,
                'address' => $address,
                'created_at' => $joinDate,
                'updated_at' => $joinDate,
            ]);
            $userIds[] = $user->id;
        }

        // 5. Generate 300 Transaksi Selesai (Dikunci tepat 300 sesuai ralat)
        $totalTransactions = 300;
        for ($i = 1; $i <= $totalTransactions; $i++) {
            $selectedUserId = $userIds[array_rand($userIds)];
            $selectedUser = User::find($selectedUserId);

            $randomDate = Carbon::now()->subDays(rand(1, 90));
            $orderCode = 'INV-' . $randomDate->format('Ymd') . '-' . $i . '-' . rand(100, 999);

            $orderId = DB::table('orders')->insertGetId([
                'user_id'          => $selectedUserId,
                'order_code'       => $orderCode,
                'order_date'       => $randomDate->toDateString(),
                'delivery_time'    => '12:00:00',
                'recipient_name'   => $selectedUser->name, 
                'phone_number'     => $selectedUser->phone,
                'shipping_address' => $selectedUser->address,
                'payment_method'   => 'Transfer Bank',
                'payment_status'   => 'paid',
                'status'           => 'Selesai',
                'total_price'      => 0, 
                'is_read'          => 1,
                'created_at'       => $randomDate,
                'updated_at'       => $randomDate,
            ]);

            $maxIndex = count($activePackages) - 1;
            $pkgIndex = min((int) floor(pow(rand(0, 1000) / 1000, 2) * count($activePackages)), $maxIndex);
            $chosenMenuIds = $activePackages[$pkgIndex];

            $totalPrice = 0;
            foreach ($chosenMenuIds as $menuId) {
                $menu = Menu::find($menuId);
                if (!$menu) continue;

                $qty = rand(20, 50);
                $subtotal = $menu->price * $qty;
                $totalPrice += $subtotal;

                DB::table('order_details')->insert([
                    'order_id'   => $orderId,
                    'menu_id'    => $menu->id,
                    'package_id' => null,
                    'qty'        => $qty,
                    'price'      => $menu->price,
                    'total'      => $subtotal,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }

            DB::table('orders')->where('id', $orderId)->update(['total_price' => $totalPrice]);
        }

        $this->command->info("Seeder sukses! Total tepat 300 transaksi berhasil dibuat, email pelanggan menggunakan domain @gmail.com, dan pola Apriori tetap stabil.");
    }
}