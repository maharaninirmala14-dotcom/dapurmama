<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AprioriService;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AprioriServiceTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomer(string $name): User
    {
        return User::create([
            'name' => $name,
            'email' => strtolower($name) . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
    }

    /** @return array{0: Category, 1: Menu, 2: Menu, 3: Menu, 4: Menu} */
    private function createMenus(): array
    {
        $category = Category::create(['name' => 'Nasi Box', 'slug' => 'nasi-box']);

        $menuA = Menu::create(['category_id' => $category->id, 'name' => 'Ayam Goreng', 'price' => 20000]);
        $menuB = Menu::create(['category_id' => $category->id, 'name' => 'Es Teh', 'price' => 5000]);
        $menuC = Menu::create(['category_id' => $category->id, 'name' => 'Sayur Asem', 'price' => 8000]);
        $menuD = Menu::create(['category_id' => $category->id, 'name' => 'Sambal', 'price' => 3000]);

        return [$category, $menuA, $menuB, $menuC, $menuD];
    }

    /**
     * Buat $count order untuk $user, masing-masing berisi item yang sama.
     *
     * @param  Menu[]  $items
     */
    private function createOrders(User $user, array $items, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => 'ORD-' . $user->id . '-' . $i . '-' . uniqid(),
                'order_date' => now()->format('Y-m-d'),
                'total_price' => collect($items)->sum('price'),
                'status' => 'Selesai',
            ]);

            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->id,
                    'qty' => 1,
                    'price' => $item->price,
                    'total' => $item->price,
                ]);
            }
        }
    }

    /**
     * Data uji (5 transaksi):
     *  T1: Ayam Goreng, Es Teh, Sayur Asem
     *  T2: Ayam Goreng, Es Teh
     *  T3: Ayam Goreng, Es Teh, Sayur Asem
     *  T4: Ayam Goreng, Sayur Asem
     *  T5: Ayam Goreng, Es Teh, Sayur Asem
     */
    public function test_apriori_generates_rules_and_recommendations(): void
    {
        $customer = $this->createCustomer('Budi');
        [, $menuA, $menuB, $menuC] = $this->createMenus();

        $transactionsData = [
            [$menuA, $menuB, $menuC],
            [$menuA, $menuB],
            [$menuA, $menuB, $menuC],
            [$menuA, $menuC],
            [$menuA, $menuB, $menuC],
        ];

        foreach ($transactionsData as $idx => $items) {
            $order = Order::create([
                'user_id' => $customer->id,
                'order_code' => 'ORD-TEST-' . $idx,
                'order_date' => now()->format('Y-m-d'),
                'total_price' => collect($items)->sum('price'),
                'status' => 'Selesai',
            ]);

            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->id,
                    'qty' => 1,
                    'price' => $item->price,
                    'total' => $item->price,
                ]);
            }
        }

        $service = new AprioriService();
        $service->setParameters(0.5, 0.6); // Min Support: 50%, Min Confidence: 60%
        $result = $service->run();

        $this->assertEquals(5, $result['total_transactions']);
        $this->assertFalse($result['personalized'], 'Tanpa userId, analisis harus global');
        $this->assertNotEmpty($result['rules']);

        // Support/Confidence/Lift lolos ambang parameter (support >= 0.5, confidence >= 0.6)
        $firstRule = $result['rules'][0];
        $this->assertGreaterThanOrEqual(0.5, $firstRule['support']);
        $this->assertGreaterThanOrEqual(0.6, $firstRule['confidence']);
        $this->assertLessThanOrEqual(1.0, $firstRule['confidence']);
        $this->assertGreaterThan(0, $firstRule['lift']);

        // getRecommendations mengembalikan collection menu (bukan fallback acak)
        $recs = $service->getRecommendations(3);
        $this->assertTrue($recs->isNotEmpty());
        $this->assertLessThanOrEqual(3, $recs->count());
    }

    public function test_cart_based_recommendation_suggests_next_menu(): void
    {
        $customer = $this->createCustomer('Budi');
        [, $menuA, $menuB, $menuC] = $this->createMenus();

        // Pola: Ayam Goreng sering dibeli bersama Es Teh dan Sayur Asem
        $this->createOrders($customer, [$menuA, $menuB, $menuC], 3);
        $this->createOrders($customer, [$menuA, $menuB], 2);

        $service = new AprioriService();
        $service->setParameters(0.5, 0.6);

        // Jika cart berisi Ayam Goreng + Es Teh, harus merekomendasikan Sayur Asem
        $rec = $service->getRecommendationsForCart([$menuA->id, $menuB->id], $customer->id);

        $recIds = $rec['recommended_menus']->pluck('id')->toArray();
        $this->assertContains($menuC->id, $recIds, 'Sayur Asem harus direkomendasikan sebagai consequent');
        $this->assertNotEmpty($rec['explanation_rules'], 'Rules penjelas harus tersedia untuk customer');
        $this->assertNotEmpty($rec['recommended_packages']);
        $this->assertTrue($rec['personalized'], 'Analisis harus memakai riwayat pelanggan yang login');

        // Menu yang sudah ada di cart tidak boleh direkomendasikan ulang
        $this->assertNotContains($menuA->id, $recIds);
        $this->assertNotContains($menuB->id, $recIds);

        // Metrik wajib ada di rules penjelas
        $rule = $rec['explanation_rules'][0];
        $this->assertArrayHasKey('support', $rule);
        $this->assertArrayHasKey('confidence', $rule);
        $this->assertArrayHasKey('lift', $rule);
    }

    public function test_recommendations_differ_between_customers_with_different_histories(): void
    {
        [, $menuA, $menuB, $menuC, $menuD] = $this->createMenus();

        $budi = $this->createCustomer('Budi');
        $siti = $this->createCustomer('Siti');

        // Budi selalu beli Ayam Goreng + Es Teh (5 transaksi)
        $this->createOrders($budi, [$menuA, $menuB], 5);
        // Siti selalu beli Ayam Goreng + Sayur Asem (5 transaksi)
        $this->createOrders($siti, [$menuA, $menuC], 5);

        $service = new AprioriService();
        $service->setParameters(0.4, 0.5);

        $resultBudi = $service->run($budi->id);
        $resultSiti = $service->run($siti->id);

        // Keduanya personal (pakai transaksi masing-masing)
        $this->assertTrue($resultBudi['personalized']);
        $this->assertTrue($resultSiti['personalized']);
        $this->assertEquals(5, $resultBudi['total_transactions']);
        $this->assertEquals(5, $resultSiti['total_transactions']);

        // Rules Budi hanya memuat menu A & B (Es Teh tidak pernah dibeli Siti)
        $budiItems = collect($resultBudi['rules'])
            ->flatMap(fn ($r) => array_merge($r['antecedent'], $r['consequent']))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        $this->assertContains($menuA->id, $budiItems);
        $this->assertContains($menuB->id, $budiItems);
        $this->assertNotContains($menuC->id, $budiItems, 'Budi tidak pernah membeli Sayur Asem');

        // Rules Siti hanya memuat menu A & C (Es Teh tidak pernah dibeli Siti)
        $sitiItems = collect($resultSiti['rules'])
            ->flatMap(fn ($r) => array_merge($r['antecedent'], $r['consequent']))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        $this->assertContains($menuC->id, $sitiItems);
        $this->assertNotContains($menuB->id, $sitiItems, 'Siti tidak pernah membeli Es Teh');

        // Rekomendasi menu pun berbeda
        $recBudi = $service->getRecommendations(3, $budi->id)->pluck('id')->toArray();
        $recSiti = $service->getRecommendations(3, $siti->id)->pluck('id')->toArray();
        $this->assertNotEquals($recBudi, $recSiti, 'Pelanggan dengan riwayat berbeda harus mendapat rekomendasi berbeda');
        $this->assertContains($menuB->id, $recBudi);
        $this->assertNotContains($menuB->id, $recSiti);
    }

    public function test_falls_back_to_global_when_customer_has_no_history(): void
    {
        [, $menuA, $menuB, $menuC] = $this->createMenus();

        $existing = $this->createCustomer('Budi');
        $this->createOrders($existing, [$menuA, $menuB, $menuC], 3);

        $newbie = $this->createCustomer('PelangganBaru'); // belum punya transaksi

        $service = new AprioriService();
        $service->setParameters(0.5, 0.6);

        $result = $service->run($newbie->id);

        $this->assertFalse($result['personalized'], 'Harus fallback ke data global');
        $this->assertEquals(3, $result['total_transactions'], 'Fallback memakai seluruh transaksi');
        $this->assertNotEmpty($result['rules'], 'Pelanggan baru tetap mendapat rekomendasi dari data global');
    }
}
