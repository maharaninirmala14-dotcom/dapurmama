<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Services\AprioriService;
use App\Models\Setting;
use App\Models\Order;
use App\Models\User;

class Apriori extends Component
{
    public float $minSupport = 0.2;
    public float $minConfidence = 0.5;
    public $results = null;
    public bool $hasRun = false;

    // ============================================================
    // Bagian "Bukti Personalisasi per Pelanggan" (gabung satu halaman)
    // Logika sama dengan halaman Bukti Apriori; AprioriService tetap satu-satunya sumber hitungan.
    // ============================================================
    public $userIdA;
    public $userIdB;
    public array $customers = [];
    public array $proofA = [];
    public array $proofB = [];
    public array $comparison = [];

    public function mount()
    {
        $this->minSupport = (float) Setting::getValue('min_support', 0.2);
        $this->minConfidence = (float) Setting::getValue('min_confidence', 0.5);

        // Langsung jalankan analisis otomatis saat halaman dibuka
        $this->runApriori();

        // Siapkan perbandingan bukti per pelanggan (default: Nirmala vs Nirmanto)
        $this->loadCustomersForProof();
        $this->analyzeProof();
    }

    public function saveSettings()
    {
        $this->validate([
            'minSupport' => 'required|numeric|min:0.01|max:1',
            'minConfidence' => 'required|numeric|min:0.01|max:1',
        ]);

        Setting::setValue('min_support', $this->minSupport);
        Setting::setValue('min_confidence', $this->minConfidence);

        // Jalankan ulang analisis setelah setting disimpan
        $this->runApriori();
        $this->analyzeProof();

        $this->dispatch('notify', message: 'Pengaturan Apriori disimpan & dianalisis ulang!', type: 'success');
    }

    public function runApriori()
    {
        $service = new AprioriService();
        $service->setParameters($this->minSupport, $this->minConfidence);
        $this->results = $service->run();
        $this->hasRun = true;
    }

    // ------------------------------------------------------------
    // LOGIKA BUKTI PERSONALISASI
    // ------------------------------------------------------------

    public function loadCustomersForProof(): void
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

        $withOrders = collect($this->customers)->where('orders', '>', 0)->values();

        $this->userIdA = $this->defaultUserBy('NIRMALA') ?? $withOrders->first()['id'] ?? null;

        $this->userIdB = $this->defaultUserBy('NIRMANTO')
            ?? $withOrders->first(fn ($c) => $c['id'] !== $this->userIdA)['id']
            ?? null;
    }

    public function updatedUserIdA(): void
    {
        $this->analyzeProof();
    }

    public function updatedUserIdB(): void
    {
        $this->analyzeProof();
    }

    public function analyzeProof(): void
    {
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
        return view('livewire.admin.apriori', [
            'results' => $this->results
        ])->layout('layouts.admin');
    }
}