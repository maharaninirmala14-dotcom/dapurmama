<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class Orders extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public ?int $detailOrderId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['as' => 'status', 'except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function showDetail(int $orderId): void
    {
        $this->detailOrderId = $orderId;
    }

    public function closeDetail(): void
    {
        $this->detailOrderId = null;
    }

    public function updateOrderStatus(int $orderId, string $newStatus): void
    {
        $order = Order::find($orderId);

        if ($order) {
            $order->update(['status' => $newStatus]);

            Notification::create([
                'user_id' => $order->user_id,
                'title'   => 'Status Pesanan Diperbarui',
                'message' => "Pesanan #{$order->order_code} saat ini berstatus: {$newStatus}.",
                'type'    => 'info',
                'is_read' => false,
            ]);

            $this->dispatch('notify', message: "Status pesanan #{$order->order_code} berhasil diperbarui menjadi {$newStatus}.", type: 'success');
        } else {
            $this->dispatch('notify', message: 'Pesanan tidak ditemukan.', type: 'error');
        }
    }

    public function render()
    {
        // Menggunakan alias customer_real_name untuk melewati accessor yang menampilkan kata "Pelanggan"
        $query = Order::query()
            ->with(['user', 'orderDetails.menu', 'orderDetails.package'])
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as customer_real_name', 'users.phone as customer_phone');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('orders.order_code', 'like', '%' . $this->search . '%')
                  ->orWhere('users.name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('orders.status', $this->statusFilter);
        }

        $totalOrders = Order::count();
        $processingOrders = Order::where('status', 'Diproses')->count();
        $shippingOrders = Order::where('status', 'Siap Dikirim')->count(); 
        $completedOrders = Order::where('status', 'Selesai')->count();

        $transactions = $query->latest('orders.created_at')->paginate(10);
        $detailOrder = $this->detailOrderId ? Order::with(['user', 'orderDetails.menu', 'orderDetails.package'])->find($this->detailOrderId) : null;

        return view('livewire.admin.orders', [
            'transactions' => $transactions,
            'detailOrder' => $detailOrder,
            'totalOrders' => $totalOrders,
            'processingOrders' => $processingOrders,
            'shippingOrders' => $shippingOrders,
            'completedOrders' => $completedOrders,
        ])->layout('layouts.admin');
    }
}