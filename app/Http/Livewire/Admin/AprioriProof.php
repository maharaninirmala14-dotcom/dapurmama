<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Services\AprioriService;

/**
 * Halaman "Bukti Apriori" — membandingkan hasil analisis dua pelanggan
 * berdampingan untuk membuktikan algoritma Apriori benar-benar belajar
 * dari data transaksi masing-masing (bukan hardcode).
 *
 * Semua angka (frequent itemset, support, confidence, lift, rekomendasi)
 * dihitung ulang oleh App\Services\AprioriService saat halaman dibuka /
 * saat pelanggan diganti.
 */
class AprioriProof extends Component
{
    public $userIdA;
    public $userIdB;

    /** @var array<int, array{id:int, name:string, orders:int}> */
    public array $customers = [];

    public array $proofA = [];
    public array $proofB = [];
    public array $comparison = [];

    protected $rules = [
        'userIdA' => 'nullable|integer|exists:users,id',
        'userIdB' => 'nullable|integer|exists:users,id',
    ];

    public function mount(): void
    {
        $orderCounts = Order::whereIn('status', AprioriService::INCLUDED_STATUSES)
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as total')
            ->pluck('total', 'user_id');

        $this->customers = User::where('role', 'customer')
            ->orderBy('name')
            ->get()
            ->map(fn ($user) => [
                'id'     => $user->id,
                'name'   => $user->name,
                'orders' => (int) ($orderCounts[$user->id] ?? 0),
            ])
            ->values()
            ->all();

        // Default demo: pasangan Nirmala & Nirmanto bila ada, selain itu
        // pakai dua pelanggan pertama yang punya transaksi.
        $withOrders = collect($this->customers)->where('orders', '>', 0)->values();

        $this->userIdA = $this->defaultUserBy('NIRMALA') ?? $withOrders->first()['id'] ?? null;

        $this->userIdB = $this->defaultUserBy('NIRMANTO')
            ?? $withOrders->first(fn ($c) => $c['id'] !== $this->userIdA)['id']
            ?? null;

        $this->analyze();
    }

    public function updatedUserIdA(): void
    {
        $this->analyze();
    }

    public function updatedUserIdB(): void
    {
        $this->analyze();
    }

    public function analyze(): void
    {
        $this->resetErrorBag();

        if (!$this->userIdA || !$this->userIdB || (int) $this->userIdA === (int) $this->userIdB) {
            $this->proofA = [];
            $this->proofB = [];
            $this->comparison = [];
            return;
        }

        $service = new AprioriService();

        $this->proofA = $service->getProofForUser((int) $this->userIdA);
        $this->proofB = $service->getProofForUser((int) $this->userIdB);
        $this->comparison = $this->buildComparison($this->proofA, $this->proofB);
    }

    /**
     * Perbandingan yang seluruhnya diturunkan dari hasil analisis kedua pelanggan.
     */
    private function buildComparison(array $proofA, array $proofB): array
    {
        $idsA = collect($proofA['recommended_menu_ids'] ?? []);
        $idsB = collect($proofB['recommended_menu_ids'] ?? []);

        $namesA = $this->recommendationMenuMap($proofA);
        $namesB = $this->recommendationMenuMap($proofB);

        $onlyA = $idsA->diff($idsB)->values();
        $onlyB = $idsB->diff($idsA)->values();
        $shared = $idsA->intersect($idsB)->values();

        return [
            'same_recommendations' => $idsA->sort()->values()->all() === $idsB->sort()->values()->all()
                && $idsA->isNotEmpty(),
            'only_a_names'         => $onlyA->map(fn ($id) => $namesA[$id] ?? ('Menu #' . $id))->values()->all(),
            'only_b_names'         => $onlyB->map(fn ($id) => $namesB[$id] ?? ('Menu #' . $id))->values()->all(),
            'shared_names'         => $shared->map(fn ($id) => $namesA[$id] ?? $namesB[$id] ?? ('Menu #' . $id))->values()->all(),
            'rules_count_a'        => count($proofA['rules'] ?? []),
            'rules_count_b'        => count($proofB['rules'] ?? []),
            'itemsets_count_a'     => collect($proofA['frequent_itemsets'] ?? [])->flatten(1)->count(),
            'itemsets_count_b'     => collect($proofB['frequent_itemsets'] ?? [])->flatten(1)->count(),
            'same_history'         => $this->historySignature($proofA) === $this->historySignature($proofB),
        ];
    }

    /**
     * Sidik jari belanja pelanggan: daftar menu unik dari seluruh transaksinya.
     */
    private function historySignature(array $proof): string
    {
        return implode(',', $proof['item_ids'] ?? []);
    }

    private function recommendationMenuMap(array $proof): array
    {
        $map = [];
        foreach ($proof['recommendations'] ?? [] as $rec) {
            foreach ($rec['menu_ids'] as $index => $id) {
                $map[$id] = $rec['menu_names'][$index] ?? ('Menu #' . $id);
            }
        }

        return $map;
    }

    private function defaultUserBy(string $needle): ?int
    {
        $found = collect($this->customers)->first(
            fn ($c) => str_contains(strtoupper($c['name']), $needle)
        );

        return $found ? (int) $found['id'] : null;
    }

    public function render()
    {
        return view('livewire.admin.apriori-proof', [
            'customers'  => $this->customers,
            'proofA'     => $this->proofA,
            'proofB'     => $this->proofB,
            'comparison' => $this->comparison,
        ])->layout('layouts.admin');
    }
}
