<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Carbon\Carbon;

class Transactions extends Component
{
    use WithPagination;

    public $search = '';
    public $status = ''; 
    public $startDate = '';
    public $endDate = '';

    protected $queryString = ['search', 'status', 'startDate', 'endDate'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $totalTransactions = Order::count();
        
        $todaySales = Order::whereDate('created_at', Carbon::today())
                            ->where(function($q) {
                                $q->where('payment_status', 'paid')
                                  ->orWhere('payment_method', 'like', '%lunas%')
                                  ->orWhere('payment_status', 'like', '%DP 50%')
                                  ->orWhere('payment_method', 'like', '%DP 50%');
                            })
                            ->sum('total_price');

        $completedTransactions = Order::where('payment_status', 'paid')
                                      ->orWhere('payment_method', 'like', '%lunas%')
                                      ->orWhere('payment_status', 'like', '%DP 50%')
                                      ->orWhere('payment_method', 'like', '%DP 50%')
                                      ->count();
        
        $completedPercentage = $totalTransactions > 0 
            ? round(($completedTransactions / $totalTransactions) * 100, 2) 
            : 0;

        $averageTransaction = $totalTransactions > 0 
            ? Order::avg('total_price') 
            : 0;

        $query = Order::with('user');

        // Filter Pencarian
        if (!empty($this->search)) {
            $searchTerm = trim($this->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_code', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($sub) use ($searchTerm) {
                      $sub->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter Status Transaksi (Pending dikhususkan untuk COD / Menunggu Pembayaran di Tempat)
        if (!empty($this->status)) {
            $statusVal = trim($this->status);
            if ($statusVal === 'paid') {
                $query->where(function($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere('payment_method', 'like', '%lunas%');
                });
            } elseif ($statusVal === 'dp') {
                $query->where(function($q) {
                    $q->where('payment_status', 'like', '%DP 50%')
                      ->orWhere('payment_method', 'like', '%DP 50%');
                });
            } elseif ($statusVal === 'pending') {
                $query->where(function($q) {
                    $q->where('payment_status', 'like', '%COD%')
                      ->orWhere('payment_method', 'like', '%COD%')
                      ->orWhere('payment_status', 'pending');
                });
            } elseif ($statusVal === 'failed') {
                $query->where(function($q) {
                    $q->where('payment_status', 'like', '%gagal%')
                      ->orWhere('payment_status', 'like', '%batal%');
                });
            }
        }

        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $query->latest();

        $transactions = $query->paginate(7);

        return view('livewire.admin.transactions', compact(
            'totalTransactions',
            'todaySales',
            'completedTransactions',
            'completedPercentage',
            'averageTransaction',
            'transactions'
        ))->layout('layouts.admin');
    }
}