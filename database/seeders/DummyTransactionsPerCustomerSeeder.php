<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Data dummy transaksi PER PELANGGAN untuk pembuktian Apriori.
 *
 * Setiap pelanggan memiliki "sidik jari" belanja sendiri (menu yang sering
 * ia beli berulang), sehingga hasil Apriori (frequent itemset, association
 * rules, paket rekomendasi) berbeda antar pelanggan — murni karena pola data,
 * bukan hardcode nama pelanggan.
 *
 * Jalankan  : php artisan db:seed --class=DummyTransactionsPerCustomerSeeder
 * Idempoten : pelanggan yang sudah punya transaksi dummy (notes [dummy-apriori])
 *             akan dilewati, aman dijalankan berulang.
 */
class DummyTransactionsPerCustomerSeeder extends Seeder
{
    /**
     * Sidik jari belanja tiap pelanggan — masing-masing 14 order dummy
     * (digabung dengan 4 order existing = 18 transaksi per pelanggan).
     *
     * Tiap pelanggan punya DUO khas (pasangan menu yang selalu dibeli
     * bersamaan, tapi tidak pernah sendirian) sehingga lift ≥ 2, plus
     * tepat SATU paket 4-itemset unik yang berbeda antar pelanggan:
     *
     *   265 NAF'AN  : duo Agar-Agar+Vit Gelas (lift 2,25) → paket [1,3,7,8]
     *   266 RYANDRA : duo Kremesan+Nasi Ayam Goreng (lift 2,00) → paket [1,2,4,6]
     *   267 ILHAM   : duo Keripik Tempe+Samosa (lift 2,25) → paket [1,2,3,5]
     *   268 RIZKA   : duo Nasi Ayam Goreng+Vit Gelas (lift 3,00) → paket [1,5,6,8]
     *   269 WILDAN  : duo Agar-Agar+Vit Gelas (lift 3,00) → paket [1,2,7,8]
     *
     * Catatan menu: 1=Nasi Ayam Bakar, 2=Tahu Isi, 3=Keripik Tempe,
     *               4=Kremesan Ayam, 5=Samosa, 6=Nasi Ayam Goreng,
     *               7=Agar-Agar, 8=Vit Gelas
     */
    private array $plans = [
        // NAF'AN — doyan keripik tempe; Agar-Agar & Vit Gelas selalu berduaan
        265 => [
            [1, 3, 7, 8], [1, 3, 7, 8], [1, 3, 7, 8], [1, 3, 7, 8], [1, 3, 7, 8], [1, 3, 7, 8], // L4 signature ×6
            [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3], // kebiasaan tahu isi + keripik
            [1, 3, 5], [1, 3, 5],
        ],
        // RYANDRA — kremesan + nasi ayam goreng selalu bersamaan
        266 => [
            [1, 2, 4, 6], [1, 2, 4, 6], [1, 2, 4, 6], [1, 2, 4, 6], [1, 2, 4, 6], // L4 signature ×5
            [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3],
            [1, 3, 5], [1, 3, 5], [1, 3, 5], [1, 3, 5],
        ],
        // ILHAM — keripik tempe + samosa selalu bersamaan
        267 => [
            [1, 2, 3, 5], [1, 2, 3, 5], [1, 2, 3, 5], [1, 2, 3, 5], [1, 2, 3, 5], // L4 signature ×5
            [1, 2, 6], [1, 2, 6], [1, 2, 6], [1, 2, 6], [1, 2, 6],
            [1, 2, 4], [1, 2, 4], [1, 2, 4], [1, 2, 4],
        ],
        // RIZKA — nasi ayam goreng + vit gelas selalu bersamaan
        268 => [
            [1, 5, 6, 8], [1, 5, 6, 8], [1, 5, 6, 8], [1, 5, 6, 8], [1, 5, 6, 8], // L4 signature ×5
            [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3],
            [1, 3, 4], [1, 3, 4], [1, 3, 4], [1, 3, 4],
        ],
        // WILDAN — tahu isi + (agar-agar & vit gelas) selalu bersamaan
        269 => [
            [1, 2, 7, 8], [1, 2, 7, 8], [1, 2, 7, 8], [1, 2, 7, 8], [1, 2, 7, 8], // L4 signature ×5
            [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3], [1, 2, 3],
            [1, 4, 6], [1, 4, 6], [1, 4, 6], [1, 4, 6],
        ],
    ];

    public function run(): void
    {
        $menus = Menu::whereIn('id', range(1, 8))->get()->keyBy('id');

        if ($menus->count() < 8) {
            $this->command->error('Menu id 1-8 belum lengkap. Seed menu dulu sebelum menjalankan seeder ini.');
            return;
        }

        foreach ($this->plans as $userId => $plan) {
            $user = User::find($userId);

            if (!$user) {
                $this->command->warn("User {$userId} tidak dilewati (tidak ditemukan).");
                continue;
            }

            $alreadySeeded = Order::where('user_id', $userId)
                ->where('notes', 'like', '%[dummy-apriori]%')
                ->exists();

            if ($alreadySeeded) {
                $this->command->info("User {$userId} ({$user->email}) sudah punya transaksi dummy — dilewati.");
                continue;
            }

            // Urutan order diacak deterministik supaya daftar riwayat tidak terlihat berulang
            mt_srand($userId * 7 + 2026);
            shuffle($plan);

            $totalCreated = 0;
            $totalItems = 0;

            foreach ($plan as $i => $menuIds) {
                // Sebar tanggal order selama ~60 hari terakhir, unik per order
                $daysBack = 55 - ($i * 3) - ($userId % 5);
                $orderDate = now()->subDays(max(1, $daysBack))->startOfDay();

                $subtotal = 0;
                $details = [];

                foreach ($menuIds as $menuId) {
                    $menu = $menus[$menuId];
                    // Menu kecil/minuman dipesan 2 pcs, menu utama 1 porsi
                    $qty = in_array($menuId, [5, 8]) ? 2 : 1;
                    $lineTotal = (float) $menu->price * $qty;

                    $details[] = [
                        'menu_id' => $menuId,
                        'qty'     => $qty,
                        'price'   => $menu->price,
                        'total'   => $lineTotal,
                    ];
                    $subtotal += $lineTotal;
                }

                $orderCode = 'ORD-DMT-' . strtoupper(Str::random(6));
                while (Order::where('order_code', $orderCode)->exists()) {
                    $orderCode = 'ORD-DMT-' . strtoupper(Str::random(6));
                }

                $order = Order::create([
                    'user_id'         => $userId,
                    'recipient_name'  => $user->name,
                    'phone_number'    => '0812' . str_pad((string) $userId, 7, '0', STR_PAD_LEFT),
                    'shipping_address'=> 'Jl. Contoh Catering No. ' . ($i + 1) . ', Kota Demo',
                    'order_code'      => $orderCode,
                    'order_date'      => $orderDate,
                    'delivery_time'   => '11:00 - 13:00',
                    'total_price'     => $subtotal,
                    'payment_method'  => $i % 2 === 0 ? 'COD (Bayar di Tempat)' : 'Transfer Bank',
                    'payment_status'  => 'Lunas',
                    'status'          => 'completed',
                    'notes'           => '[dummy-apriori] Data dummy untuk analisis Apriori — pola belanja pelanggan.',
                    'is_read'         => true,
                ]);

                foreach ($details as $detail) {
                    OrderDetail::create(array_merge($detail, ['order_id' => $order->id]));
                }

                $totalCreated++;
                $totalItems += count($details);
            }

            $this->command->info(
                "User {$userId} ({$user->email}): +{$totalCreated} order dummy, {$totalItems} baris order_details."
            );
        }

        $globalOrders = Order::count();
        $this->command->info("Total transaksi di database sekarang: {$globalOrders}.");
    }
}
