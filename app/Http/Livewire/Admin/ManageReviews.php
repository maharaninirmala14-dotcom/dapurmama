<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Review;

class ManageReviews extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // '' = Semua Status, 'belum' = Belum Dibalas, 'sudah' = Sudah Dibalas

    public $replyModal = false;
    public $reviewId;
    public $adminReply = '';

    // Reset halaman jika melakukan pencarian atau filter
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    // Membuka modal balas ulasan
    public function openReplyModal($id)
    {
        $review = Review::with(['user', 'order'])->find($id);
        if ($review) {
            $this->reviewId = $review->id;
            $this->adminReply = $review->admin_reply ?? '';
            $this->replyModal = true;
        }
    }

    // Menyimpan balasan admin ke database
    public function saveReply()
    {
        $this->validate([
            'adminReply' => 'required|string|max:500',
        ], [
            'adminReply.required' => 'Tanggapan admin tidak boleh kosong.',
            'adminReply.max' => 'Tanggapan maksimal 500 karakter.',
        ]);

        $review = Review::find($this->reviewId);
        if ($review) {
            $review->admin_reply = $this->adminReply;
            $review->save();
        }

        $this->replyModal = false;
        session()->flash('success', 'Balasan ulasan berhasil dikirim!');
    }

    public function render()
    {
        $query = Review::with(['user', 'order'])->latest();

        // Fitur Pencarian berdasarkan nama customer atau kode pesanan
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('order', function($orderQuery) {
                    $orderQuery->where('order_code', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Fitur Filter Berdasarkan Status Balasan
        if ($this->statusFilter === 'belum') {
            $query->whereNull('admin_reply')->orWhere('admin_reply', '');
        } elseif ($this->statusFilter === 'sudah') {
            $query->whereNotNull('admin_reply')->where('admin_reply', '!=', '');
        }

        $reviews = $query->paginate(5);
        $totalReviewsCount = Review::count();

        return view('livewire.admin.manage-reviews', compact('reviews', 'totalReviewsCount'))
                 ->layout('layouts.admin');
    }
}