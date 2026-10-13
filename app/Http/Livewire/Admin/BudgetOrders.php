<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BudgetOrder;

/**
 * BudgetOrders — Halaman Admin Riwayat Pemesanan Berdasarkan Budget.
 *
 * Fitur:
 * - Menampilkan daftar riwayat seluruh pesanan dari database MySQL.
 * - Pencarian berdasarkan nama pemesan & nomor pesanan.
 * - Filter rentang tanggal pemesanan & filter status.
 * - Modal detail rincian paket (Makanan, Minuman, Snack, harga masing-masing, total paket).
 * - Fitur update status pemesanan (Menunggu, Diproses, Selesai, Batal).
 */
class BudgetOrders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $statusFilter = '';

    public bool $showDetailModal = false;
    public ?BudgetOrder $selectedOrder = null;

    public bool $showStatusModal = false;
    public ?int $editStatusOrderId = null;
    public string $newStatus = 'Menunggu';

    protected $queryString = [
        'search'       => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'startDate'    => ['except' => null],
        'endDate'      => ['except' => null],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'startDate', 'endDate', 'statusFilter']);
        $this->resetPage();
    }

    /**
     * Tampilkan modal rincian detail pesanan.
     */
    public function viewDetail(int $orderId): void
    {
        $this->selectedOrder = BudgetOrder::with(['items.menu'])->findOrFail($orderId);
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->selectedOrder = null;
    }

    /**
     * Buka modal ubah status pesanan.
     */
    public function openUpdateStatus(int $orderId): void
    {
        $order = BudgetOrder::findOrFail($orderId);
        $this->editStatusOrderId = $order->id;
        $this->newStatus = $order->status;
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->editStatusOrderId = null;
    }

    /**
     * Simpan pembaruan status pesanan.
     */
    public function updateStatus(): void
    {
        if (!$this->editStatusOrderId) {
            return;
        }

        $order = BudgetOrder::findOrFail($this->editStatusOrderId);
        $order->update([
            'status' => $this->newStatus,
        ]);

        $this->closeStatusModal();
        $this->dispatch('notify', message: "Status pesanan {$order->order_number} berhasil diperbarui menjadi {$this->newStatus}.", type: 'success');
    }

    public function render()
    {
        $query = BudgetOrder::with('items')->latest();

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('customer_name', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('order_number', 'LIKE', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $orders = $query->paginate(10);

        return view('livewire.admin.budget-orders', [
            'orders' => $orders,
        ])->layout('layouts.admin');
    }
}
