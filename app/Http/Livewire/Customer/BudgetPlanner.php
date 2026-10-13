<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\BudgetOrder;
use App\Models\BudgetOrderItem;
use App\Services\BudgetRecommendationService;
use Illuminate\Support\Facades\DB;

/**
 * BudgetPlanner — Sistem Rekomendasi Menu Berdasarkan Budget & Algoritma Apriori.
 *
 * Fitur:
 * 1. Form input pemesanan: Nama Pemesan, Total Budget, Jumlah Orang/Porsi, Menu Makanan yang Dipilih, dan Tipe Paket.
 * 2. Perhitungan otomatis Rata-rata Budget per Porsi (Total Budget ÷ Jumlah Porsi).
 * 3. Tiga tabel terpisah: Makanan, Minuman, Snack langsung dari database MySQL.
 * 4. Algoritma Data Mining Apriori untuk menemukan pola kombinasi menu berdasarkan transaksi historis database.
 * 5. Rekomendasi dinamis: menyesuaikan menu yang dipilih pengguna & aturan asosiasi yang ditemukan.
 * 6. Alternatif paket rekomendasi yang memiliki kombinasi makanan dan minuman berbeda serta harga bervariasi.
 * 7. Pilihan paket untuk setiap paket pesanan (Paket 1 s.d. N) dengan variasi otomatis antarpaket.
 * 8. Tampilan lengkap: nama menu, harga satuan, porsi, total harga paket, budget, sisa budget, Support, Confidence, Lift.
 * 9. Simpan pemesanan pelanggan ke database MySQL secara transaksional (budget_orders & orders historis Apriori).
 */
class BudgetPlanner extends Component
{
    /** Nama Pemesan */
    public string $customerName = '';

    /** Total budget untuk seluruh paket */
    public $totalBudget = null;

    /** Alias backward-compatibility untuk totalBudget */
    public $budget = null;

    /** Jumlah paket / porsi yang dipesan */
    public $packageQuantity = 1;

    /** Alias backward-compatibility untuk packageQuantity */
    public $pax = 1;

    /** Menu makanan yang dipilih pengguna (opsional) */
    public ?int $selectedMenuId = null;

    /** Rata-rata budget per paket (dihitung otomatis: Total Budget ÷ Jumlah Paket) */
    public float $averageBudget = 0.0;

    /** Preferensi Kriteria Menu (backward-compatibility) */
    public string $preference = 'semua';

    /** Tipe Paket: 'complete' (Makanan + Minuman + Snack) atau 'food_drink' (Makanan + Minuman) */
    public string $packageType = 'complete';

    /** Batas jumlah paket rekomendasi yang ditampilkan */
    public int $packageLimit = 20;

    /** Filter tampilan tabel menu: 'all', 'makanan', 'minuman', 'snack' */
    public string $activeMenuTab = 'all';

    /** Daftar seluruh menu dari database untuk 3 tabel */
    public array $allMenus = [
        'makanan' => [],
        'minuman' => [],
        'snack'   => [],
    ];

    /** Daftar menu makanan untuk dropdown pilihan menu awal */
    public array $availableFoodMenus = [];

    /** Hasil rekomendasi dari BudgetRecommendationService */
    public array $result = [];

    /**
     * Pilihan paket untuk setiap pesanan (Paket 1 s.d. N).
     */
    public array $assignedPackages = [];

    /** Total harga pesanan dari seluruh paket yang dipilih */
    public float $totalOrderPrice = 0.0;

    /** Sisa total budget = Total Budget - Total Harga Pesanan */
    public float $remainingTotalBudget = 0.0;

    /** Modal konfirmasi dan ringkasan pesanan berhasil disimpan */
    public ?BudgetOrder $savedOrderSummary = null;
    public bool $showSuccessModal = false;

    protected $queryString = [
        'customerName'    => ['as' => 'name', 'except' => ''],
        'totalBudget'     => ['as' => 'budget', 'except' => ''],
        'packageQuantity' => ['as' => 'qty', 'except' => 1],
        'selectedMenuId'  => ['as' => 'menu', 'except' => null],
        'packageLimit'    => ['as' => 'limit', 'except' => 20],
        'packageType'     => ['as' => 'type', 'except' => 'complete'],
    ];

    public function mount(): void
    {
        $this->customerName    = (string) request()->query('customer_name', request()->query('name', $this->customerName));
        $rawBudget             = request()->query('total_budget', request()->query('budget', $this->totalBudget));
        $rawQty                = request()->query('package_quantity', request()->query('packages', request()->query('pax', request()->query('qty', $this->packageQuantity))));
        $this->packageLimit    = (int) request()->query('limit', $this->packageLimit);
        $this->packageType     = (string) request()->query('package_type', request()->query('type', $this->packageType));
        $rawMenuId             = request()->query('menu', request()->query('selected_menu_id', $this->selectedMenuId));

        if ($rawMenuId !== null && $rawMenuId !== '' && is_numeric($rawMenuId)) {
            $this->selectedMenuId = (int) $rawMenuId;
        }

        if ($rawBudget !== null && $rawBudget !== '') {
            $this->totalBudget = $rawBudget;
            $this->budget      = $rawBudget;
        }

        if ($rawQty !== null && $rawQty !== '') {
            $cleanQty = (int) preg_replace('/[^0-9]/', '', (string) $rawQty);
            $this->packageQuantity = max(1, $cleanQty);
            $this->pax             = $this->packageQuantity;
        }

        $this->loadAllMenus();
        $this->recalculateAverageBudget();

        if ($this->cleanBudget() > 0) {
            $this->recommend();
        }
    }

    /**
     * Hitung rata-rata budget per paket secara otomatis: Total Budget ÷ Jumlah Paket.
     */
    public function recalculateAverageBudget(): void
    {
        $budget = $this->cleanBudget();
        $qty    = $this->cleanQuantity();

        if ($budget > 0 && $qty > 0) {
            $this->averageBudget = round($budget / $qty, 2);
        } else {
            $this->averageBudget = 0.0;
        }
    }

    public function updatedTotalBudget($val): void
    {
        $this->budget = $val;
        $this->recalculateAverageBudget();
        if ($this->cleanBudget() > 0) {
            $this->recommend();
        } else {
            $this->recalculateOrderTotals();
        }
    }

    public function updatedBudget($val): void
    {
        $this->totalBudget = $val;
        $this->recalculateAverageBudget();
        if ($this->cleanBudget() > 0) {
            $this->recommend();
        } else {
            $this->recalculateOrderTotals();
        }
    }

    public function updatedPackageQuantity($val): void
    {
        $clean = (int) preg_replace('/[^0-9]/', '', (string) $val);
        $this->packageQuantity = max(1, $clean);
        $this->pax             = $this->packageQuantity;
        $this->recalculateAverageBudget();

        if ($this->cleanBudget() > 0) {
            $this->recommend();
        } else if (!empty($this->result['packages'])) {
            $this->syncAssignedPackages();
            $this->recalculateOrderTotals();
        }
    }

    public function updatedPax($val): void
    {
        $clean = (int) preg_replace('/[^0-9]/', '', (string) $val);
        $this->packageQuantity = max(1, $clean);
        $this->pax             = $this->packageQuantity;
        $this->recalculateAverageBudget();

        if ($this->cleanBudget() > 0) {
            $this->recommend();
        } else if (!empty($this->result['packages'])) {
            $this->syncAssignedPackages();
            $this->recalculateOrderTotals();
        }
    }

    public function updatedSelectedMenuId($val): void
    {
        $this->selectedMenuId = (!empty($val) && is_numeric($val)) ? (int) $val : null;
        if ($this->cleanBudget() > 0) {
            $this->recommend();
        }
    }

    public function updatedPackageType($val): void
    {
        $this->packageType = $val;
        if ($this->cleanBudget() > 0) {
            $this->recommend();
        }
    }

    /**
     * Helper untuk mengambil total budget bersih (float).
     */
    public function cleanBudget(): float
    {
        $val = $this->totalBudget ?? $this->budget ?? 0;
        return (float) preg_replace('/[^0-9.]/', '', (string) $val);
    }

    /**
     * Helper untuk mengambil jumlah paket bersih (integer >= 1).
     */
    public function cleanQuantity(): int
    {
        $val = $this->packageQuantity ?? $this->pax ?? 1;
        $qty = (int) preg_replace('/[^0-9]/', '', (string) $val);
        return max(1, $qty);
    }

    /**
     * Muat seluruh menu database ke 3 tabel terpisah (Makanan, Minuman, Snack).
     */
    public function loadAllMenus(): void
    {
        $service = new BudgetRecommendationService();
        $buckets = $service->getMenusByGroup();

        $this->allMenus = [
            'makanan' => $buckets['makanan']->values()->toArray(),
            'minuman' => $buckets['minuman']->values()->toArray(),
            'snack'   => $buckets['snack']->values()->toArray(),
        ];

        $this->availableFoodMenus = $buckets['makanan']->values()->toArray();
    }

    /**
     * Pilih nominal budget cepat (Quick Budget Buttons).
     */
    public function setQuickBudget(int $amount): void
    {
        $this->totalBudget = $amount;
        $this->budget      = $amount;
        $this->recalculateAverageBudget();
        $this->recommend();
    }

    /**
     * Pilih menu makanan utama secara cepat.
     */
    public function selectFoodMenu(?int $menuId): void
    {
        $this->selectedMenuId = $menuId;
        if ($this->cleanBudget() > 0) {
            $this->recommend();
        }
    }

    /**
     * Ganti tab/filter tampilan tabel menu.
     */
    public function setMenuTab(string $tab): void
    {
        if (in_array($tab, ['all', 'makanan', 'minuman', 'snack'], true)) {
            $this->activeMenuTab = $tab;
        }
    }

    /**
     * Hitung rekomendasi berdasarkan Rata-rata Budget per Porsi dan Pola Algoritma Apriori.
     */
    public function recommend(): void
    {
        $this->resetErrorBag();
        $this->result = [];

        $cleanBudget = $this->cleanBudget();
        $cleanQty    = $this->cleanQuantity();

        if ($cleanBudget <= 0) {
            $this->addError('budget', 'Total budget harus berupa angka positif (lebih dari Rp 0).');
            $this->addError('totalBudget', 'Total budget harus berupa angka positif (lebih dari Rp 0).');
            return;
        }

        if ($cleanQty <= 0) {
            $this->addError('packageQuantity', 'Jumlah paket harus berupa bilangan bulat positif dan tidak boleh 0.');
            return;
        }

        $this->recalculateAverageBudget();

        // Cari rekomendasi paket berdasarkan total budget dan jumlah porsi menggunakan Algoritma Apriori
        $service = new BudgetRecommendationService();
        $includeSnack = ($this->packageType === 'complete');

        $this->result = $service->recommend(
            $cleanBudget,
            $cleanQty,
            $this->packageLimit > 0 ? $this->packageLimit : null,
            $this->selectedMenuId ?: $this->preference,
            $includeSnack,
            auth()->id()
        );

        if (empty($this->result['packages'])) {
            $this->assignedPackages = [];
            $this->totalOrderPrice  = 0.0;
            $this->remainingTotalBudget = $cleanBudget;
            $this->dispatch('notify', message: 'Belum ada kombinasi paket yang sesuai dengan alokasi budget tersebut. Coba naikkan total budget atau sesuaikan jumlah porsi.', type: 'warning');
            return;
        }

        // Sinkronisasi slot paket 1 s.d. N dengan alternatif paket rekomendasi berbeda
        $this->syncAssignedPackages(forceReset: true);
        $this->recalculateOrderTotals();
    }

    /**
     * Sinkronkan slot paket (Paket 1 s.d. Paket N) dengan paket-paket alternatif rekomendasi.
     * Paket 1, 2, 3 ... otomatis mengambil alternatif paket yang berbeda agar memiliki isi dan harga bervariasi.
     */
    public function syncAssignedPackages(bool $forceReset = false): void
    {
        $packages = $this->result['packages'] ?? [];
        if (empty($packages)) {
            $this->assignedPackages = [];
            return;
        }

        $qty          = $this->cleanQuantity();
        $packageCount = count($packages);
        $newAssigned  = [];

        for ($i = 1; $i <= $qty; $i++) {
            if (!$forceReset && isset($this->assignedPackages[$i]) && $this->isValidAssignedItem($this->assignedPackages[$i])) {
                $newAssigned[$i] = $this->assignedPackages[$i];
            } else {
                // Distribusikan paket alternatif rekomendasi berbeda ke tiap slot pesanan
                $comboIdx = ($packageCount > 0) ? (($i - 1) % $packageCount) : 0;
                $newAssigned[$i] = $this->buildAssignedPayload($comboIdx);
            }
        }

        $this->assignedPackages = $newAssigned;
    }

    /**
     * Bentuk data payload paket terpilih dari index paket rekomendasi.
     */
    private function buildAssignedPayload(int $comboIndex): array
    {
        $packages = $this->result['packages'] ?? [];
        if (!isset($packages[$comboIndex])) {
            $comboIndex = 0;
        }

        $pkg = $packages[$comboIndex] ?? null;
        if (!$pkg) {
            return [];
        }

        $m = $pkg['makanan'];
        $d = $pkg['minuman'];
        $s = $pkg['snack'] ?? null;

        return [
            'package_index'    => $comboIndex,
            'combo_number'     => $pkg['package_number'] ?? ($comboIndex + 1),
            'rank'             => $pkg['rank'] ?? ($comboIndex + 1),
            'label'            => $pkg['label'] ?? ('Paket ' . ($comboIndex + 1)),
            'support'          => $pkg['support'] ?? null,
            'confidence'       => $pkg['confidence'] ?? null,
            'lift'             => $pkg['lift'] ?? null,
            'is_apriori_match' => (bool) ($pkg['is_apriori_match'] ?? true),
            'rule_description' => $pkg['rule_description'] ?? null,
            'food_id'          => $m->id,
            'food_name'        => $m->name,
            'food_price'       => (float) $m->price,
            'drink_id'         => $d->id,
            'drink_name'       => $d->name,
            'drink_price'      => (float) $d->price,
            'snack_id'         => $s ? $s->id : null,
            'snack_name'       => $s ? $s->name : null,
            'snack_price'      => $s ? (float) $s->price : 0.0,
            'total_price'      => (float) $pkg['total_price'],
            'saw_score'        => $pkg['confidence'] ?? 1.0,
            'saw_percent'      => $pkg['confidence'] ?? 100.0,
        ];
    }

    private function isValidAssignedItem(array $item): bool
    {
        return !empty($item['food_id']) && !empty($item['drink_id']);
    }

    /**
     * Pilih kombinasi rekomendasi tertentu untuk 1 paket spesifik (Paket #$packageNum).
     */
    public function selectComboForPackage(int $packageNum, int $comboIndex): void
    {
        $payload = $this->buildAssignedPayload($comboIndex);
        if (!empty($payload)) {
            $this->assignedPackages[$packageNum] = $payload;
            $this->recalculateOrderTotals();
        }
    }

    /**
     * Terapkan 1 kombinasi rekomendasi tertentu ke SEMUA paket pesanan (Paket 1 s.d. N).
     */
    public function applyComboToAllPackages(int $comboIndex): void
    {
        $payload = $this->buildAssignedPayload($comboIndex);
        if (empty($payload)) {
            return;
        }

        $qty = $this->cleanQuantity();
        for ($i = 1; $i <= $qty; $i++) {
            $this->assignedPackages[$i] = $payload;
        }

        $this->recalculateOrderTotals();
        $label = $payload['label'] ?? ('Kombinasi #' . ($comboIndex + 1));
        $this->dispatch('notify', message: "Berhasil menerapkan {$label} ke seluruh {$qty} paket!", type: 'success');
    }

    /**
     * Hitung total harga seluruh paket dan sisa total budget pelanggan.
     */
    public function recalculateOrderTotals(): void
    {
        $total = 0.0;
        foreach ($this->assignedPackages as $pkg) {
            $total += (float) ($pkg['total_price'] ?? 0);
        }

        $this->totalOrderPrice = $total;
        $budget = $this->cleanBudget();
        $this->remainingTotalBudget = max(0.0, $budget - $total);
    }

    /**
     * Validasi dan simpan pemesanan pelanggan ke database MySQL secara transaksional.
     * Transaksi baru otomatis disimpan ke `budget_orders` dan tabel riwayat transaksi umum `orders` + `order_details`
     * sehingga dapat langsung menjadi dataset historis pada analisis Apriori berikutnya.
     */
    public function saveOrder(): void
    {
        $this->resetErrorBag();

        // 1. Validasi Input Pemesanan
        $this->validate([
            'customerName'    => 'required|string|min:2|max:255',
            'totalBudget'     => 'required|numeric|min:1',
            'packageQuantity' => 'required|integer|min:1',
        ], [
            'customerName.required'    => 'Nama pemesan wajib diisi.',
            'customerName.min'         => 'Nama pemesan minimal 2 karakter.',
            'totalBudget.required'     => 'Total budget wajib diisi.',
            'totalBudget.min'          => 'Total budget harus berupa angka positif.',
            'packageQuantity.required' => 'Jumlah paket / porsi wajib diisi.',
            'packageQuantity.min'      => 'Jumlah paket harus minimal 1 paket.',
        ]);

        $cleanBudget = $this->cleanBudget();
        $cleanQty    = $this->cleanQuantity();

        if (empty($this->assignedPackages) || count($this->assignedPackages) < $cleanQty) {
            $this->addError('order', 'Silakan cari rekomendasi dan pastikan seluruh paket telah dipilih.');
            $this->dispatch('notify', message: 'Silakan pilih paket rekomendasi terlebih dahulu.', type: 'error');
            return;
        }

        // 2. Verifikasi & Hitung Ulang Harga Langsung dari Database MySQL dengan Prepared Statements
        $menuIds = [];
        foreach ($this->assignedPackages as $pkg) {
            $menuIds[] = $pkg['food_id'];
            $menuIds[] = $pkg['drink_id'];
            if (!empty($pkg['snack_id'])) {
                $menuIds[] = $pkg['snack_id'];
            }
        }
        $dbMenus = Menu::with('category')->whereIn('id', array_unique($menuIds))->get()->keyBy('id');

        $recalculatedTotal = 0.0;
        $preparedItems = [];

        for ($pkgNum = 1; $pkgNum <= $cleanQty; $pkgNum++) {
            $pkg = $this->assignedPackages[$pkgNum] ?? null;
            if (!$pkg) {
                $this->addError('order', "Paket #{$pkgNum} belum dipilih.");
                return;
            }

            $food  = $dbMenus->get($pkg['food_id']);
            $drink = $dbMenus->get($pkg['drink_id']);
            $snack = !empty($pkg['snack_id']) ? $dbMenus->get($pkg['snack_id']) : null;

            if (!$food || !$drink) {
                $this->addError('order', 'Salah satu menu makanan atau minuman tidak ditemukan di database.');
                return;
            }

            $pkgTotal = (float) $food->price + (float) $drink->price + ($snack ? (float) $snack->price : 0.0);
            $recalculatedTotal += $pkgTotal;

            $preparedItems[] = [
                'package_number' => $pkgNum,
                'menu_id'        => $food->id,
                'menu_name'      => $food->name,
                'menu_category'  => 'Makanan',
                'menu_price'     => (float) $food->price,
            ];
            $preparedItems[] = [
                'package_number' => $pkgNum,
                'menu_id'        => $drink->id,
                'menu_name'      => $drink->name,
                'menu_category'  => 'Minuman',
                'menu_price'     => (float) $drink->price,
            ];
            if ($snack) {
                $preparedItems[] = [
                    'package_number' => $pkgNum,
                    'menu_id'        => $snack->id,
                    'menu_name'      => $snack->name,
                    'menu_category'  => 'Snack',
                    'menu_price'     => (float) $snack->price,
                ];
            }
        }

        // 3. Pastikan Total Pesanan Tidak Melebihi Total Budget
        if ($recalculatedTotal > $cleanBudget) {
            $this->addError('order', 'Total pesanan (Rp ' . number_format($recalculatedTotal, 0, ',', '.') . ') melebihi total budget (Rp ' . number_format($cleanBudget, 0, ',', '.') . ').');
            $this->dispatch('notify', message: 'Total harga pesanan melebihi budget pemesan!', type: 'error');
            return;
        }

        // 4. Simpan ke Database Secara Transaksional
        try {
            $order = DB::transaction(function () use ($cleanBudget, $cleanQty, $recalculatedTotal, $preparedItems, $dbMenus) {
                $orderNumber = BudgetOrder::generateOrderNumber();
                $avgBudget   = round($cleanBudget / $cleanQty, 2);
                $remaining   = max(0.0, $cleanBudget - $recalculatedTotal);

                $bestPkg = $this->result['packages'][0] ?? null;
                $confVal = $bestPkg['confidence'] ?? null;
                $supVal  = $bestPkg['support'] ?? null;
                $liftVal = $bestPkg['lift'] ?? null;

                $notes = sprintf(
                    "Metode: Apriori | %s | Conf: %s | Sup: %s | Lift: %s",
                    $bestPkg['label'] ?? 'Paket 1',
                    $confVal !== null ? $confVal . '%' : '-',
                    $supVal !== null ? $supVal . '%' : '-',
                    $liftVal !== null ? number_format((float) $liftVal, 2) : '-'
                );

                // A. Simpan ke tabel budget_orders
                $budgetOrder = BudgetOrder::create([
                    'order_number'               => $orderNumber,
                    'customer_name'              => trim($this->customerName),
                    'total_budget'               => $cleanBudget,
                    'package_quantity'           => $cleanQty,
                    'average_budget_per_package' => $avgBudget,
                    'total_price'                => $recalculatedTotal,
                    'remaining_budget'           => $remaining,
                    'status'                     => 'Menunggu',
                    'notes'                      => $notes,
                ]);

                // B. Simpan rincian item ke budget_order_items
                foreach ($preparedItems as $item) {
                    $item['budget_order_id'] = $budgetOrder->id;
                    BudgetOrderItem::create($item);
                }

                // C. Simpan juga ke tabel transaksi umum `orders` & `order_details`
                // agar transaksi baru ini dapat langsung digunakan sebagai data historis untuk analisis Apriori berikutnya
                $orderUserId = auth()->id() ?? \App\Models\User::first()?->id;
                if (!$orderUserId) {
                    $guest = \App\Models\User::create([
                        'name'     => trim($this->customerName) ?: 'Pelanggan Tamu',
                        'email'    => 'guest_' . uniqid() . '@dapurmama.test',
                        'password' => bcrypt('guest123'),
                        'role'     => 'customer',
                    ]);
                    $orderUserId = $guest->id;
                }

                $generalOrder = Order::create([
                    'user_id'          => $orderUserId,
                    'order_code'       => $orderNumber,
                    'order_date'       => now()->toDateString(),
                    'delivery_time'    => now()->addDay()->toTimeString(),
                    'total_price'      => $recalculatedTotal,
                    'status'           => 'Selesai',
                    'recipient_name'   => trim($this->customerName),
                    'notes'            => 'Pesanan Paket Rekomendasi Apriori: ' . $orderNumber,
                    'payment_method'   => 'Transfer Bank',
                    'payment_status'   => 'Lunas',
                    'is_read'          => false,
                ]);

                $quantitiesByMenu = [];
                foreach ($preparedItems as $item) {
                    $mId = $item['menu_id'];
                    $quantitiesByMenu[$mId] = ($quantitiesByMenu[$mId] ?? 0) + 1;
                }

                foreach ($quantitiesByMenu as $mId => $qty) {
                    $menuObj = $dbMenus->get($mId);
                    if ($menuObj) {
                        OrderDetail::create([
                            'order_id' => $generalOrder->id,
                            'menu_id'  => $menuObj->id,
                            'qty'      => $qty,
                            'price'    => (float) $menuObj->price,
                            'total'    => (float) $menuObj->price * $qty,
                        ]);
                    }
                }

                return $budgetOrder;
            });

            // 5. Muat Ringkasan dan Buka Modal
            $this->savedOrderSummary = $order->load('items');
            $this->showSuccessModal  = true;

            $this->dispatch('notify', message: "Pemesanan berhasil disimpan dengan nomor {$order->order_number}!", type: 'success');

        } catch (\Throwable $e) {
            report($e);
            $this->addError('order', 'Gagal menyimpan pesanan: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Terjadi kesalahan sistem saat menyimpan pemesanan.', type: 'error');
        }
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
    }

    public function createNewOrder(): void
    {
        $this->showSuccessModal  = false;
        $this->savedOrderSummary = null;
        $this->customerName      = '';
        $this->selectedMenuId    = null;
        $this->assignedPackages  = [];
        $this->totalOrderPrice   = 0.0;
        $this->remainingTotalBudget = 0.0;
        $this->result            = [];
    }

    /**
     * Tambahkan menu satuan ke keranjang belanja umum.
     */
    public function addMenuToCart(int $menuId, ?int $customQty = null): void
    {
        $menu = Menu::with('category')->find($menuId);
        if (!$menu) {
            $this->dispatch('notify', message: 'Menu tidak ditemukan.', type: 'error');
            return;
        }

        $minQty = $this->minQtyFor($menu);
        $qty = $customQty ?? max($this->cleanQuantity(), $minQty);

        $cart = session()->get('cart', []);
        $cartKey = 'menu_' . $menu->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                'id'            => $menu->id,
                'name'          => $menu->name,
                'price'         => (float) $menu->price,
                'type'          => 'menu',
                'qty'           => $qty,
                'image'         => $menu->image,
                'category_name' => $menu->category?->name ?? 'Umum',
                'min_qty'       => $minQty,
            ];
        }

        session()->put('cart', $cart);
        session()->save();

        $this->dispatch('cartUpdated', count: (int) collect($cart)->sum('qty'));
        $this->dispatch('notify', message: "\"{$menu->name}\" berhasil ditambahkan ke keranjang ({$qty} porsi).", type: 'success');
    }

    /**
     * Tambahkan seluruh menu dalam satu kombinasi paket ke keranjang umum.
     */
    public function addPackageToCart(int $packageIndex): void
    {
        $packages = $this->result['packages'] ?? [];
        if (!isset($packages[$packageIndex])) {
            $this->dispatch('notify', message: 'Paket rekomendasi tidak ditemukan.', type: 'error');
            return;
        }

        $pkg = $packages[$packageIndex];
        $items = [
            $pkg['makanan'] ?? null,
            $pkg['minuman'] ?? null,
        ];
        if (!empty($pkg['snack'])) {
            $items[] = $pkg['snack'];
        }

        $qtyMultiplier = $this->cleanQuantity();
        $cart = session()->get('cart', []);

        foreach ($items as $menu) {
            if (!$menu instanceof Menu) {
                continue;
            }

            $minQty  = $this->minQtyFor($menu);
            $qty     = max($qtyMultiplier, $minQty);
            $cartKey = 'menu_' . $menu->id;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['qty'] += $qty;
            } else {
                $cart[$cartKey] = [
                    'id'            => $menu->id,
                    'name'          => $menu->name,
                    'price'         => (float) $menu->price,
                    'type'          => 'menu',
                    'qty'           => $qty,
                    'image'         => $menu->image,
                    'category_name' => $menu->category?->name ?? 'Umum',
                    'min_qty'       => $minQty,
                ];
            }
        }

        session()->put('cart', $cart);
        session()->save();

        $this->dispatch('cartUpdated', count: (int) collect($cart)->sum('qty'));
        $label = $pkg['label'] ?? ('Paket ' . ($packageIndex + 1));
        $this->dispatch('notify', message: "{$label} berhasil ditambahkan ke keranjang!", type: 'success');
    }

    private function minQtyFor(Menu $menu): int
    {
        $name = strtolower(trim($menu->category?->name ?? ''));
        $slug = strtolower(trim($menu->category?->slug ?? ''));

        if (str_contains($name, 'nasi') || str_contains($name, 'box') || $slug === 'nasi-box') return 20;
        if (str_contains($name, 'basah') || $slug === 'snack-basah') return 20;
        if (str_contains($name, 'kering') || $slug === 'snack-kering') return 1;
        if (str_contains($name, 'minuman') || $slug === 'minuman') return 20;

        return 1;
    }

    public function render()
    {
        $layout = (auth()->check() && auth()->user()->isCustomer())
            ? 'layouts.customer'
            : 'layouts.app';

        return view('livewire.customer.budget-planner', [
            'result'             => $this->result,
            'allMenus'           => $this->allMenus,
            'availableFoodMenus' => $this->availableFoodMenus,
            'effectiveBudget'    => $this->cleanBudget(),
            'totalBudget'        => $this->cleanBudget(),
            'averageBudget'      => $this->averageBudget,
            'packageQuantity'    => $this->cleanQuantity(),
        ])->layout($layout);
    }
}
