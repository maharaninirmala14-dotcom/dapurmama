<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use App\Models\Review;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CustomerOrders extends Component
{
    public string $activeTab = 'active';
    public string $search = '';
    public ?int $detailOrderId = null;

    public bool $showCancelModal = false;
    public ?int $cancelOrderId = null;
    public string $cancelOrderCode = '';

    // Properti Form Ulasan
    public ?int $selectedOrderId = null;
    public int $rating = 5;
    public string $comment = '';

    public function mount()
    {
        if (request()->has('tab') && request()->get('tab') === 'history') {
            $this->activeTab = 'history';
        }

        if (request()->has('search')) {
            $this->search = request()->get('search');
        }
    }

    public function openReview(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->rating = 5;
        $this->comment = '';
    }

    public function submitReview(): void
    {
        $this->validate([
            'selectedOrderId' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $existingReview = Review::where('order_id', $this->selectedOrderId)->first();
        if ($existingReview) {
            $this->reset(['selectedOrderId', 'rating', 'comment']);
            $this->dispatch('notify', message: 'Pesanan ini sudah pernah diulas.', type: 'error');
            return;
        }

        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $this->selectedOrderId,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['selectedOrderId', 'rating', 'comment']);

        session()->flash('success', 'Ulasan berhasil dikirim!');

        $this->redirect(route('customer.reviews'), navigate: true);
    }

    public function confirmCancelOrder(int $orderId): void
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if ($order) {
            $this->cancelOrderId = $order->id;
            $this->cancelOrderCode = $order->order_code;
            $this->showCancelModal = true;
        }
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
        $this->cancelOrderId = null;
        $this->cancelOrderCode = '';
    }

    public function cancelOrder(): void
    {
        $order = Order::where('id', $this->cancelOrderId)
            ->where('user_id', Auth::id())
            ->first();

        if ($order && in_array($order->status, ['Menunggu', 'Diproses'])) {
            $order->update(['status' => 'Dibatalkan']);

            Notification::create([
                'user_id' => Auth::id(),
                'title'   => 'Pesanan Dibatalkan',
                'message' => "Pesanan #{$order->order_code} telah dibatalkan.",
                'type'    => 'danger',
                'is_read' => false,
            ]);

            $this->dispatch('notify', message: "Pesanan #{$order->order_code} berhasil dibatalkan.", type: 'success');
        } else {
            $this->dispatch('notify', message: 'Pesanan tidak dapat dibatalkan.', type: 'error');
        }

        $this->closeCancelModal();
    }

    public function showDetail(int $orderId): void
    {
        $this->detailOrderId = $orderId;
    }

    public function closeDetail(): void
    {
        $this->detailOrderId = null;
    }

    public function render()
    {
        if (request()->has('tab')) {
            $this->activeTab = request()->get('tab');
        }

        // Query utama dengan relasi lengkap
        $query = Order::where('user_id', Auth::id())
            ->with(['orderDetails.menu', 'orderDetails.package', 'reviews']);

        if (!empty($this->search)) {
            $query->where('order_code', 'like', '%' . $this->search . '%');
        }

        $allOrders = $query->latest()->get();

        // Mengelompokkan status pesanan secara fleksibel (mengantisipasi variasi huruf kapital dari inputan admin/seeder)
        $activeOrders = $allOrders->filter(function ($order) {
            return in_array(strtolower($order->status), ['menunggu', 'diproses']);
        })->values();

        $historyOrders = $allOrders->filter(function ($order) {
            return in_array(strtolower($order->status), ['selesai', 'dibatalkan', 'completed']);
        })->values();

        $displayOrders = $this->activeTab === 'history' ? $historyOrders : $activeOrders;

        $detailOrder = $this->detailOrderId ? $allOrders->firstWhere('id', $this->detailOrderId) : null;

        return view('livewire.customer.orders', [
            'activeOrders' => $activeOrders,
            'historyOrders' => $historyOrders,
            'allOrders' => $allOrders,
            'displayOrders' => $displayOrders,
            'detailOrder' => $detailOrder,
            'activeTab' => $this->activeTab,
        ])->layout('layouts.customer');
    }
}