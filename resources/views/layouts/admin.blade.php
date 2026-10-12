<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dapur Mama Delisha - Admin Panel</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Internal Global & Admin Layout (Responsive Mobile Fix) -->
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #2b5352 !important;
            --primary-dark: #1e3d3c !important;
            --success: #38a169;
            --border-color: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        }

        aside, .admin-sidebar {
            background-color: var(--primary) !important;
        }

        .table-panel { background: #ffffff; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 24px; box-shadow: var(--shadow-sm); overflow-x: auto; }
        .table-search-input { width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-size: 0.9rem; outline: none; box-sizing: border-box; }
        .table-search-input:focus { border-color: var(--primary); }

        .btn-primary { background-color: var(--primary); color: white; border: none; border-radius: var(--radius-sm); font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-primary:hover { background-color: var(--primary-dark); }

        .admin-table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 16px; min-width: 600px; }
        .admin-table th { background-color: #f8fafc; color: var(--primary); font-size: 0.82rem; font-weight: 700; padding: 12px 14px; border-bottom: 2px solid var(--border-color); text-transform: uppercase; letter-spacing: 0.03em; }
        .admin-table td { padding: 14px; font-size: 0.88rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; color: var(--text-main); }
        .admin-table tbody tr:hover { background-color: #f8fafc; }

        .action-btns { display: flex; gap: 6px; align-items: center; }
        .action-btn { padding: 6px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid var(--border-color); background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; }
        .action-btn.edit { border-color: #cbd5e1; color: #2b6cb0; }
        .action-btn.edit:hover { background: #ebf8ff; }
        .action-btn.delete { border-color: #feb2b2; color: #c53030; }
        .action-btn.delete:hover { background: #fff5f5; }

        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #e6fffa; color: #234e52; }

        .pagination-btn { padding: 6px 12px; border: 1px solid var(--border-color); background: #fff; border-radius: var(--radius-sm); font-size: 0.85rem; cursor: pointer; color: var(--text-main); }
        .pagination-btn:hover { background: #f1f5f9; }

        /* Styling Modal */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 1050; padding: 20px; }
        .modal-content { background: #ffffff; border-radius: var(--radius-md); width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow: hidden; animation: modalPop 0.2s ease-out; }
        @keyframes modalPop { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: #f8fafc; }
        .modal-body { padding: 20px; max-height: 75vh; overflow-y: auto; }
        .modal-footer { padding: 14px 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; }
        .modal-close { background: transparent; border: none; font-size: 1.25rem; cursor: pointer; color: var(--text-muted); }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-size: 0.9rem; outline: none; box-sizing: border-box; }
        .form-control:focus { border-color: var(--primary); }
        .form-error { font-size: 0.75rem; color: #e53e3e; margin-top: 4px; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; width: 100%; margin-bottom: 24px; }
        .dashboard-grid, .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; width: 100%; margin-bottom: 24px; }

        /* Kelas Responsive Mobile */
        .mobile-hamburger { display: none; background: none; border: none; font-size: 1.3rem; color: #1e293b; cursor: pointer; padding: 5px; }
        .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; }

        /* Kontrol Tampilan Selamat Datang Berdasarkan Layar */
        .welcome-text-in-content {
            display: none; /* Sembunyikan di Desktop/Laptop */
            margin-bottom: 20px;
        }

        @media (max-width: 992px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .admin-layout-wrapper {
                flex-direction: row !important; 
            }
            
            .admin-sidebar {
                position: fixed !important;
                top: 0;
                left: 0;
                height: 100vh !important;
                width: 260px !important;
                max-width: 75vw !important; 
                z-index: 1000 !important;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .admin-sidebar.sidebar-is-open {
                transform: translateX(0);
            }

            /* Header Bersih & Sticky */
            .admin-header {
                position: sticky !important;
                top: 0 !important;
                z-index: 900 !important;
                background: #fff !important;
                height: 70px !important; 
                padding: 0 15px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            
            .mobile-hamburger { display: block; }
            
            /* Sembunyikan Jam, Teks Profil, & Teks Welcome Lama dari Header */
            .clock-widget, .profile-text, .welcome-text-header { 
                display: none !important; 
            }

            /* Munculkan Teks Welcome di dalam Konten Utama khusus Mobile */
            .welcome-text-in-content {
                display: block !important;
            }

            .admin-main-content {
                padding: 15px !important;
                width: 100% !important;
                overflow-x: hidden;
            }

            .stats-grid, .dashboard-grid, .bottom-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    @livewireStyles
</head>
<body style="margin: 0; font-family: 'Inter', sans-serif; background-color: #fbf9f6; overflow-x: hidden;">

    <div class="admin-layout-wrapper" x-data="{ sidebarOpen: false }" style="display: flex; min-height: 100vh; position: relative;">
        
        <div class="sidebar-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition style="display: none;"></div>

        <!-- SIDEBAR KIRI -->
        <aside class="admin-sidebar" :class="sidebarOpen ? 'sidebar-is-open' : ''" style="width: 260px; background-color: #2b5352; color: #fff; display: flex; flex-direction: column; flex-shrink: 0; position: fixed; height: 100vh; z-index: 1001; overflow-y: auto;">
            <div style="padding: 20px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="background: #f0fdfa; color: #134e4a; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1.5px solid #99f6e4; overflow: hidden; flex-shrink: 0;">
                        <img src="{{ asset('images/Logo Dapur Mama Delisha.jpeg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('images/image.png') }}';">
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Dapur</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #fff; line-height: 1.2;">Mama Delisha</div>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="mobile-hamburger" style="color: white; margin-left: 10px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav style="padding: 20px 12px; flex: 1; display: flex; flex-direction: column; gap: 4px; overflow-y: auto;">
                <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.dashboard') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-house" style="width: 20px;"></i> Dashboard
                </a>
                <a href="{{ route('admin.categories') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.categories') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-layer-group" style="width: 20px;"></i> Kategori
                </a>
                <a href="{{ route('admin.menus') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.menus*') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-book-open" style="width: 20px;"></i> Menu
                </a>
                <a href="{{ route('admin.customers') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.customers') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-users" style="width: 20px;"></i> Pelanggan
                </a>
                <a href="{{ route('admin.orders') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.orders*') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-receipt" style="width: 20px;"></i> Pesanan
                </a>
                <a href="{{ route('admin.transactions') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.transactions') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-wallet" style="width: 20px;"></i> Transaksi
                </a>
                <a href="{{ route('admin.reports') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.reports') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-chart-column" style="width: 20px;"></i> Laporan
                </a>
                <a href="{{ route('admin.apriori') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; border-radius: 8px; font-size: 0.9rem; {{ request()->routeIs('admin.apriori*') ? 'background: #ffffff; color: #2b5352; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'color: #cbd5e1; font-weight: 500;' }}">
                    <i class="fa-solid fa-brain" style="width: 20px;"></i> Apriori
                </a>
            </nav>
        </aside>

        <!-- KONTEN UTAMA DI KANAN -->
        <div style="flex: 1; display: flex; flex-direction: column; min-width: 0; margin-left: 0;" class="main-content-wrapper">
            <style>
                @media (min-width: 769px) {
                    .main-content-wrapper {
                        margin-left: 260px !important;
                    }
                }
            </style>
            
            <!-- NAVBAR ATAS (BERSIH & STICKY DI HP) -->
            <header class="admin-header" style="background: #fff; height: 85px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; padding: 0 35px; box-sizing: border-box;">
                
                <!-- SISI KIRI -->
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button @click="sidebarOpen = !sidebarOpen" class="mobile-hamburger">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    
                    <!-- Welcome text untuk versi Desktop (Asli) -->
                    <div class="welcome-text-header">
                        <div style="font-size: 1.15rem; font-weight: 800; color: #1e293b; letter-spacing: -0.3px;">Selamat datang, Admin!</div>
                        <div style="font-size: 0.85rem; color: #64748b; margin-top: 2px;">Kelola sistem pemesanan catering dengan mudah dan efektif.</div>
                    </div>
                </div>

                <!-- SISI KANAN -->
                <div style="display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                    
                    <div class="clock-widget" style="display: flex; align-items: center; gap: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 30px;">
                        <div style="width: 32px; height: 32px; background: #e6fffa; color: #2b5352; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div style="text-align: right;">
                            <div id="realtime-date" style="font-size: 0.75rem; font-weight: 700; color: #1e293b; line-height: 1.2;">Memuat tanggal...</div>
                            <div id="realtime-clock" style="font-size: 0.72rem; font-weight: 600; color: #2b5352; line-height: 1.2;">00:00:00 WIB</div>
                        </div>
                    </div>

                    @php
                        $unreadCount = \App\Models\Order::where('is_read', false)->count();
                        $recentOrders = \App\Models\Order::where('is_read', false)->latest()->take(5)->get();
                    @endphp

                    <!-- Lonceng Notifikasi -->
                    <div class="relative" x-data="{ openNotif: false }" style="position: relative;">
                        <button @click="openNotif = !openNotif" style="background: #f8fafc; border: 1px solid #e2e8f0; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #475569; position: relative;">
                            <i class="fa-solid fa-bell" style="font-size: 1.05rem;"></i>
                            @if($unreadCount > 0)
                                <span style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 700; padding: 2px 6px; border-radius: 50px; border: 2px solid #fff;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="openNotif" @click.away="openNotif = false" style="position: absolute; right: 0; top: 52px; width: 320px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 15px; z-index: 100; display: none;" x-transition>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                                <span style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">Notifikasi Pesanan Masuk</span>
                                @if($unreadCount > 0)
                                    <span style="font-size: 0.72rem; background: #e6fffa; color: #234e52; padding: 2px 8px; border-radius: 10px; font-weight: 600;">{{ $unreadCount }} Baru</span>
                                @endif
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 8px; max-height: 240px; overflow-y: auto;">
                                @forelse($recentOrders as $order)
                                    <a href="{{ route('admin.notification.read', $order->id) }}" style="text-decoration: none; padding: 10px; border-radius: 8px; background: #f8fafc; display: block;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                            <span style="font-size: 0.82rem; font-weight: 700; color: #1e293b;">{{ $order->order_code ?? 'Pesanan #' . $order->id }}</span>
                                            <span style="font-size: 0.7rem; color: #64748b;">{{ $order->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div style="font-size: 0.78rem; color: #475569;">
                                            Pesanan baru dari <strong>{{ $order->user->name ?? 'Customer' }}</strong>
                                        </div>
                                    </a>
                                @empty
                                    <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 0.85rem;">
                                        <i class="fa-solid fa-bell-slash" style="display: block; font-size: 1.5rem; margin-bottom: 8px;"></i>
                                        Tidak ada pesanan baru.
                                    </div>
                                @endforelse
                            </div>

                            <div style="border-top: 1px solid #f1f5f9; margin-top: 10px; padding-top: 8px; text-align: center;">
                                <a href="{{ route('admin.orders') }}" style="font-size: 0.8rem; font-weight: 700; color: #2b5352; text-decoration: none;">Lihat Semua Pesanan &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- Profil Admin -->
                    <div class="relative" x-data="{ openProfile: false }" style="position: relative;">
                        <div @click="openProfile = !openProfile" style="display: flex; align-items: center; gap: 10px; border: 1px solid #e2e8f0; padding: 6px 14px 6px 6px; border-radius: 30px; cursor: pointer; background: #ffffff;">
                            <div style="width: 40px; height: 40px; background: #2b5352; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.88rem;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 2)) }}
                            </div>
                            <div class="profile-text">
                                <div style="font-size: 0.88rem; font-weight: 700; color: #1e293b; line-height: 1.2;">{{ Auth::user()->name ?? 'Admin Dapur' }}</div>
                                <div style="font-size: 0.72rem; color: #64748b; line-height: 1.2;">Administrator</div>
                            </div>
                            <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; color: #64748b; margin-left: 4px;"></i>
                        </div>

                        <div x-show="openProfile" @click.away="openProfile = false" style="position: absolute; right: 0; top: 54px; width: 200px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 8px; z-index: 100; display: none;" x-transition>
                            <a href="{{ route('admin.settings') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; text-decoration: none; color: #334155; font-size: 0.85rem; border-radius: 8px;">
                                <i class="fa-solid fa-gear" style="color: #64748b; width: 16px;"></i> Pengaturan Akun
                            </a>
                            <div style="height: 1px; background: #f1f5f9; margin: 4px 0;"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" style="background: transparent; border: none; width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #dc2626; font-size: 0.85rem; font-weight: 600; border-radius: 8px; cursor: pointer; text-align: left;">
                                    <i class="fa-solid fa-right-from-bracket" style="width: 16px;"></i> Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Bagian Isi Halaman -->
            <main class="admin-main-content" style="padding: 20px 15px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden !important;">
                
                <!-- Welcome text khusus versi Mobile (Tampil rapi di dalam konten bagian atas) -->
                <div class="welcome-text-in-content">
                    <div style="font-size: 1.1rem; font-weight: 800; color: #1e293b; letter-spacing: -0.3px;">Selamat datang, Admin!</div>
                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">Kelola sistem pemesanan catering dengan mudah dan efektif.</div>
                </div>

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateRealtimeClock() {
            const now = new Date();
            const optionsDate = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', optionsDate);
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds} WIB`;
            
            document.getElementById('realtime-date').textContent = dateString;
            document.getElementById('realtime-clock').textContent = timeString;
        }

        updateRealtimeClock();
        setInterval(updateRealtimeClock, 1000);
    </script>

    @livewireScripts
</body>
</html>