<div>
<style>
    .catalog-page-container { 
        padding: 10px 0; 
        max-width: 1200px; 
        margin: 0 auto; 
        width: 100%;
        box-sizing: border-box;
    }

    .catalog-wrapper {
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: flex-start;
        text-align: left;
    }

    .pg-title { font-size: 24px; font-weight: 800; color: #1e2a2d; margin-bottom: 4px; }
    .pg-sub { font-size: 14px; color: #64748b; margin-bottom: 24px; }

    /* Kategori Pil Horizontal */
    .cat-pills-row {
        display: flex; 
        gap: 10px; 
        margin-bottom: 16px; 
        flex-wrap: wrap;
        justify-content: flex-start;
        width: 100%;
        /* Mobile: bisa discroll horizontal kalau kepanjangan */
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .cat-pills-row::-webkit-scrollbar { display: none; }

    .cat-pill-btn {
        padding: 8px 22px; border-radius: 30px; font-size: 13.5px; font-weight: 600;
        border: 1.5px solid #134e4a; background: #ffffff; color: #134e4a;
        cursor: pointer; transition: all 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .cat-pill-btn:hover { background: #f0fdfa; }
    .cat-pill-btn.active {
        background-color: #134e4a; color: #ffffff; border-color: #134e4a;
        box-shadow: 0 4px 12px rgba(19, 78, 74, 0.2);
    }

    /* Filter Rentang Harga Box */
    .budget-filter-box {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        background: #f8fafc;
        padding: 12px 18px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        width: 100%;
        max-width: 600px;
        box-sizing: border-box;
    }
    .budget-input {
        width: 130px;
        padding: 8px 12px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        background: #fff;
    }
    .budget-input:focus { border-color: #134e4a; }
    .btn-reset-budget {
        padding: 8px 14px;
        background: #e2e8f0;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
    }
    .btn-reset-budget:hover { background: #cbd5e1; }

    /* Search bar */
    .catalog-search-wrap { position: relative; max-width: 420px; width: 100%; margin-bottom: 30px; }
    .catalog-search-wrap i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px; }
    .catalog-search-input {
        width: 100%; background: #ffffff; border: 1.5px solid #134e4a;
        border-radius: 30px; padding: 11px 18px 11px 44px;
        font-size: 13.5px; font-family: 'Inter', sans-serif; color: #334155;
        outline: none; box-shadow: 0 0 0 3px rgba(19, 78, 74, 0.08);
        box-sizing: border-box;
    }

    /* Grid Menu */
    .menu-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
        gap: 20px; 
        width: 100%;
        text-align: left;
    }

    .menu-card {
        background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px;
        overflow: hidden; display: flex; flex-direction: column;
        transition: box-shadow .2s, transform .2s;
    }
    .menu-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-2px); }

    .menu-card-img {
        height: 180px; 
        overflow: hidden; background: #f8fafc;
        display: flex; align-items: center; justify-content: center; position: relative;
        padding: 8px; 
    }
    .menu-card-img img { 
        width: 100%; 
        height: 100%; 
        object-fit: contain; 
        transition: transform .3s; 
    }
    .menu-card:hover .menu-card-img img { transform: scale(1.03); }
    .menu-fav-badge {
        position: absolute; top: 10px; left: 10px; background: #f59e0b;
        color: #78350f; font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 20px;
        z-index: 5;
    }

    .menu-card-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .menu-cat-pill {
        display: inline-block; background: #f0fdfa; color: #0f766e;
        font-size: 10.5px; font-weight: 700; padding: 2px 10px; border-radius: 20px; margin-bottom: 6px; width: fit-content;
    }
    .menu-card-body h4 { font-size: 14px; font-weight: 700; color: #1e2a2d; margin: 0 0 4px; cursor: pointer; }
    .menu-card-body h4:hover { color: #134e4a; }
    .menu-card-body p { font-size: 12px; color: #64748b; line-height: 1.5; flex: 1; margin: 0 0 12px; }
    .menu-price { font-size: 14px; font-weight: 800; color: #134e4a; margin: 0 0 12px; }
    .menu-card-actions { display: flex; gap: 8px; position: relative; z-index: 10; }

    .btn-detail-sm {
        flex: 1; padding: 7px; border: 1.5px solid #e2e8f0; border-radius: 9px;
        font-size: 11.5px; font-weight: 600; color: #475569; background: #f8fafc;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;
        transition: all .2s;
    }
    .btn-detail-sm:hover { border-color: #134e4a; color: #134e4a; background: #f0fdfa; }

    .btn-addcart {
        flex: 1; padding: 7px; border: none; border-radius: 9px;
        font-size: 11.5px; font-weight: 700; color: #fff; background: #134e4a;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;
        transition: background .2s;
    }
    .btn-addcart:hover { background: #0f3d39; }

    /* Pagination */
    .pg-nav { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 30px; width: 100%; flex-wrap: wrap; }
    .pg-btn {
        width: 34px; height: 34px; border-radius: 8px; border: 1.5px solid #e2e8f0;
        background: #fff; color: #475569; font-size: 13px; font-weight: 600;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .pg-btn:hover, .pg-btn.active { background: #134e4a; color: #fff; border-color: #134e4a; }
    .pg-btn:disabled { opacity: .4; cursor: not-allowed; }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 200;
        display: flex; align-items: center; justify-content: center; padding: 20px;
        text-align: left;
    }
    .modal-box {
        background: #fff; border-radius: 18px; width: 100%; max-width: 560px;
        max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-box-img { height: 260px; background: #f8fafc; overflow: hidden; display: flex; align-items: center; justify-content: center; border-radius: 18px 18px 0 0; padding: 10px; }
    .modal-box-img img { width: 100%; height: 100%; object-fit: contain; }
    .modal-box-body { padding: 24px; }
    .modal-box-body h3 { font-size: 20px; font-weight: 800; color: #1e2a2d; margin: 8px 0 6px; }
    .modal-box-body .mprice { font-size: 18px; font-weight: 800; color: #134e4a; margin: 0 0 12px; }
    .modal-box-body p { font-size: 13.5px; color: #475569; line-height: 1.7; margin: 0 0 8px; }
    .modal-box-meta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
    .modal-box-meta span { font-size: 12px; background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; }
    .modal-actions { display: flex; gap: 10px; }
    .btn-modal-close { flex: 1; padding: 11px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-weight: 600; color: #475569; background: #fff; cursor: pointer; }
    .btn-modal-add { flex: 2; padding: 11px; border: none; border-radius: 10px; font-size: 13px; font-weight: 700; color: #fff; background: #134e4a; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }

    /* ========================= */
    /* RESPONSIVE - TABLET       */
    /* ========================= */
    @media (max-width: 992px) {
        .menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }
        .catalog-page-container { padding: 10px 16px; }
    }

    /* ========================= */
    /* RESPONSIVE - MOBILE       */
    /* ========================= */
    @media (max-width: 640px) {
        .catalog-page-container { padding: 12px 12px; }

        .pg-title { font-size: 20px; }
        .pg-sub { font-size: 12.5px; margin-bottom: 16px; }

        /* Pil kategori jadi scroll horizontal, tidak wrap agar tetap 1 baris rapi */
        .cat-pills-row {
            flex-wrap: nowrap;
            gap: 8px;
            margin-bottom: 12px;
            padding-bottom: 4px;
        }
        .cat-pill-btn {
            padding: 7px 16px;
            font-size: 12.5px;
        }

        .catalog-search-wrap { max-width: 100%; margin-bottom: 20px; }
        .catalog-search-input { padding: 10px 16px 10px 40px; font-size: 13px; }

        .budget-filter-box {
            padding: 10px 14px;
            max-width: 100%;
        }
        .budget-input { width: 100%; flex: 1 1 100px; }
        .btn-reset-budget { flex-shrink: 0; }

        /* Grid jadi 2 kolom di HP */
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .menu-card-img { height: 120px; padding: 6px; }
        .menu-card-body { padding: 10px; }
        .menu-card-body h4 { font-size: 12.5px; margin-bottom: 3px; }
        .menu-card-body p {
            font-size: 10.5px;
            /* Batasi 2 baris deskripsi supaya card tidak terlalu tinggi/berantakan */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .menu-price { font-size: 12.5px; margin-bottom: 8px; }
        .menu-cat-pill { font-size: 9px; padding: 2px 8px; }

        /* Tombol aksi ditumpuk vertikal biar tidak kepenyet */
        .menu-card-actions {
            flex-direction: column;
            gap: 6px;
        }
        .btn-detail-sm, .btn-addcart {
            width: 100%;
            padding: 8px 6px;
            font-size: 11px;
        }

        .menu-fav-badge { font-size: 9px; padding: 3px 8px; top: 8px; left: 8px; }

        /* Modal full-width di HP */
        .modal-overlay { padding: 0; align-items: flex-end; }
        .modal-box {
            max-width: 100%;
            width: 100%;
            border-radius: 18px 18px 0 0;
            max-height: 92vh;
        }
        .modal-box-img { height: 200px; }
        .modal-box-body { padding: 18px; }
        .modal-box-body h3 { font-size: 17px; }

        .modal-actions { flex-direction: column; }
        .btn-modal-close, .btn-modal-add { flex: none; width: 100%; }

        .pg-btn { width: 30px; height: 30px; font-size: 12px; }
    }

    /* HP sangat kecil (<380px): grid jadi 1 kolom agar tidak terlalu sempit */
    @media (max-width: 380px) {
        .menu-grid {
            grid-template-columns: 1fr;
        }
        .menu-card-img { height: 150px; }
    }
</style>

<div class="catalog-page-container">
    <div class="catalog-wrapper">
        <h2 class="pg-title">Katalog Menu</h2>
        <p class="pg-sub">Berbagai pilihan menu lezat untuk melengkapi acara spesial Anda.</p>

        <!-- Kategori Pil Horizontal & Filter Favorit -->
        <div class="cat-pills-row">
            <button type="button" wire:click="$set('selectedCategory','')"
                    class="cat-pill-btn {{ $selectedCategory==='' && (!isset($filter) || $filter !== 'favorite') ? 'active' : '' }}">
                Semua
            </button>
            <button type="button" wire:click="$set('selectedCategory','nasi box')"
                    class="cat-pill-btn {{ $selectedCategory==='nasi box' ? 'active' : '' }}">
                Nasi Box
            </button>
            <button type="button" wire:click="$set('selectedCategory','tumpeng')"
                    class="cat-pill-btn {{ $selectedCategory==='tumpeng' ? 'active' : '' }}">
                Tumpeng
            </button>
            <button type="button" wire:click="$set('selectedCategory','snack basah')"
                    class="cat-pill-btn {{ $selectedCategory==='snack basah' ? 'active' : '' }}">
                Snack Basah
            </button>
            <button type="button" wire:click="$set('selectedCategory','snack kering')"
                    class="cat-pill-btn {{ $selectedCategory==='snack kering' ? 'active' : '' }}">
                Snack Kering
            </button>
            
            <!-- Tombol Filter Menu Favorit untuk Dosen -->
            <button type="button" wire:click="$set('filter', 'favorite')"
                    class="cat-pill-btn {{ isset($filter) && $filter === 'favorite' ? 'active' : '' }}" style="border-color: #f59e0b; color: {{ isset($filter) && $filter === 'favorite' ? '#fff' : '#d97706' }}; background-color: {{ isset($filter) && $filter === 'favorite' ? '#f59e0b' : '#fff' }};">
                ⭐ Menu Favorit
            </button>
        </div>

        <!-- Kotak Pencarian -->
        <div class="catalog-search-wrap">
            <input type="text" wire:model.live.debounce.300ms="search" class="catalog-search-input" placeholder="Cari menu catering...">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <!-- Product Grid -->
        <div style="width: 100%;">
            @if($menus->isEmpty())
                <div style="text-align:center;padding:60px;background:#fff;border-radius:14px;border:1.5px solid #e2e8f0;">
                    <i class="fa-solid fa-bowl-food" style="font-size:3rem;color:#cbd5e1;margin-bottom:16px;"></i>
                    <h3 style="color:#1e2a2d;">Menu Tidak Ditemukan</h3>
                    <p style="color:#64748b;margin-top:8px;">Coba sesuaikan kata kunci, kategori, atau filter favorit Anda.</p>
                </div>
            @else
                <div class="menu-grid">
                    @foreach($menus as $menu)
                    <div class="menu-card">
                        <div class="menu-card-img" wire:click="showDetail({{ $menu->id }})" style="cursor:pointer;">
                            @if($menu->image)
                                <img src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->name }}">
                            @else
                                <i class="fa-solid fa-bowl-food" style="font-size:3rem;color:#134e4a;"></i>
                            @endif
                            @if($menu->is_favorite)
                                <span class="menu-fav-badge"><i class="fa-solid fa-fire"></i> Favorit</span>
                            @endif
                        </div>
                        <div class="menu-card-body">
                            <span class="menu-cat-pill">{{ is_object($menu->category) ? $menu->category->name : $menu->category }}</span>
                            <h4 wire:click="showDetail({{ $menu->id }})">{{ $menu->name }}</h4>
                            <p>{{ Str::limit($menu->description, 70) }}</p>
                            <div class="menu-price">Rp {{ number_format($menu->price,0,',','.') }}</div>
                            <div class="menu-card-actions">
                                <button type="button" wire:click="showDetail({{ $menu->id }})" class="btn-detail-sm">
                                    <i class="fa-solid fa-circle-info"></i> Detail
                                </button>

                                <button type="button" wire:click="addToCart({{ $menu->id }})" class="btn-addcart" wire:loading.attr="disabled">
                                    <span wire:loading wire:target="addToCart({{ $menu->id }})">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                    <span wire:loading.remove wire:target="addToCart({{ $menu->id }})">
                                        + Keranjang
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(isset($totalPages) && $totalPages > 1)
                <div class="pg-nav">
                    <button type="button" wire:click="previousPage" class="pg-btn" {{ $page<=1 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    @for($i=1; $i<=$totalPages; $i++)
                        <button type="button" wire:click="$set('page',{{ $i }})"
                                class="pg-btn {{ $page===$i ? 'active' : '' }}">{{ $i }}</button>
                    @endfor
                    <button type="button" wire:click="nextPage" class="pg-btn" {{ $page>=$totalPages ? 'disabled' : '' }}>
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Detail Modal -->
@if($detailMenu)
<div class="modal-overlay" wire:click="closeDetail">
    <div class="modal-box" wire:click.stop>
        <div class="modal-box-img">
            @if($detailMenu->image)
                <img src="{{ asset('storage/'.$detailMenu->image) }}" alt="{{ $detailMenu->name }}">
            @else
                <i class="fa-solid fa-bowl-food" style="font-size:5rem;color:#134e4a;"></i>
            @endif
        </div>
        <div class="modal-box-body">
            <span class="menu-cat-pill">{{ is_object($detailMenu->category) ? $detailMenu->category->name : $detailMenu->category }}</span>
            <h3>{{ $detailMenu->name }}</h3>
            <div class="mprice">Rp {{ number_format($detailMenu->price,0,',','.') }}</div>
            <p>{{ $detailMenu->description }}</p>
            <div class="modal-box-meta">
                <span><i class="fa-solid fa-tag"></i> {{ is_object($detailMenu->category) ? $detailMenu->category->name : $detailMenu->category }}</span>
                <span><i class="fa-solid fa-check-circle"></i> Tersedia</span>
            </div>
            <div class="modal-actions">
                <button type="button" wire:click="closeDetail" class="btn-modal-close">← Kembali</button>

                <button type="button" wire:click="addToCart({{ $detailMenu->id }})" class="btn-modal-add" wire:loading.attr="disabled">
                    <span wire:loading wire:target="addToCart({{ $detailMenu->id }})">
                        <i class="fa-solid fa-spinner fa-spin"></i> Memproses...
                    </span>
                    <span wire:loading.remove wire:target="addToCart({{ $detailMenu->id }})">
                        <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
</div>