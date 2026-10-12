<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Menu;

class AdminPackages extends Component
{
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editId = null;
    public string $name = '';
    public string $description = '';
    public float $price = 0;
    public array $selectedMenus = [];

    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteName = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'selectedMenus' => 'required|array|min:1',
        ];
    }

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'description', 'price', 'selectedMenus']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id)
    {
        $pkg = Package::with('menus')->findOrFail($id);
        $this->editId = $id;
        $this->name = $pkg->name;
        $this->description = $pkg->description ?? '';
        $this->price = (float)$pkg->price;
        $this->selectedMenus = $pkg->menus->pluck('id')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = ['name' => $this->name, 'description' => $this->description, 'price' => $this->price];

        if ($this->isEditing) {
            $pkg = Package::findOrFail($this->editId);
            $pkg->update($data);
            $pkg->menus()->sync($this->selectedMenus);
            $this->dispatch('notify', message: 'Paket diperbarui!', type: 'success');
        } else {
            $pkg = Package::create($data);
            $pkg->menus()->sync($this->selectedMenus);
            $this->dispatch('notify', message: 'Paket ditambahkan!', type: 'success');
        }

        $this->showModal = false;
        $this->reset(['editId', 'name', 'description', 'price', 'selectedMenus']);
    }

    public function confirmDelete(int $id)
    {
        $pkg = Package::findOrFail($id);
        $this->deleteId = $pkg->id;
        $this->deleteName = $pkg->name;
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

        Package::findOrFail($this->deleteId)->delete();
        $this->dispatch('notify', message: 'Paket dihapus.', type: 'success');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $packages = Package::with('menus')->latest()->get();
        $allMenus = Menu::all();
        return view('livewire.admin.packages', compact('packages', 'allMenus'))->layout('layouts.admin');
    }
}
