<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Data demo untuk halaman "Bukti Apriori" (admin).
 *
 * Dua pelanggan dengan sidik jari belanja berbeda:
 *
 *   Nirmala Maharani : 12 transaksi
 *     - 6x { Nasi Ayam Bakar, Keripik Tempe, Es Jeruk, Pudding }
 *     - 6x { Kue Sus, Bolu Kukus }
 *
 *   Nirmanto Wijaya : 12 transaksi
 *     - 7x { Nasi Ayam Goreng, Risol Mayo, Tahu Isi, Es Teh }
 *     - 5x { Samosa, Vit Gelas }
 *
 *   Dewi Lestari : 12 transaksi
 *     - 7x { Nasi Ayam Gongso, Lemper Ayam, Kacang Bawang, Es Lemon Tea }
 *     - 5x { Pastel, Klepon }
 *
 *   Fajar Nugroho : 12 transaksi
 *     - 8x { Nasi Lele Bakar, Risol Rogut, Stik Keju, Keripik Singkong }
 *     - 4x { Nagasari, Kroket Kentang }
 *
 * Yang dibuat di sini HANYA data transaksi (dataset). Frequent itemset,
 * aturan asosiasi (Support / Confidence / Lift) dan rekomendasi TIDAK
 * ditulis di sini — semuanya dihitung ulang oleh App\Services\AprioriService
 * dari transaksi di bawah ini.
 *
 * Jalankan  : php artisan db:seed --class=DemoAprioriProofSeeder
 * Idempoten : transaksi demo lama (tanda [demo-apriori]) dihapus lalu dibuat
 *             ulang mengikuti plan, sehingga hasilnya selalu sama.
 */
class DemoAprioriProofSeeder extends Seeder
{
    private const NOTES = '[demo-apriori] Transaksi demo halaman Bukti Apriori (pola belanja per pelanggan).';

    /**
     * @var array<int, array{name: string, emails: array<int, string>, plan: array<int, array{menus: array<int, string>, times: int}>}>
     */
    private array $profiles = [
        [
            'name'   => 'Nirmala Maharani',
            // email pertama dipakai bila pelanggan harus dibuat baru
            'emails' => ['nirmalamaharani101@gmail.com', 'nirmala@customer.com'],
            'plan'   => [
                ['menus' => ['Nasi Ayam Bakar', 'Keripik Tempe', 'Es Jeruk', 'Pudding'], 'times' => 6],
                ['menus' => ['Kue Sus', 'Bolu Kukus'], 'times' => 6],
            ],
        ],
        [
            'name'   => 'Nirmanto Wijaya',
            'emails' => ['nirmanto@customer.com'],
            'plan'   => [
                ['menus' => ['Nasi Ayam Goreng', 'Risol Mayo', 'Tahu Isi', 'Es Teh'], 'times' => 7],
                ['menus' => ['Samosa', 'Vit Gelas'], 'times' => 5],
            ],
        ],
        [
            'name'   => 'Dewi Lestari',
            'emails' => ['dewi.lestari@customer.com'],
            'plan'   => [
                ['menus' => ['Nasi Ayam Gongso', 'Lemper Ayam', 'Kacang Bawang', 'Es Lemon Tea'], 'times' => 7],
                ['menus' => ['Pastel', 'Klepon'], 'times' => 5],
            ],
        ],
        [
            'name'   => 'Fajar Nugroho',
            'emails' => ['fajar.nugroho@customer.com'],
            'plan'   => [
                ['menus' => ['Nasi Lele Bakar', 'Risol Rogut', 'Stik Keju', 'Keripik Singkong'], 'times' => 8],
                ['menus' => ['Nagasari', 'Kroket Kentang'], 'times' => 4],
            ],
        ],
    ];

    public function run(): void
    {
        foreach ($this->profiles as $profile) {
            $user = $this->findOrCreateUser($profile);

            if (!$user) {
                continue;
            }

            $hadDemoOrders = Order::where('user_id', $user->id)
                ->where('notes', 'like', '%[demo-apriori]%')
                ->exists();

            // Transaksi demo lama dihapus lalu dibuat ulang mengikuti plan,
            // supaya jalankan ulang seeder ini selalu menghasilkan data yang sama.
            $demoOrderIds = Order::where('user_id', $user->id)
                ->where('notes', 'like', '%[demo-apriori]%')
                ->pluck('id');
            OrderDetail::whereIn('order_id', $demoOrderIds)->delete();
            Order::whereIn('id', $demoOrderIds)->delete();

            // Baru pertama kali: riwayat lama (hasil generate random) ikut
            // dibersihkan agar pola belanja pelanggan ini mudah dibuktikan.
            if (!$hadDemoOrders) {
                $oldOrderIds = Order::where('user_id', $user->id)->pluck('id');
                if ($oldOrderIds->isNotEmpty()) {
                    OrderDetail::whereIn('order_id', $oldOrderIds)->delete();
                    Order::whereIn('id', $oldOrderIds)->delete();
                    $this->command->info("{$user->name}: menghapus {$oldOrderIds->count()} transaksi lama hasil generate.");
                }
            }

            $created = $this->seedFingerprint($user, $profile['plan']);

            $this->command->info("{$user->name}: {$created} transaksi demo (sidik jari belanja khas).");
        }

        $this->command->info('Total transaksi di database sekarang: ' . Order::count() . '.');
    }

    /**
     * @param  array{name: string, emails: array<int, string>}  $profile
     */
    private function findOrCreateUser(array $profile): ?User
    {
        $user = User::whereIn('email', $profile['emails'])->first()
            ?? User::where('name', $profile['name'])->first();

        if ($user) {
            return $user;
        }

        $email = $profile['emails'][0];
        if (User::where('email', $email)->exists()) {
            $email = 'demo+' . Str::slug($profile['name']) . '@customer.com';
        }

        $user = User::create([
            'name'     => $profile['name'],
            'email'    => $email,
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'phone'    => '0812' . str_pad((string) random_int(1, 9999999), 7, '0', STR_PAD_LEFT),
            'address'  => 'Jl. Bukti Apriori No. 1, Surakarta',
        ]);

        $this->command->info("Membuat pelanggan baru: {$user->name} ({$user->email}) - password: password.");

        return $user;
    }

    /**
     * @param  array<int, array{menus: array<int, string>, times: int}>  $plan
     */
    private function seedFingerprint(User $user, array $plan): int
    {
        $created = 0;
        $sequence = 0;

        foreach ($plan as $group) {
            $menus = Menu::whereIn('name', $group['menus'])->get()->keyBy('name');

            if ($menus->count() !== count($group['menus'])) {
                $missing = implode(', ', array_diff($group['menus'], $menus->keys()->all()));
                $this->command->error("Menu tidak ditemukan ({$missing}) - grup dilewati.");
                continue;
            }

            for ($i = 0; $i < $group['times']; $i++) {
                $orderDate = now()->subDays(max(2, 52 - ($sequence * 4)))->startOfDay();
                $sequence++;

                $total = 0;
                $details = [];

                foreach ($menus as $menu) {
                    $qty = 20; // porsi katering
                    $lineTotal = (float) $menu->price * $qty;

                    $details[] = [
                        'menu_id' => $menu->id,
                        'qty'     => $qty,
                        'price'   => $menu->price,
                        'total'   => $lineTotal,
                    ];
                    $total += $lineTotal;
                }

                $orderCode = 'ORD-DEMO-' . strtoupper(Str::random(6));
                while (Order::where('order_code', $orderCode)->exists()) {
                    $orderCode = 'ORD-DEMO-' . strtoupper(Str::random(6));
                }

                $order = Order::create([
                    'user_id'          => $user->id,
                    'recipient_name'   => $user->name,
                    'phone_number'     => $user->phone,
                    'shipping_address' => $user->address,
                    'order_code'       => $orderCode,
                    'order_date'       => $orderDate,
                    'delivery_time'    => '11:00 - 13:00',
                    'total_price'      => $total,
                    'payment_method'   => $sequence % 2 === 0 ? 'COD (Bayar di Tempat)' : 'Transfer Bank',
                    'payment_status'   => 'Lunas',
                    'status'           => 'completed',
                    'notes'            => self::NOTES,
                    'is_read'          => true,
                ]);

                foreach ($details as $detail) {
                    OrderDetail::create(array_merge($detail, ['order_id' => $order->id]));
                }

                $created++;
            }
        }

        return $created;
    }
}
