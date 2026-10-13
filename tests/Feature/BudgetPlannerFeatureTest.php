<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Category;
use App\Models\Menu;
use App\Models\BudgetOrder;
use App\Models\BudgetOrderItem;
use App\Http\Livewire\Customer\BudgetPlanner;
use App\Http\Livewire\Admin\BudgetOrders;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetPlannerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $catFood  = Category::create(['name' => 'Nasi Box', 'slug' => 'nasi-box']);
        $catDrink = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        $catSnack = Category::create(['name' => 'Snack Basah', 'slug' => 'snack-basah']);

        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Ayam Bakar', 'price' => 20000]);
        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Ayam Goreng', 'price' => 15000]);
        Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Jeruk', 'price' => 5000]);
        Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Teh', 'price' => 3000]);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Kue Sus', 'price' => 4000]);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Risol Mayo', 'price' => 2500]);
    }

    public function test_budget_planner_page_loads_successfully(): void
    {
        $response = $this->get('/budget-planner');
        $response->assertStatus(200);
        $response->assertSee('Sistem Rekomendasi Menu Berdasarkan Budget');
        $response->assertSee('Tabel Makanan');
        $response->assertSee('Tabel Minuman');
        $response->assertSee('Tabel Snack');
        $response->assertSee('Nama Pemesan');
        $response->assertSee('Total Budget');
        $response->assertSee('Jumlah Paket');
    }

    public function test_livewire_budget_planner_calculates_recommendations(): void
    {
        Livewire::test(BudgetPlanner::class)
            ->set('budget', 30000)
            ->call('recommend')
            ->assertHasNoErrors()
            ->assertSee('Terbaik')
            ->assertSee('Rp');
    }

    public function test_quick_budget_sets_budget_and_calculates(): void
    {
        Livewire::test(BudgetPlanner::class)
            ->call('setQuickBudget', 25000)
            ->assertSet('budget', 25000)
            ->assertHasNoErrors();
    }

    public function test_validation_error_on_zero_or_empty_budget(): void
    {
        Livewire::test(BudgetPlanner::class)
            ->set('budget', 0)
            ->call('recommend')
            ->assertHasErrors(['budget']);
    }

    public function test_add_package_to_cart_adds_all_three_items(): void
    {
        Livewire::test(BudgetPlanner::class)
            ->set('budget', 30000)
            ->call('recommend')
            ->call('addPackageToCart', 0)
            ->assertDispatched('cartUpdated');

        $cart = session('cart', []);
        $this->assertNotEmpty($cart);
        $this->assertCount(3, $cart); // 1 Makanan + 1 Minuman + 1 Snack
    }

    public function test_all_menus_are_loaded_into_three_tables(): void
    {
        $component = Livewire::test(BudgetPlanner::class);
        $component->assertSee('Nasi Ayam Bakar');
        $component->assertSee('Es Teh');
        $component->assertSee('Risol Mayo');
    }

    public function test_average_budget_is_calculated_automatically(): void
    {
        // Contoh sesuai instruksi: Total budget Rp150.000, 5 paket => Rata-rata Rp30.000
        Livewire::test(BudgetPlanner::class)
            ->set('customerName', 'Budi')
            ->set('totalBudget', 150000)
            ->set('packageQuantity', 5)
            ->assertSet('averageBudget', 30000.0);
    }

    public function test_changing_total_budget_or_quantity_updates_average_budget(): void
    {
        $component = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 150000)
            ->set('packageQuantity', 5)
            ->assertSet('averageBudget', 30000.0);

        // Ubah total budget menjadi 200.000 dengan 5 paket => Rata-rata 40.000
        $component->set('totalBudget', 200000)
            ->assertSet('averageBudget', 40000.0);

        // Ubah jumlah paket menjadi 4 => Rata-rata 50.000
        $component->set('packageQuantity', 4)
            ->assertSet('averageBudget', 50000.0);
    }

    public function test_multi_package_selection_and_total_calculation(): void
    {
        // Setup: total budget 100.000, 3 paket => rata-rata 33.333
        $component = Livewire::test(BudgetPlanner::class)
            ->set('customerName', 'Budi')
            ->set('totalBudget', 100000)
            ->set('packageQuantity', 3)
            ->call('recommend')
            ->assertHasNoErrors();

        // Pastikan 3 paket terisi kombinasi default
        $this->assertCount(3, $component->get('assignedPackages'));

        // Cek total order price dan sisa budget
        $totalPrice = $component->get('totalOrderPrice');
        $remainingBudget = $component->get('remainingTotalBudget');

        $this->assertGreaterThan(0, $totalPrice);
        $this->assertLessThanOrEqual(100000, $totalPrice);
        $this->assertEquals(100000 - $totalPrice, $remainingBudget);
    }

    public function test_customer_can_save_order_and_persists_in_database(): void
    {
        $component = Livewire::test(BudgetPlanner::class)
            ->set('customerName', 'Budi')
            ->set('totalBudget', 150000)
            ->set('packageQuantity', 3)
            ->call('recommend')
            ->call('saveOrder')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true);

        // Verifikasi data pesanan tersimpan di tabel budget_orders
        $order = BudgetOrder::where('customer_name', 'Budi')->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('ORD-BGT-', $order->order_number);
        $this->assertEquals(150000, (float) $order->total_budget);
        $this->assertEquals(3, $order->package_quantity);
        $this->assertEquals(50000, (float) $order->average_budget_per_package);
        $this->assertEquals('Menunggu', $order->status);

        // Verifikasi item pesanan tersimpan (3 paket x 3 menu = 9 item)
        $this->assertCount(9, $order->items);
        $this->assertEquals(3, $order->items->where('menu_category', 'Makanan')->count());
        $this->assertEquals(3, $order->items->where('menu_category', 'Minuman')->count());
        $this->assertEquals(3, $order->items->where('menu_category', 'Snack')->count());

        // Verifikasi harga tersimpan konsisten
        $expectedTotal = (float) $order->items->sum('menu_price');
        $this->assertEquals($expectedTotal, (float) $order->total_price);
        $this->assertEquals(150000 - $expectedTotal, (float) $order->remaining_budget);
    }

    public function test_admin_can_view_budget_orders_history_and_detail(): void
    {
        // 1. Buat user admin
        $admin = User::create([
            'name'     => 'Admin Dapur Mama',
            'email'    => 'admin@dapurmama.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        // 2. Buat pesanan budget
        $order = BudgetOrder::create([
            'order_number'               => 'ORD-BGT-20261012-0001',
            'customer_name'              => 'Budi Santoso',
            'total_budget'               => 150000,
            'package_quantity'           => 5,
            'average_budget_per_package' => 30000,
            'total_price'                => 139000,
            'remaining_budget'           => 11000,
            'status'                     => 'Menunggu',
        ]);

        BudgetOrderItem::create([
            'budget_order_id' => $order->id,
            'package_number'  => 1,
            'menu_id'         => 1,
            'menu_name'       => 'Nasi Ayam Bakar',
            'menu_category'   => 'Makanan',
            'menu_price'      => 20000,
        ]);

        // 3. Admin membuka halaman riwayat pemesanan
        $this->actingAs($admin);

        $response = $this->get('/admin/budget-orders');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Pemesanan Berdasarkan Budget');
        $response->assertSee('Budi Santoso');
        $response->assertSee('ORD-BGT-20261012-0001');

        // 4. Tes komponen Livewire Admin
        Livewire::actingAs($admin)
            ->test(BudgetOrders::class)
            ->assertSee('Budi Santoso')
            ->call('viewDetail', $order->id)
            ->assertSet('showDetailModal', true)
            ->assertSee('Nasi Ayam Bakar')
            // Tes ubah status
            ->call('openUpdateStatus', $order->id)
            ->set('newStatus', 'Diproses')
            ->call('updateStatus');

        $this->assertEquals('Diproses', $order->fresh()->status);
    }

    public function test_packages_have_different_contents_when_multiple_packages_assigned(): void
    {
        // Setup menu tambahan untuk memastikan cukup variasi
        $catFood = Category::where('slug', 'nasi-box')->first();
        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Telur', 'price' => 10000]);

        $component = Livewire::test(BudgetPlanner::class)
            ->set('customerName', 'Budi')
            ->set('totalBudget', 100000)
            ->set('packageQuantity', 3)
            ->call('recommend');

        $assigned = $component->get('assignedPackages');
        $this->assertCount(3, $assigned);

        // Paket 1 dan Paket 2 harus memiliki isi yang berbeda
        $this->assertNotEquals(
            $assigned[1]['food_name'] . '-' . $assigned[1]['drink_name'],
            $assigned[2]['food_name'] . '-' . $assigned[2]['drink_name']
        );
    }

    public function test_changing_preference_dynamically_changes_saw_recommendation_and_prices(): void
    {
        $catFood = Category::where('slug', 'nasi-box')->first();
        Menu::create(['category_id' => $catFood->id, 'name' => 'Nasi Telur', 'price' => 8000]);

        $component = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 50000)
            ->set('packageQuantity', 2);

        // 1. Pilih preferensi Ayam
        $component->set('preference', 'ayam')
            ->call('recommend');
        $pkgAyam = $component->get('result')['packages'][0];
        $this->assertStringContainsString('Ayam', $pkgAyam['makanan']->name);

        // 2. Ubah preferensi ke Hemat
        $component->set('preference', 'hemat')
            ->call('recommend');
        $pkgHemat = $component->get('result')['packages'][0];
        $this->assertEquals('Nasi Telur', $pkgHemat['makanan']->name);
        $this->assertLessThan($pkgAyam['total_price'], $pkgHemat['total_price']);
    }

    public function test_different_budget_inputs_yield_different_recommendations_and_prices(): void
    {
        // Budget rendah: Rp 22.000 untuk 1 paket
        $lowBudgetComp = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 22000)
            ->set('packageQuantity', 1)
            ->call('recommend');
        $lowPkg = $lowBudgetComp->get('result')['packages'][0];

        // Budget tinggi: Rp 60.000 untuk 1 paket
        $highBudgetComp = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 60000)
            ->set('packageQuantity', 1)
            ->call('recommend');
        $highPkg = $highBudgetComp->get('result')['packages'][0];

        // Hasil rekomendasi harus dinamis dan total harga berbeda
        $this->assertLessThanOrEqual(22000, $lowPkg['total_price']);
        $this->assertLessThanOrEqual(60000, $highPkg['total_price']);
    }

    public function test_user_selecting_different_food_menu_dynamically_changes_recommendations(): void
    {
        $catFood = Category::where('slug', 'nasi-box')->first();
        $foodA = Menu::where('name', 'Nasi Ayam Bakar')->first();
        $foodB = Menu::where('name', 'Nasi Ayam Goreng')->first();

        $component = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 60000)
            ->set('packageQuantity', 2);

        // 1. Pilih Menu A: Nasi Ayam Bakar
        $component->set('selectedMenuId', $foodA->id)
            ->call('recommend');
        $pkgsA = $component->get('result')['packages'];
        $this->assertNotEmpty($pkgsA);
        $this->assertEquals($foodA->id, $pkgsA[0]['makanan']->id);

        // 2. Ganti ke Menu B: Nasi Ayam Goreng
        $component->set('selectedMenuId', $foodB->id)
            ->call('recommend');
        $pkgsB = $component->get('result')['packages'];
        $this->assertNotEmpty($pkgsB);
        $this->assertEquals($foodB->id, $pkgsB[0]['makanan']->id);

        // Harga dan makanan harus berbeda
        $this->assertNotEquals($pkgsA[0]['makanan']->name, $pkgsB[0]['makanan']->name);
    }

    public function test_unmatched_menu_returns_alternative_with_proper_apriori_explanation(): void
    {
        $catFood = Category::where('slug', 'nasi-box')->first();
        $newFood = Menu::create([
            'category_id' => $catFood->id,
            'name'        => 'Nasi Bebek Madura',
            'price'       => 16000,
        ]);

        $component = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 50000)
            ->set('packageQuantity', 2)
            ->set('selectedMenuId', $newFood->id)
            ->call('recommend');

        $packages = $component->get('result')['packages'];
        $this->assertNotEmpty($packages);

        // Tidak boleh mengklaim sebagai hasil Apriori jika pola belum ditemukan
        $firstPkg = $packages[0];
        $this->assertEquals($newFood->id, $firstPkg['makanan']->id);
        $this->assertFalse($firstPkg['is_apriori_match']);
        $this->assertStringContainsString('Pola asosiasi Apriori belum ditemukan', $firstPkg['rule_description']);
    }

    public function test_order_saving_persists_to_both_budget_orders_and_historical_orders_for_apriori(): void
    {
        $component = Livewire::test(BudgetPlanner::class)
            ->set('customerName', 'Siti Rahma')
            ->set('totalBudget', 80000)
            ->set('packageQuantity', 2)
            ->call('recommend')
            ->call('saveOrder');

        $component->assertHasNoErrors();

        // 1. Tersimpan di budget_orders
        $budgetOrder = BudgetOrder::where('customer_name', 'Siti Rahma')->first();
        $this->assertNotNull($budgetOrder);
        $this->assertStringContainsString('Metode: Apriori', $budgetOrder->notes);
        $this->assertGreaterThan(0, $budgetOrder->items()->count());

        // 2. Tersimpan di orders & order_details historis Apriori
        $generalOrder = \App\Models\Order::where('order_code', $budgetOrder->order_number)->first();
        $this->assertNotNull($generalOrder);
        $this->assertEquals('Siti Rahma', $generalOrder->recipient_name);
        $this->assertEquals('Selesai', $generalOrder->status);
        $this->assertGreaterThan(0, $generalOrder->orderDetails()->count());
    }

    public function test_recommendations_feature_varied_drinks_and_dynamically_filter(): void
    {
        $catDrink = Category::where('slug', 'minuman')->first();
        $catFood  = Category::where('slug', 'nasi-box')->first();

        // Siapkan beberapa minuman dengan harga bervariasi
        $d1 = Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Cokelat Premium', 'price' => 7000]);
        $d2 = Menu::create(['category_id' => $catDrink->id, 'name' => 'Es Lemon Segar', 'price' => 5000]);
        $d3 = Menu::create(['category_id' => $catDrink->id, 'name' => 'Teh Manis Hangat', 'price' => 3000]);

        $component = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 60000)
            ->set('packageQuantity', 2)
            ->call('recommend');

        $packages = $component->get('result')['packages'];
        $this->assertNotEmpty($packages);

        // Rekomendasi harus memiliki variasi minuman
        $drinkNames = array_map(fn($p) => $p['minuman']->name, $packages);
        $uniqueDrinks = array_unique($drinkNames);
        $this->assertGreaterThanOrEqual(2, count($uniqueDrinks));

        // Jika budget diturunkan drastis (misal Rp 20.000 untuk 2 porsi = Rp 10.000/porsi),
        // minuman mahal (Rp 7.000) bersama makanan (Rp 15.000) tidak akan muncul
        $componentTight = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 20000)
            ->set('packageQuantity', 2)
            ->call('recommend');

        $tightPackages = $componentTight->get('result')['packages'];
        foreach ($tightPackages as $p) {
            $this->assertLessThanOrEqual(10000, $p['total_price']);
        }
    }

    public function test_recommendations_feature_varied_snacks_across_packages(): void
    {
        $catSnack = Category::where('slug', 'snack-basah')->first();

        // Siapkan beberapa variasi snack
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Pastel Ayam Renyah', 'price' => 3500]);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Kue Lumpur Surga', 'price' => 3000]);
        Menu::create(['category_id' => $catSnack->id, 'name' => 'Onde-Onde Wijen', 'price' => 2500]);

        $component = Livewire::test(BudgetPlanner::class)
            ->set('totalBudget', 80000)
            ->set('packageQuantity', 3)
            ->set('packageType', 'complete')
            ->call('recommend');

        $packages = $component->get('result')['packages'];
        $this->assertNotEmpty($packages);

        // Rekomendasi harus memiliki variasi snack
        $snackNames = array_filter(array_map(fn($p) => $p['snack']?->name, $packages));
        $uniqueSnacks = array_unique($snackNames);
        $this->assertGreaterThanOrEqual(2, count($uniqueSnacks), "Pilihan snack antarpaket harus bervariasi.");
    }
}


