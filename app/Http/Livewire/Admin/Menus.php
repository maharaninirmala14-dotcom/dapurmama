<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Menus extends Component
{
    use WithFileUploads;

    public $search = '';
    public $isForm = false;
    public $isEditing = false;
    public $isView = false; // Status untuk mode Lihat Detail

    public $editId = null;
    public $name = '';
    public $category_id = null;
    public $price = null;
    public $description = '';
    public $image;
    public $oldImage = null;
    public $categoryName = ''; // Untuk menampilkan nama kategori saat mode view

    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteName = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'category_id', 'price', 'description', 'image', 'oldImage', 'categoryName']);
        $this->resetValidation();
        $this->isEditing = false;
        $this->isView = false;
        $this->isForm = true;
    }

    public function openEdit($id)
    {
        $this->resetValidation();
        $menu = Menu::findOrFail($id);

        $this->editId = $menu->id;
        $this->name = $menu->name;
        $this->category_id = $menu->category_id;
        $this->price = $menu->price;
        $this->description = $menu->description ?? '';
        $this->oldImage = $menu->image;
        $this->image = null;

        $this->isEditing = true;
        $this->isView = false;
        $this->isForm = true;
    }

    public function openView($id)
    {
        $menu = Menu::with('category')->findOrFail($id);

        $this->editId = $menu->id;
        $this->name = $menu->name;
        $this->categoryName = $menu->category->name ?? '-';
        $this->price = $menu->price;
        $this->description = $menu->description ?? '-';
        $this->oldImage = $menu->image;

        $this->isView = true;
        $this->isEditing = false;
        $this->isForm = true;
    }

    public function cancelForm()
    {
        $this->isForm = false;
        $this->isView = false;
        $this->isEditing = false;
        $this->reset(['editId', 'name', 'category_id', 'price', 'description', 'image', 'oldImage', 'categoryName']);
    }

    public function save()
    {
        if ($this->isView) return; // Mencegah simpan jika dalam mode view

        $this->validate();

        $imagePath = $this->oldImage;

        if ($this->image) {
            if ($this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('menus', 'public');
        }

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'stock' => 50,
            'status' => 'aktif',
            'description' => $this->description,
            'image' => $imagePath,
        ];

        if ($this->isEditing) {
            Menu::findOrFail($this->editId)->update($data);
            session()->flash('message', 'Menu berhasil diperbarui!');
        } else {
            Menu::create($data);
            session()->flash('message', 'Menu berhasil ditambahkan!');
        }

        $this->cancelForm();
    }

    public function confirmDelete($id)
    {
        $menu = Menu::findOrFail($id);
        $this->deleteId = $menu->id;
        $this->deleteName = $menu->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = '';
    }

    public function delete()
    {
        if (!$this->deleteId) return;

        $menu = Menu::findOrFail($this->deleteId);
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();

        session()->flash('message', 'Menu berhasil dihapus.');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $menus = Menu::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->latest()
            ->get();

        $categories = Category::all();

        return view('livewire.admin.menus', compact('menus', 'categories'))->layout('layouts.admin');
    }
}