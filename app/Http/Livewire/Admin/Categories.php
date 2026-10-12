<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Categories extends Component
{
    public string $search = '';

    // Modal
    public bool $showModal = false;
    public bool $showViewModal = false;
    public bool $showDeleteModal = false;

    // Form
    public bool $isEditing = false;
    public ?int $editId = null;
    public string $name = '';
    public string $description = '';

    // Selected category
    public ?Category $selectedCategory = null;

    // Delete
    public ?int $deleteId = null;
    public string $deleteName = '';

    // Informasi jumlah menu
    public int $currentMenusCount = 0;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name,' . $this->editId,
            ],
            'description' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Membuka modal tambah kategori
     */
    public function openCreate(): void
    {
        $this->resetForm();

        $this->isEditing = false;
        $this->showModal = true;
    }

    /**
     * Membuka modal edit kategori
     */
    public function openEdit(int $id): void
    {
        $this->resetValidation();

        $category = Category::withCount('menus')->findOrFail($id);

        $this->editId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->currentMenusCount = $category->menus_count;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Membuka modal detail kategori
     */
    public function openView(int $id): void
    {
        $this->selectedCategory = Category::withCount('menus')
            ->findOrFail($id);

        $this->showViewModal = true;
    }

    /**
     * Menutup modal detail
     */
    public function closeView(): void
    {
        $this->showViewModal = false;
        $this->selectedCategory = null;
    }

    /**
     * Membuka modal konfirmasi hapus
     */
    public function confirmDelete(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->deleteId = $category->id;
        $this->deleteName = $category->name;
        $this->showDeleteModal = true;
    }

    /**
     * Menghapus kategori
     */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        $category = Category::findOrFail($this->deleteId);

        // Jangan hapus kategori jika masih memiliki menu
        if ($category->menus()->exists()) {
            session()->flash(
                'error',
                'Kategori tidak dapat dihapus karena masih memiliki menu.'
            );

            $this->closeDelete();

            return;
        }

        $category->delete();

        session()->flash(
            'message',
            'Kategori berhasil dihapus.'
        );

        $this->closeDelete();
    }

    /**
     * Menutup modal hapus
     */
    public function closeDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = '';
    }

    /**
     * Menyimpan kategori
     */
    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => trim($this->name),
            'slug' => Str::slug($this->name),
            'description' => trim($this->description),
        ];

        if ($this->isEditing) {
            $category = Category::findOrFail($this->editId);

            $category->update($data);

            session()->flash(
                'message',
                'Kategori berhasil diperbarui.'
            );
        } else {
            Category::create($data);

            session()->flash(
                'message',
                'Kategori berhasil ditambahkan.'
            );
        }

        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset form kategori
     */
    private function resetForm(): void
    {
        $this->resetValidation();

        $this->editId = null;
        $this->name = '';
        $this->description = '';
        $this->currentMenusCount = 0;
        $this->isEditing = false;
    }

    /**
     * Render halaman kategori
     */
    public function render()
    {
        $categories = Category::withCount('menus')
            ->when(
                $this->search,
                function ($query) {
                    $query->where(
                        'name',
                        'like',
                        '%' . trim($this->search) . '%'
                    );
                }
            )
            ->latest()
            ->get();

        return view('livewire.admin.categories', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}