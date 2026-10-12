<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Customers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public bool $showModal = false;
    public bool $showDetailModal = false;
    public bool $showDeleteModal = false;
    public bool $isEditing = false;

    public ?int $deleteId = null;
    public string $deleteName = '';

    public ?int $selectedCustomerId = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $password = '';

    public ?User $selectedCustomer = null;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'email', 'phone', 'address', 'selectedCustomerId', 'isEditing']);
        // Berikan password default otomatis agar admin tidak kebingungan saat input pelanggan offline/WA
        $this->password = 'password123';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $this->resetValidation();
        $user = User::findOrFail($id);

        $this->selectedCustomerId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->address = $user->address ?? '';
        $this->password = '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function openDetail(int $id): void
    {
        $this->selectedCustomer = User::where('role', 'customer')
            ->with(['orders' => fn($q) => $q->latest()->with('orderDetails.menu', 'orderDetails.package')])
            ->findOrFail($id);

        $this->showDetailModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->reset(['name', 'email', 'phone', 'address', 'password', 'selectedCustomerId', 'isEditing', 'selectedCustomer']);
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.($this->selectedCustomerId ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => $this->isEditing ? 'nullable|min:6' : 'required|min:6',
        ];

        $validated = $this->validate($rules);

        if ($this->isEditing && $this->selectedCustomerId) {
            $user = User::findOrFail($this->selectedCustomerId);
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            session()->flash('message', 'Data pelanggan berhasil diperbarui!');
        } else {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
            ]);

            session()->flash('message', 'Pelanggan baru berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        $this->deleteId = $user->id;
        $this->deleteName = $user->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = '';
    }

    public function delete(): void
    {
        if (!$this->deleteId) return;

        $user = User::where('role', 'customer')->findOrFail($this->deleteId);

        if ($user->orders()->count() > 0) {
            $user->orders()->delete();
        }

        $user->delete();

        session()->flash('message', 'Data pelanggan berhasil dihapus!');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(7);

        $totalCustomers = User::where('role', 'customer')->count();
        $activeCustomers = User::where('role', 'customer')->has('orders')->count();
        $totalCustomerOrders = User::where('role', 'customer')->withCount('orders')->get()->sum('orders_count');

        return view('livewire.admin.customers', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'totalCustomerOrders'
        ))->layout('layouts.admin');
    }
}