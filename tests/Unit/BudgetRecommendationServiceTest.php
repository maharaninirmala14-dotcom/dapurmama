<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BudgetRecommendationService;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BudgetRecommendationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BudgetRecommendationService();

        // Siapkan kategori
        $catFood  = Category::create(['name' => 'Nasi Box', 'slug' => 'nasi-box']);
        $catDrink = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        $catSnack = Category::create(['name' => 'Snack Basah', 'slug' => 'snack-basah']);

        // Siapkan menu makanan
        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Ayam Bakar', 'price' => 20000, 'description' => 'Ayam bakar lezat']);
        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Ayam Goreng', 'price' => 15000, 'description' => 'Ayam goreng renyah']);
        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Telur', 'price' => 10000, 'description' => 'Nasi telur hemat']);

        // Siapkan menu minuman
        Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Jeruk', 'price' => 5000, 'description' => 'Es jeruk manis']);
        Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Teh', 'price' => 3000, 'description' => 'Es teh segar']);

        // Siapkan menu snack
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Kue Sus', 'price' => 4000, 'description' => 'Kue sus vla']);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Risol Mayo', 'price' => 2500, 'description' => 'Risol mayo gurih']);
    }

    public function test_group_of_category_correctly_categorizes(): void
    {
        $this->assertEquals('makanan', $this->service->groupOfCategory('Nasi Box', 'nasi-box'));
        $this->assertEquals('makanan', $this->service->groupOfCategory('Tumpeng', 'tumpeng'));
        $this->assertEquals('minuman', $this->service->groupOfCategory('Minuman', 'minuman'));
        $this->assertEquals('snack', $this->service->groupOfCategory('Snack Basah', 'snack-basah'));
        $this->assertEquals('snack', $this->service->groupOfCategory('Snack Kering', 'snack-kering'));
    }

    public function test_category_recommendations_are_sorted_and_within_budget(): void
    {
        $budget = 18000.0;
        $categories = $this->service->getCategoryRecommendations($budget);

        $this->assertArrayHasKey('makanan', $categories);
        $this->assertArrayHasKey('minuman', $categories);
        $this->assertArrayHasKey('snack', $categories);

        // Nasi Ayam Bakar (20000) tidak boleh masuk karena > 18000
        $foodNames = $categories['makanan']['items']->pluck('name')->toArray();
        $this->assertNotContains('Nasi Ayam Bakar', $foodNames);
        $this->assertContains('Nasi Ayam Goreng', $foodNames);
        $this->assertContains('Nasi Telur', $foodNames);

        foreach (['makanan', 'minuman', 'snack'] as $groupKey) {
            $items = $categories[$groupKey]['items'];
            $previousPrice = PHP_FLOAT_MAX;

            foreach ($items as $item) {
                // Harga tidak boleh melebihi budget
                $this->assertLessThanOrEqual($budget, $item['price']);

                // Sisa budget harus sesuai: budget - price
                $this->assertEquals($budget - $item['price'], $item['remaining_budget']);

                // Urutan harus menurun (tertinggi ke terendah)
                $this->assertLessThanOrEqual($previousPrice, $item['price']);
                $previousPrice = $item['price'];
            }
        }
    }

    public function test_package_recommendations_stay_within_budget_and_have_best_label(): void
    {
        $budget = 25000.0;
        $packages = $this->service->getPackageRecommendations($budget);

        $this->assertNotEmpty($packages);

        // Paket pertama harus Rekomendasi Terbaik
        $this->assertTrue($packages[0]['is_best']);
        $this->assertEquals('Rekomendasi Terbaik', $packages[0]['label']);

        $previousSawScore = 999.0;

        foreach ($packages as $pkg) {
            $this->assertNotNull($pkg['makanan']);
            $this->assertNotNull($pkg['minuman']);
            $this->assertNotNull($pkg['snack']);

            $total = (float) $pkg['makanan']->price + (float) $pkg['minuman']->price + (float) $pkg['snack']->price;

            // Total tidak boleh melebihi budget
            $this->assertLessThanOrEqual($budget, $pkg['total_price']);
            $this->assertEquals($total, $pkg['total_price']);
            $this->assertEquals($budget - $total, $pkg['remaining_budget']);

            // Urutan prioritas rekomendasi harus berdasarkan nilai preferensi SAW
            $this->assertArrayHasKey('saw_score', $pkg);
            $this->assertLessThanOrEqual($previousSawScore + 0.001, $pkg['saw_score']);
            $previousSawScore = $pkg['saw_score'];
        }
    }

    public function test_recommend_returns_complete_structure(): void
    {
        $result = $this->service->recommend(28000.0);

        $this->assertEquals(28000.0, $result['budget']);
        $this->assertTrue($result['has_result']);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('packages', $result);
        $this->assertArrayHasKey('best_package', $result);
        $this->assertArrayHasKey('weights', $result);
        $this->assertTrue($result['has_packages']);
    }

    public function test_saw_preference_dynamically_changes_ranking(): void
    {
        $budget = 30000.0;

        // Cari rekomendasi dengan preferensi ayam vs hemat
        $packagesAyam = $this->service->getPackageRecommendations($budget, 10, 'ayam');
        $packagesHemat = $this->service->getPackageRecommendations($budget, 10, 'hemat');

        $this->assertNotEmpty($packagesAyam);
        $this->assertNotEmpty($packagesHemat);

        // Rekomendasi pertama untuk preferensi 'ayam' harus menu ayam
        $this->assertStringContainsString('Ayam', $packagesAyam[0]['makanan']->name);

        // Rekomendasi pertama untuk preferensi 'hemat' harus menu paling ekonomis (Nasi Telur)
        $this->assertEquals('Nasi Telur', $packagesHemat[0]['makanan']->name);
        $this->assertLessThan($packagesAyam[0]['total_price'], $packagesHemat[0]['total_price']);
    }

    public function test_apriori_package_recommendations_computes_support_confidence_lift_when_transactions_exist(): void
    {
        $user = \App\Models\User::create([
            'name'     => 'Customer Test',
            'email'    => 'customertest@test.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $food = Menu::where('name', 'Nasi Ayam Bakar')->first();
        $drink = Menu::where('name', 'Es Teh')->first();
        $snack = Menu::where('name', 'Risol Mayo')->first();

        // Buat 5 transaksi historis: 4 diantaranya membeli Ayam Bakar + Es Teh + Risol Mayo
        for ($i = 1; $i <= 5; $i++) {
            $order = \App\Models\Order::create([
                'user_id'        => $user->id,
                'order_code'     => 'TX-TEST-' . $i,
                'order_date'     => now(),
                'status'         => 'Selesai',
                'recipient_name' => 'Tester ' . $i,
                'total_price'    => 25500,
            ]);

            \App\Models\OrderDetail::create([
                'order_id' => $order->id,
                'menu_id'  => $food->id,
                'qty'      => 1,
                'price'    => $food->price,
                'total'    => $food->price,
            ]);

            \App\Models\OrderDetail::create([
                'order_id' => $order->id,
                'menu_id'  => $drink->id,
                'qty'      => 1,
                'price'    => $drink->price,
                'total'    => $drink->price,
            ]);

            if ($i <= 4) {
                \App\Models\OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id'  => $snack->id,
                    'qty'      => 1,
                    'price'    => $snack->price,
                    'total'    => $snack->price,
                ]);
            }
        }

        $packages = $this->service->getAprioriPackageRecommendations(30000.0, 10, $food->id, true);

        $this->assertNotEmpty($packages);
        $firstPkg = $packages[0];

        $this->assertTrue($firstPkg['is_apriori_match']);
        $this->assertEquals($food->id, $firstPkg['makanan']->id);
        $this->assertEquals($drink->id, $firstPkg['minuman']->id);

        // Metrik Apriori harus ada
        $this->assertNotNull($firstPkg['support']);
        $this->assertNotNull($firstPkg['confidence']);
        $this->assertNotNull($firstPkg['lift']);
        $this->assertGreaterThan(0, $firstPkg['confidence']);
        $this->assertGreaterThan(0, $firstPkg['support']);
    }

    public function test_apriori_package_filtering_exceeding_budget(): void
    {
        // Budget Rp 18.000 cukup untuk Nasi Telur (10.000) + Es Teh (3.000) + Risol Mayo (2.500) = 15.500
        // tetapi tidak cukup untuk Nasi Ayam Bakar (20.000)
        $packages = $this->service->getAprioriPackageRecommendations(18000.0, 10, null, true);

        $this->assertNotEmpty($packages);

        // Seluruh paket yang lolos filter tidak boleh melebihi budget Rp 18.000
        foreach ($packages as $pkg) {
            $this->assertLessThanOrEqual(18000.0, $pkg['total_price']);
            $this->assertNotEquals('Nasi Ayam Bakar', $pkg['makanan']->name);
        }
    }

    public function test_packages_diversify_drinks_across_recommendations(): void
    {
        // Siapkan menu tambahan minuman
        $catDrink = Category::where('slug', 'minuman')->first();
        $catFood  = Category::where('slug', 'nasi-box')->first();

        $dLemon = Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Lemon Tea', 'price' => 6000]);
        $dMilo  = Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Milo', 'price' => 7000]);
        $dSirup = Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Sirup', 'price' => 4000]);

        $user = \App\Models\User::create([
            'name'     => 'Customer Diversify',
            'email'    => 'diversify@test.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $foodA = Menu::where('name', 'Nasi Ayam Goreng')->first();
        $dTeh  = Menu::where('name', 'Es Teh')->first();
        $dJeruk = Menu::where('name', 'Es Jeruk')->first();

        // Buat transaksi dengan variasi minuman berbeda
        $pairs = [
            [$foodA, $dTeh],
            [$foodA, $dJeruk],
            [$foodA, $dLemon],
            [$foodA, $dMilo],
            [$foodA, $dSirup],
        ];

        foreach ($pairs as $idx => [$f, $d]) {
            for ($k = 0; $k < 3; $k++) {
                $order = \App\Models\Order::create([
                    'user_id'     => $user->id,
                    'order_code'  => 'TX-DIV-' . $idx . '-' . $k,
                    'order_date'  => now(),
                    'status'      => 'Selesai',
                    'total_price' => $f->price + $d->price,
                ]);
                \App\Models\OrderDetail::create(['order_id' => $order->id, 'menu_id' => $f->id, 'qty' => 1, 'price' => $f->price, 'total' => $f->price]);
                \App\Models\OrderDetail::create(['order_id' => $order->id, 'menu_id' => $d->id, 'qty' => 1, 'price' => $d->price, 'total' => $d->price]);
            }
        }

        $res = $this->service->recommend(budget: 35000, pax: 1, limit: 5, selectedMenuOrPreference: $foodA->id, includeSnack: false);
        $packages = $res['packages'];

        $this->assertNotEmpty($packages);

        // Kumpulkan jenis minuman yang muncul di seluruh paket
        $drinkIds = array_map(fn($p) => $p['minuman']->id, $packages);
        $uniqueDrinkCount = count(array_unique($drinkIds));

        // Pilihan minuman tidak boleh hanya 1 atau 2 jenis, harus bervariasi
        $this->assertGreaterThanOrEqual(3, $uniqueDrinkCount, "Rekomendasi minuman harus bervariasi dan tidak terkunci hanya pada 2 minuman.");

        // Paket 1 dan Paket 2 tidak boleh memiliki minuman yang sama
        if (count($packages) >= 2) {
            $this->assertNotEquals($packages[0]['minuman']->id, $packages[1]['minuman']->id);
        }
    }

    public function test_fallback_mode_provides_varied_drinks_without_fake_rules(): void
    {
        $catFood = Category::where('slug', 'nasi-box')->first();
        $unseenFood = Menu::create([
            'category_id' => $catFood->id,
            'name'        => 'Menu Baru Tanpa Transaksi',
            'price'       => 15000,
        ]);

        $res = $this->service->recommend(budget: 25000, pax: 1, limit: 4, selectedMenuOrPreference: $unseenFood->id, includeSnack: false);
        $packages = $res['packages'];

        $this->assertNotEmpty($packages);

        foreach ($packages as $pkg) {
            $this->assertFalse($pkg['is_apriori_match']);
            $this->assertNull($pkg['confidence']);
            $this->assertNull($pkg['support']);
            $this->assertNull($pkg['lift']);
            $this->assertStringContainsString('Pola asosiasi Apriori belum ditemukan', $pkg['rule_description']);
        }

        // Minuman tetap bervariasi pada fallback
        $drinkNames = array_map(fn($p) => $p['minuman']->name, $packages);
        $this->assertGreaterThan(1, count(array_unique($drinkNames)));
    }

    public function test_packages_diversify_snacks_across_recommendations(): void
    {
        $catSnack = Category::where('slug', 'snack-basah')->first();
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Lemper Ayam Spesial', 'price' => 3000]);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Pastel Sayur Gurih', 'price' => 2500]);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Kroket Kentang Renyah', 'price' => 2000]);

        $res = $this->service->recommend(budget: 35000, pax: 1, limit: 5, selectedMenuOrPreference: null, includeSnack: true);
        $packages = $res['packages'];

        $this->assertNotEmpty($packages);

        $snackIds = array_filter(array_map(fn($p) => $p['snack']?->id, $packages));
        $uniqueSnackCount = count(array_unique($snackIds));

        // Snack antarpaket harus bervariasi dan tidak terkunci hanya pada satu jenis snack
        $this->assertGreaterThanOrEqual(2, $uniqueSnackCount, "Pilihan snack antarpaket harus bervariasi.");
    }
}

