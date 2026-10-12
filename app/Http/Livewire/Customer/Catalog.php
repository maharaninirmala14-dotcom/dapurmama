<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Category;

class Catalog extends Component
{
    public string $search = '';
    public string $selectedCategory = '';
    public string $filter = ''; // Menangkap filter khusus (misal: 'favorite')
    public $minPrice = null; 
    public $maxPrice = null; 
    public int $page = 1;
    public int $perPage = 8; // Diatur ke 8 agar grid 4 kolom terisi penuh (4 item per baris)
    public ?Menu $detailMenu = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['as' => 'category', 'except' => ''],
        'filter' => ['except' => ''],
        'minPrice' => ['as' => 'min_price', 'except' => ''],
        'maxPrice' => ['as' => 'max_price', 'except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->selectedCategory = request()->query('category', request()->query('selectedCategory', $this->selectedCategory));
        $this->filter = request()->query('filter', $this->filter);
        $this->minPrice = request()->query('min_price', $this->minPrice);
        $this->maxPrice = request()->query('max_price', $this->maxPrice);
        $this->page = max(1, (int) request()->query('page', 1));
    }

    public function updatedSearch() { $this->page = 1; }
    public function updatedSelectedCategory() { $this->page = 1; $this->filter = ''; } // Reset filter favorit jika kategori diklik
    public function updatedFilter() { $this->page = 1; $this->selectedCategory = ''; } // Reset kategori jika filter favorit diklik
    public function updatedMinPrice() { $this->page = 1; }
    public function updatedMaxPrice() { $this->page = 1; }

    public function resetPriceFilter()
    {
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->page = 1;
    }

    public function showDetail(int $menuId): void
    {
        $this->detailMenu = Menu::with('category')->find($menuId);
    }

    public function closeDetail(): void
    {
        $this->detailMenu = null;
    }

    public function addToCart($id)
    {
        $menuId = (int) $id;
        $menu = Menu::with('category')->find($menuId);
        
        if (!$menu) {
            $this->dispatch('notify', message: 'Menu tidak ditemukan.', type: 'error');
            return;
        }

        $cart = session()->get('cart', []);
        $cartKey = 'menu_' . $menu->id;

        $categoryName = $menu->category ? strtolower(trim($menu->category->name)) : '';
        $categorySlug = $menu->category ? strtolower(trim($menu->category->slug)) : '';
        
        $minQty = 1; 
        if (str_contains($categoryName, 'nasi box') || str_contains($categoryName, 'nasi') || $categorySlug === 'nasi-box') {
            $minQty = 20; 
        } elseif (str_contains($categoryName, 'snack basah') || str_contains($categoryName, 'basah') || $categorySlug === 'snack-basah') {
            $minQty = 20; 
        } elseif (str_contains($categoryName, 'snack kering') || str_contains($categoryName, 'kering') || $categorySlug === 'snack-kering') {
            $minQty = 1;
        } elseif (str_contains($categoryName, 'minuman') || $categorySlug === 'minuman') {
            $minQty = 20;
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += 1;
        } else {
            $cart[$cartKey] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'type' => 'menu',
                'qty' => max(1, $minQty),
                'image' => $menu->image,
                'category_name' => $menu->category ? $menu->category->name : 'Umum',
                'min_qty' => $minQty,
            ];
        }

        session()->put('cart', $cart);
        session()->save(); 

        $totalQty = array_sum(array_column($cart, 'qty'));
        $this->js("Alpine.store('cart').count = {$totalQty}");
        $this->dispatch('notify', message: "\"{$menu->name}\" berhasil ditambahkan ke keranjang!", type: 'success');

        // agar pop-up detail langsung tertutup otomatis setelah produk dimasukkan ke keranjang
        $this->detailMenu = null; 
    }

    public function nextPage(): void { $this->page++; }
    public function previousPage(): void { if ($this->page > 1) { $this->page--; } }

    public function render()
    {
        $categories = Category::where('slug', '!=', 'prasmanan')
                            ->where('name', 'not like', '%prasmanan%')
                            ->get();
                            
        $query = Menu::query()->with('category');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filter khusus untuk Menu Favorit
        if ($this->filter === 'favorite') {
            $query->where('is_favorite', true);
        }

        if (!empty($this->selectedCategory)) {
            $filterCat = strtolower(trim($this->selectedCategory));
            $query->whereHas('category', function ($q) use ($filterCat) {
                if ($filterCat === 'snack basah') {
                    $q->where('slug', 'like', '%basah%')->orWhere('name', 'like', '%basah%');
                } elseif ($filterCat === 'snack kering') {
                    $q->where('slug', 'like', '%kering%')->orWhere('name', 'like', '%kering%');
                } else {
                    $q->where('slug', $filterCat)->orWhere('slug', str_replace(' ', '-', $filterCat))->orWhere('name', 'like', '%' . $filterCat . '%');
                }
            });
        }

        $min = ($this->minPrice !== '' && $this->minPrice !== null) ? preg_replace('/[^0-9]/', '', $this->minPrice) : '';
        $max = ($this->maxPrice !== '' && $this->maxPrice !== null) ? preg_replace('/[^0-9]/', '', $this->maxPrice) : '';

        if ($min !== '' && $max !== '') {
            $query->whereBetween('price', [(float)$min, (float)$max]);
        } elseif ($min !== '') {
            $query->where('price', '>=', (float)$min);
        } elseif ($max !== '') {
            $query->where('price', '<=', (float)$max);
        }

        $query->whereDoesntHave('category', function($q) {
            $q->where('slug', 'prasmanan')->orWhere('name', 'like', '%prasmanan%');
        });

        $totalItems = $query->count();
        $totalPages = (int) ceil($totalItems / $this->perPage);
        if ($totalPages < 1) { $totalPages = 1; }
        if ($this->page > $totalPages) { $this->page = $totalPages; }

        $menus = $query->latest()
                       ->skip(($this->page - 1) * $this->perPage)
                       ->take($this->perPage)
                       ->get();

        return view('livewire.customer.catalog', [
            'menus' => $menus,
            'categories' => $categories,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'selectedCategory' => $this->selectedCategory,
            'filter' => $this->filter,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'page' => $this->page,
            'detailMenu' => $this->detailMenu
        ])->layout('layouts.customer');
    }
}