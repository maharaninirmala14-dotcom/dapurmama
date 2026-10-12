<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AprioriProofPageTest extends TestCase
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

    /** @return array{0: Menu, 1: Menu, 2: Menu, 3: Menu} */
    private function createMenus(): array
    {
        $category = Category::create(['name' => 'Nasi Box', 'slug' => 'nasi-box']);

        return [
            Menu::create(['category_id' => $category->id, 'name' => 'Ayam Goreng', 'price' => 20000]),
            Menu::create(['category_id' => $category->id, 'name' => 'Es Teh', 'price' => 5000]),
            Menu::create(['category_id' => $category->id, 'name' => 'Sayur Asem', 'price' => 8000]),
            Menu::create(['category_id' => $category->id, 'name' => 'Sambal', 'price' => 3000]),
        ];
    }

    /**
     * @param  Menu[]  $items
     */
    private function createOrders(User $user, array $items, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => 'ORD-' . $user->id . '-' . $i,
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

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_proof_page_requires_admin(): void
    {
        $this->get(route('admin.apriori.proof'))->assertRedirect(route('login'));
    }

    public function test_proof_page_shows_computed_comparison_for_two_customers(): void
    {
        [$menuA, $menuB, $menuC, $menuD] = $this->createMenus();

        // Pelanggan 1: selalu Ayam Goreng + Es Teh
        $budi = $this->createCustomer('Budi');
        $this->createOrders($budi, [$menuA, $menuB], 5);

        // Pelanggan 2: selalu Ayam Goreng + Sayur Asem
        $siti = $this->createCustomer('Siti');
        $this->createOrders($siti, [$menuA, $menuC], 5);

        $response = $this->actingAs($this->admin())->get(route('admin.apriori.proof'));

        $response->assertOk();
        $response->assertSee('Bukti Kerja Algoritma Apriori');
        $response->assertSee('Budi');
        $response->assertSee('Siti');

        // Data transaksi masing-masing pelanggan tampil sebagai dataset
        $response->assertSee('Es Teh');
        $response->assertSee('Sayur Asem');

        // Kesimpulan perbandingan dihitung, dan hasilnya BERBEDA
        $response->assertSee('Kesimpulan Perbandingan');
        $response->assertSee('Rekomendasi');
        $response->assertSee('berbeda', false);
    }

    public function test_proof_page_warns_when_selecting_the_same_customer_twice(): void
    {
        [$menuA, $menuB] = $this->createMenus();
        $budi = $this->createCustomer('Budi');
        $this->createOrders($budi, [$menuA, $menuB], 3);

        $component = \Livewire\Livewire::test(\App\Http\Livewire\Admin\AprioriProof::class)
            ->set('userIdA', $budi->id)
            ->set('userIdB', $budi->id);

        // analisis dibatalkan (kedua panel kosong) saat pelanggan yang dipilih sama
        $component->assertSet('proofA', []);
        $component->assertSet('proofB', []);
        $component->assertSet('comparison', []);
    }

    public function test_changing_customers_recomputes_different_proofs(): void
    {
        [$menuA, $menuB, $menuC] = $this->createMenus();

        $budi = $this->createCustomer('Budi');
        $this->createOrders($budi, [$menuA, $menuB], 5);

        $siti = $this->createCustomer('Siti');
        $this->createOrders($siti, [$menuA, $menuC], 5);

        $component = \Livewire\Livewire::test(\App\Http\Livewire\Admin\AprioriProof::class)
            ->set('userIdA', $budi->id)
            ->set('userIdB', $siti->id);

        $proofA = $component->get('proofA');
        $proofB = $component->get('proofB');
        $comparison = $component->get('comparison');

        $this->assertSame('Budi', $proofA['user']['name']);
        $this->assertSame('Siti', $proofB['user']['name']);

        // Rekomendasi diturunkan dari transaksi masing-masing → berbeda
        $this->assertNotEmpty($proofA['recommended_menu_ids']);
        $this->assertNotEmpty($proofB['recommended_menu_ids']);
        $this->assertNotEquals(
            $proofA['recommended_menu_ids'],
            $proofB['recommended_menu_ids'],
            'Rekomendasi harus beda karena riwayat transaksi berbeda'
        );
        $this->assertFalse($comparison['same_recommendations']);
        $this->assertFalse($comparison['same_history']);

        // Rules masing-masing hanya memuat menu dari riwayatnya sendiri
        $itemsA = collect($proofA['rules'])
            ->flatMap(fn ($r) => array_merge($r['antecedent_names'], $r['consequent_names']))
            ->unique()->values()->all();
        $this->assertContains('Es Teh', $itemsA);
        $this->assertNotContains('Sayur Asem', $itemsA);
    }
}
