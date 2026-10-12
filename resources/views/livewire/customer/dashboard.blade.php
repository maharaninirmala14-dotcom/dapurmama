<div>
<style>
    /* ─── PENGATURAN GLOBAL & PENGUNCI LAYAR HP ─── */
    html, body {
        max-width: 100vw !important;
        overflow-x: hidden !important;
        box-sizing: border-box;
    }

    .dashboard-container {
        padding: 24px 30px 40px 30px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box;
        overflow-x: hidden !important;
    }

    main, .max-w-7xl, .container, .mx-auto {
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
    }

    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 28px;
        align-items: start;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(120deg, #e6f7f5 0%, #ccf2ed 100%);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 30px 40px;
        margin-bottom: 28px;
        box-sizing: border-box;
        width: 100%;
    }

    .hero-text {
        z-index: 2;
        max-width: 55%;
    }

    .hero-text h2 {
        font-size: 28px;
        font-weight: 800;
        color: #134e4a;
        margin: 0 0 8px;
        line-height: 1.2;
    }

    .hero-text p {
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
        margin: 0 0 22px;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #134e4a;
        color: #fff;
        padding: 11px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s, transform .15s;
    }

    .hero-btn:hover {
        background: #0f3d39;
        transform: translateY(-1px);
    }

    .hero-img-container {
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 40%;
    }

    /* Category row */
    .cat-row {
        display: flex;
        gap: 12px;
        margin-bottom: 32px;
        flex-wrap: wrap;
        width: 100%;
        box-sizing: border-box;
    }

    .cat-card {
        flex: 1;
        min-width: 110px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all .2s ease;
        cursor: pointer;
        box-sizing: border-box;
    }

    .cat-card:hover {
        border-color: #14b8a6;
        box-shadow: 0 4px 14px rgba(20,184,166,.15);
        transform: translateY(-2px);
    }

    .cat-icon {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }

    .cat-card h4 {
        font-size: 12.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        text-align: center;
    }

    .cat-card p {
        font-size: 11px;
        color: #94a3b8;
        margin: 0;
        text-align: center;
        line-height: 1.4;
    }

    /* Section headers */
    .section-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 16px;
        width: 100%;
        box-sizing: border-box;
    }

    .section-title {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px;
    }

    .see-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        padding: 7px 16px;
        border-radius: 8px;
        white-space: nowrap;
        transition: all .2s;
    }

    .see-all-btn:hover {
        border-color: #14b8a6;
        color: #0f766e;
        background: #f0fdfa;
    }

    /* Product cards (Grid Rekomendasi) */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 36px;
        width: 100%;
        box-sizing: border-box;
    }

    .product-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: box-shadow .2s, transform .2s;
        box-sizing: border-box;
    }

    .product-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,.1);
        transform: translateY(-3px);
    }

    .product-img {
        position: relative;
        height: 160px;
        background: #f8fafc;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform .3s;
    }

    .product-card:hover .product-img img {
        transform: scale(1.05);
    }

    .badge-best {
        position: absolute;
        top: 10px; left: 10px;
        background: #f59e0b;
        color: #78350f;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: .3px;
        z-index: 5;
    }

    .product-body {
        padding: 14px;
        flex: 1;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
    }

    .product-body h4 {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px;
    }

    .product-price {
        font-size: 13px;
        font-weight: 700;
        color: #0f766e;
        margin: 0 0 6px;
    }

    .product-desc {
        font-size: 11.5px;
        color: #94a3b8;
        line-height: 1.5;
        flex: 1;
        margin: 0 0 14px;
    }

    .product-actions {
        display: flex;
        gap: 8px;
    }

    .btn-tambah {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 10px;
        border: 1.5px solid #134e4a;
        border-radius: 9px;
        color: #134e4a;
        font-size: 12.5px;
        font-weight: 600;
        background: #fff;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }

    .btn-tambah:hover {
        background: #f0fdfa;
    }

    .btn-cart {
        width: 36px; height: 36px;
        background: #134e4a;
        border: none;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 13px;
        cursor: pointer;
        transition: background .2s;
        flex-shrink: 0;
    }

    .btn-cart:hover {
        background: #0f3d39;
    }

    /* Orders table - Hanya bagian tabel ini yang bisa digeser horizontal */
    .orders-section {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-sizing: border-box;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 550px;
    }

    .orders-table thead tr {
        background: #f8fafc;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .orders-table th {
        padding: 13px 18px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .orders-table td {
        padding: 13px 18px;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }

    .orders-table tbody tr:last-child td {
        border-bottom: none;
    }

    .orders-table tbody tr:hover td {
        background: #f8fafc;
    }

    .orders-table .order-id {
        font-weight: 700;
        color: #1e293b;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .status-diproses {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .status-selesai {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        color: #0f766e;
        text-decoration: none;
        border: 1.5px solid #99f6e4;
        background: #f0fdfa;
        padding: 5px 12px;
        border-radius: 7px;
        transition: all .2s;
    }

    .btn-detail:hover {
        background: #ccfbf1;
    }

    /* ─── RIGHT SIDEBAR ─── */
    .right-sidebar {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
        box-sizing: border-box;
    }

    .sidebar-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .profile-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-avatar {
        width: 76px; height: 76px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 12px;
        border: 3px solid #e2e8f0;
    }

    .profile-name {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px;
    }

    .profile-email {
        font-size: 12px;
        color: #94a3b8;
        margin: 0 0 14px;
    }

    .btn-edit-profile {
        display: block;
        width: 100%;
        text-align: center;
        padding: 9px;
        border: 1.5px solid #e2e8f0;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        transition: all .2s;
        box-sizing: border-box;
    }

    .btn-edit-profile:hover {
        border-color: #14b8a6;
        color: #0f766e;
        background: #f0fdfa;
    }

    .bantuan-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
    }

    .bantuan-desc {
        font-size: 12px;
        color: #94a3b8;
        margin: 0 0 14px;
        line-height: 1.5;
    }

    .btn-wa {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        text-decoration: none;
        transition: all .2s;
        box-sizing: border-box;
    }

    .btn-wa:hover {
        border-color: #22c55e;
        color: #15803d;
        background: #f0fdf4;
    }

    .promo-card {
        background: linear-gradient(135deg, #134e4a 0%, #065f46 100%);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .promo-card .promo-desc {
        font-size: 12px;
        color: rgba(255,255,255,.75);
        margin: 0 0 16px;
        line-height: 1.5;
        position: relative; z-index: 2;
    }

    .btn-ikuti {
        display: inline-block;
        background: #f59e0b;
        color: #78350f;
        font-size: 12.5px;
        font-weight: 700;
        padding: 9px 20px;
        border-radius: 9px;
        text-decoration: none;
        transition: background .2s;
        position: relative; z-index: 2;
    }

    .btn-ikuti:hover {
        background: #fbbf24;
    }

    .promo-card .promo-icon {
        position: absolute;
        bottom: -10px; right: -6px;
        font-size: 80px;
        color: rgba(255,255,255,.08);
        transform: rotate(12deg);
        z-index: 1;
    }

    /* ─── MEDIA QUERY MOBILE / TABLET ─── */
    @media (max-width: 1024px) {
        .dash-grid {
            grid-template-columns: 1fr !important;
        }
        .dashboard-container {
            padding: 16px 15px 30px 15px !important;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 10px 10px 20px 10px !important;
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }
        .hero-banner {
            flex-direction: column-reverse;
            padding: 16px;
            text-align: center;
            gap: 12px;
            min-height: auto;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        .hero-text {
            max-width: 100% !important;
            width: 100%;
        }
        .hero-text h2 {
            font-size: 20px;
        }
        .hero-text p {
            font-size: 12.5px;
            margin-bottom: 14px;
        }
        .hero-img-container {
            max-width: 100% !important;
            width: 100%;
        }
        .hero-img-container img {
            width: 110px !important;
            height: 110px !important;
            border-width: 3px !important;
        }
        .hero-btn {
            width: 100%;
            justify-content: center;
            box-sizing: border-box;
        }
        .cat-row {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 6px;
            -webkit-overflow-scrolling: touch;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }
        .cat-card {
            min-width: 85px;
            flex: 0 0 auto;
            padding: 10px 6px;
        }
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }
        .product-card {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }
        .product-img {
            height: 110px;
        }
        .product-body {
            padding: 10px;
        }
        .product-body h4 {
            font-size: 12px;
        }
        .product-price {
            font-size: 11.5px;
        }
        .product-desc {
            display: none; 
        }
        .section-header {
            align-items: center;
            width: 100% !important;
            max-width: 100% !important;
        }
        .section-title {
            font-size: 14px;
        }
        .see-all-btn {
            font-size: 11px;
            padding: 5px 10px;
        }
        .right-sidebar {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        .sidebar-card {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
    }
</style>

<div>
<div class="dashboard-container">

<div class="dash-grid">

    <!-- ── LEFT / MAIN COLUMN ── -->
    <div style="width: 100%; max-width: 100%; min-width: 0; box-sizing: border-box; overflow-x: hidden;">

        <!-- Hero Banner -->
        <div class="hero-banner">
            <div class="hero-text">
                <h2>Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                <p>
                    Mau pesan catering apa hari ini?<br>
                    Nikmati hidangan lezat dari Dapur Mama Delisha<br>
                    untuk setiap momen spesial Anda.
                </p>
                <a href="{{ route('catalog') }}" class="hero-btn">
                    Lihat Katalog Menu &rarr;
                </a>
            </div>
            <!-- Logo Bundar Estetik -->
            <div class="hero-img-container">
                <img
                    src="{{ asset('images/image.png') }}"
                    alt="Logo Dapur Mama Delisha"
                    style="width: 210px; height: 210px; border-radius: 50%; object-fit: cover; border: 5px solid #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.15);"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';">
            </div>
        </div>

        <!-- Category Row -->
        <div class="cat-row">
            <a href="{{ route('catalog') }}?category=nasi-box" class="cat-card">
                <div class="cat-icon" style="background:#fff3e0;color:#e65100;">
                    <i class="fa-solid fa-box"></i>
                </div>
                <h4>Nasi Box</h4>
                <p>Aneka pilihan</p>
            </a>
            <a href="{{ route('catalog') }}?category=snack-box" class="cat-card">
                <div class="cat-icon" style="background:#e8f5e9;color:#2e7d32;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <h4>Snack Box</h4>
                <p>Snack lezat</p>
            </a>
            <a href="{{ route('catalog') }}?category=tumpeng" class="cat-card">
                <div class="cat-icon" style="background:#ede7f6;color:#6a1b9a;">
                    <i class="fa-solid fa-mountain"></i>
                </div>
                <h4>Tumpeng</h4>
                <p>Untuk acara</p>
            </a>
            <a href="{{ route('packages') }}" class="cat-card">
                <div class="cat-icon" style="background:#fffde7;color:#f57f17;">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h4>Paket Hemat</h4>
                <p>Lebih hemat</p>
            </a>
            <a href="{{ route('catalog') }}" class="cat-card">
                <div class="cat-icon" style="background:#fce4ec;color:#c62828;">
                    <i class="fa-solid fa-ellipsis"></i>
                </div>
                <h4>Lainnya</h4>
                <p>Menu lainnya</p>
            </a>
        </div>

        <!-- Section Header Produk Rekomendasi -->
        <div class="section-header">
            <div>
                <p class="section-title">Rekomendasi Menu Pilihan</p>
            </div>
            <a href="{{ route('catalog') }}" class="see-all-btn">
                Semua Menu &rarr;
            </a>
        </div>

        <div class="product-grid">
            @forelse($recommendedMenus ?? [] as $menu)
            <div class="product-card">
                <div class="product-img">
                    @if($menu->image)
                        <img src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->name }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1588168333986-5d6e275f15ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="{{ $menu->name }}">
                    @endif
                    @if(isset($menu->is_favorite) && $menu->is_favorite)
                        <span class="badge-best">Best Seller</span>
                    @endif
                </div>
                <div class="product-body">
                    <h4>{{ $menu->name }}</h4>
                    <p class="product-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    <p class="product-desc">{{ Str::limit($menu->description, 55) }}</p>
                    <div class="product-actions">
                        <button wire:click="addToCart({{ $menu->id }})" class="btn-tambah">+ Tambah</button>
                        <button wire:click="addToCart({{ $menu->id }})" class="btn-cart"><i class="fa-solid fa-cart-plus"></i></button>
                    </div>
                </div>
            </div>
            @empty
    
            @endforelse
        </div>

        <!-- Orders Table Section -->
        <div class="section-header" style="margin-bottom:14px;">
            <p class="section-title">Pesanan Terakhir Anda</p>
            <a href="{{ route('customer.orders') }}" class="see-all-btn">
                Lihat Semua
            </a>
        </div>

        <div class="orders-section">
            <div class="table-responsive-wrapper">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal Acara</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td class="order-id">{{ $order->order_code ?? '#PS-'.$order->id }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">{{ $order->delivery_time }}</div>
                            </td>
                            <td style="font-weight: 700; color: #1e2a2d;">
                                Rp {{ number_format($order->grand_total ?? (($order->total_price ?? 0) + ($order->shipping_fee ?? 0)), 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="status-pill {{ ($order->status == 'selesai' || $order->status == 'completed') ? 'status-selesai' : 'status-diproses' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('customer.orders') }}" class="btn-detail">Detail &rarr;</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 24px; color: #94a3b8;">Belum ada riwayat pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /left column -->

    <!-- ── RIGHT SIDEBAR ── -->
    <div class="right-sidebar">

        <!-- Profile Card -->
        <div class="sidebar-card profile-card">
            <img
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=134e4a&color=fff&bold=true&size=128"
                alt="Avatar"
                class="profile-avatar">
            <p class="profile-name">{{ Auth::user()->name }}</p>
            <p class="profile-email">{{ Auth::user()->email }}</p>
            <a href="{{ route('customer.profile') }}" class="btn-edit-profile">Edit Profil</a>
        </div>

        <!-- Bantuan Card -->
        <div class="sidebar-card">
            <p class="bantuan-title">Butuh Bantuan?</p>
            <p class="bantuan-desc">Kami siap membantu Anda kapan saja untuk pemesanan.</p>
            <a href="https://wa.me/" target="_blank" class="btn-wa">
                <i class="fa-brands fa-whatsapp" style="color:#22c55e;font-size:16px;"></i>
                Hubungi Kami
            </a>
        </div>

        <!-- Promo Card -->
        <div class="sidebar-card promo-card">
            <p class="promo-desc">Ikuti media sosial kami untuk mendapatkan info promo terbaru dari Dapur Mama Delisha.</p>
            <a href="#" class="btn-ikuti">Ikuti Sekarang</a>
            <i class="fa-solid fa-gift promo-icon"></i>
        </div>

    </div><!-- /right sidebar -->

</div><!-- /dash-grid -->

<!-- Footer -->
<p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:32px;padding-top:20px;border-top:1px solid #e2e8f0;">
    &copy; 2026 Dapur Mama Delisha. All rights reserved.
</p>

</div>
</div>
</div>