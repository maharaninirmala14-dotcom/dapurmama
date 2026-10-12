<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Dapur Mama Delisha')); ?> - Customer Dashboard</title>

    <!-- Tailwind CSS (via Vite) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 270px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 40;
            box-shadow: 2px 0 12px rgba(0,0,0,.06);
            transition: transform 0.3s ease;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 18px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-logo .logo-icon {
            width: 42px; height: 42px;
            background: #f0fdfa;
            border: 1.5px solid #99f6e4;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-logo .logo-text {
            font-size: 13px;
            font-weight: 700;
            color: #134e4a;
            line-height: 1.3;
            white-space: nowrap;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            text-decoration: none;
            transition: all .2s ease;
            position: relative;
        }

        .menu-item:hover {
            background: #f0fdfa;
            color: #134e4a;
        }

        .menu-item.active {
            background: #134e4a;
            color: #fff;
            font-weight: 600;
        }

        .menu-item.active i {
            color: #fff;
        }

        .menu-item i {
            width: 18px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
            color: #94a3b8;
        }

        .menu-item:hover i {
            color: #134e4a;
        }

        /* ── Main content area ── */
        .main-content {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin 0.3s ease;
        }

        /* ── Topbar ── */
        .topbar {
            height: 76px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 1px 6px rgba(0,0,0,.04);
        }

        .topbar-center {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #334155;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar-icon-btn {
            position: relative;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }

        .topbar-icon-btn:hover {
            background: #f0fdfa;
            border-color: #1e5d5c;
            color: #1e5d5c;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            cursor: pointer;
            transition: all .2s;
            background: #fff;
        }

        .user-chip:hover {
            border-color: #1e5d5c;
            background: #f0fdfa;
        }

        .user-chip img {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-chip-info p:first-child {
            font-size: 13px;
            font-weight: 600;
            color: #1e2a2d;
            margin: 0;
        }

        .user-chip-info p:last-child {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }

        .page-content {
            padding: 28px 28px 40px;
            flex: 1;
        }

        /* Tombol Toggle Menu Mobile */
        .mobile-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #134e4a;
            cursor: pointer;
            padding: 8px;
        }

        /* ── Responsif Media Queries untuk HP / Mobile ── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
            }
            .topbar {
                padding: 0 16px;
                height: 65px;
            }
            .mobile-toggle-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .topbar-center {
                font-size: 0.8rem;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                max-width: 150px;
            }
            .topbar-center span:nth-child(3) {
                display: none; /* Sembunyikan sapaan panjang di HP kecil */
            }
            .topbar-right {
                gap: 10px !important;
            }
            .page-content {
                padding: 16px;
            }
        }
    </style>

    <!-- Alpine Global Store untuk Keranjang -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                count: <?php echo e(collect(session('cart', []))->sum('qty')); ?>,
                set(n) { this.count = Number(n) || 0; },
                clear() { this.count = 0; }
            });
        });

        // Sinkronkan badge keranjang saat ada perubahan dari Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('cartUpdated', (payload) => {
                const c = Array.isArray(payload) ? payload[0]?.count : payload?.count;
                if (typeof c !== 'undefined' && Alpine.store('cart')) {
                    Alpine.store('cart').set(c);
                }
            });
            Livewire.on('cartCleared', () => {
                if (Alpine.store('cart')) Alpine.store('cart').clear();
            });
        });
    </script>
</head>
<body x-data="{ mobileSidebarOpen: false }">

    <!-- SIDEBAR -->
    <aside class="sidebar" :class="mobileSidebarOpen ? 'mobile-open' : ''">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <img src="<?php echo e(asset('images/Logo Dapur Mama Delisha.jpeg')); ?>" alt="Logo Dapur Mama Delisha" style="width: 40px; height: 40px; object-fit: contain; border-radius: 8px;" onerror="this.src='<?php echo e(asset('images/logo.png')); ?>'">
            </div>
            <div class="logo-text">Dapur Mama Delisha</div>
        </div>

        <nav class="sidebar-nav" @click="mobileSidebarOpen = false">
            <a href="<?php echo e(route('customer.dashboard')); ?>" class="menu-item <?php echo e(Request::routeIs('customer.dashboard') ? 'active' : ''); ?>">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            <a href="<?php echo e(route('catalog')); ?>" class="menu-item <?php echo e(Request::routeIs('catalog') ? 'active' : ''); ?>">
                <i class="fa-solid fa-border-all"></i> Katalog Menu
            </a>
            <a href="<?php echo e(route('customer.orders')); ?>" class="menu-item <?php echo e(Request::routeIs('customer.orders*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-clipboard-list"></i> Pesanan Saya
            </a>
            <a href="<?php echo e(route('customer.recommendations')); ?>" class="menu-item <?php echo e(Request::routeIs('customer.recommendations') ? 'active' : ''); ?>">
                <i class="fa-regular fa-star"></i> Rekomendasi Paket Menu
            </a>
            <a href="<?php echo e(route('customer.budget-planner')); ?>" class="menu-item <?php echo e(Request::routeIs('customer.budget-planner') ? 'active' : ''); ?>">
                <i class="fa-solid fa-wallet"></i> Rencana Sesuai Budget
            </a>
        </nav>
    </aside>

    <!-- Overlay Gelap saat Sidebar Mobile Terbuka -->
    <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 35; display: none;" x-transition></div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <header class="topbar">
            
            <div style="display: flex; align-items: center; gap: 12px;">
                <!-- Tombol Hamburger khusus Mobile -->
                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="mobile-toggle-btn">
                    <i class="fa-solid" :class="mobileSidebarOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>

                <!-- Bagian Tengah: Sapaan Ramah Customer -->
                <div class="topbar-center">
                    <span style="color: #134e4a;">👋 Halo, <?php echo e(Auth::user()?->name ?? 'Customer'); ?>!</span>
                    <span style="color: #cbd5e1; font-weight: 400;">|</span>
                    <span style="font-size: 0.8rem; color: #64748b; font-weight: 400;">Siap nikmati hidangan hari ini?</span>
                </div>
            </div>

            <div class="topbar-right">
                
                <!-- Ikon Keranjang Belanja -->
                <a href="<?php echo e(route('cart')); ?>" class="topbar-icon-btn" title="Keranjang" style="position: relative; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span x-data x-show="$store.cart.count > 0" 
                        x-text="$store.cart.count"
                        style="position: absolute; top: -2px; right: -2px; background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 700; padding: 1px 6px; border-radius: 50px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(239,68,68,0.3); min-width: 18px; text-align: center;">
                    </span>
                </a>

                <!-- Ikon Notifikasi Dinamis -->
                <div style="position: relative;" 
                     x-data="{ 
                        openNotif: false, 
                        unreadCount: 0, 
                        notifications: [],
                        fetchNotif() {
                            fetch('<?php echo e(route('customer.notifications.json')); ?>')
                                .then(res => res.json())
                                .then(data => {
                                    this.unreadCount = data.unreadCount;
                                    this.notifications = data.notifications;
                                });
                        },
                        markAllRead() {
                            fetch('<?php echo e(route('customer.notifications.read')); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                }
                            }).then(() => { this.unreadCount = 0; this.fetchNotif(); });
                        }
                     }" 
                     x-init="fetchNotif(); setInterval(() => fetchNotif(), 10000)">
                    
                    <button @click="openNotif = !openNotif" class="topbar-icon-btn" title="Notifikasi" style="background: #f8fafc;">
                        <i class="fa-regular fa-bell"></i>
                        <template x-if="unreadCount > 0">
                            <span style="position: absolute; top: -2px; right: -2px; background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 700; padding: 1px 6px; border-radius: 50px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(239,68,68,0.3); min-width: 18px; text-align: center;" x-text="unreadCount"></span>
                        </template>
                    </button>

                    <!-- Dropdown Box Notifikasi -->
                    <div x-show="openNotif" @click.outside="openNotif = false" style="display: none; position: absolute; right: 0; top: 50px; width: 320px; max-width: 90vw; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); z-index: 100; overflow: hidden; font-family: 'Inter', sans-serif;">
                        
                        <div style="padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e2a2d;">Notifikasi</h4>
                            <button @click="markAllRead()" style="background: none; border: none; font-size: 0.75rem; color: #1e5d5c; font-weight: 600; cursor: pointer;">Tandai semua dibaca</button>
                        </div>

                        <div style="max-height: 360px; overflow-y: auto;">
                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="notif.message.includes('selesai') ? '<?php echo e(route('customer.orders')); ?>?tab=history' : (notif.message.includes('ORD-') ? '<?php echo e(route('customer.orders')); ?>?search=' + (notif.message.match(/ORD-[A-Z0-9-]+/) || [''])[0] : '<?php echo e(route('customer.orders')); ?>')" 
                                   @click.prevent="
                                       fetch('<?php echo e(url('/customer/notifications')); ?>/' + notif.id + '/delete', {
                                           method: 'POST',
                                           headers: {
                                               'Content-Type': 'application/json',
                                               'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                           }
                                       }).then(() => {
                                           window.location.href = $el.getAttribute('href');
                                       })
                                   "
                                   style="text-decoration: none; display: flex; padding: 14px 20px; border-bottom: 1px solid #f8fafc; gap: 14px; align-items: flex-start; background: #fff; transition: background 0.2s;" 
                                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #dcfce7; color: #166534; display: grid; place-items: center; flex-shrink: 0; font-size: 0.9rem;">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px;">
                                            <span style="font-size: 0.85rem; font-weight: 700; color: #1e2a2d;" x-text="notif.title"></span>
                                        </div>
                                        <p style="margin: 0; font-size: 0.78rem; color: #64748b; line-height: 1.4;" x-text="notif.message"></p>
                                    </div>
                                </a>
                            </template>

                            <template x-if="notifications.length === 0">
                                <div style="padding: 30px; text-align: center; color: #94a3b8; font-size: 0.85rem;">
                                    Belum ada notifikasi baru.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Profil User dengan Dropdown -->
                <div style="position: relative;" x-data="{ openProfile: false }">
                    <div @click="openProfile = !openProfile" class="user-chip">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()?->name ?? 'Guest')); ?>&background=1e5d5c&color=fff&bold=true" alt="Avatar">
                        <div class="user-chip-info" style="display: none; @media(min-width: 640px){ display: block; }">
                            <p><?php echo e(Auth::user()?->name ?? 'Guest'); ?></p>
                            <p>Customer</p>
                        </div>
                        <i class="fa-solid fa-chevron-down" style="color:#94a3b8;font-size:11px;" :style="openProfile ? 'transform: rotate(180deg); transition: 0.2s;' : 'transform: rotate(0deg); transition: 0.2s;'"></i>
                    </div>

                    <div x-show="openProfile" @click.outside="openProfile = false" style="display: none; position: absolute; right: 0; top: 50px; width: 220px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); z-index: 100; overflow: hidden; font-family: 'Inter', sans-serif; padding: 6px;">
                        
                        <a href="<?php echo e(route('customer.profile')); ?>" style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; color: #334155; text-decoration: none; font-size: 0.88rem; font-weight: 500; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='#f0fdfa'; this.style.color='#134e4a'" onmouseout="this.style.background='transparent'; this.style.color='#334155'">
                            <i class="fa-regular fa-user" style="width: 16px; color: #64748b;"></i> Profil Saya
                        </a>

                        <div style="height: 1px; background: #f1f5f9; margin: 4px 0;"></div>

                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 14px; color: #dc2626; background: none; border: none; font-size: 0.88rem; font-weight: 500; border-radius: 8px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <i class="fa-solid fa-right-from-bracket" style="width: 16px; color: #dc2626;"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <main class="page-content">
            <?php echo e($slot ?? ''); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html><?php /**PATH C:\laragon\www\CateringNew\resources\views/layouts/customer.blade.php ENDPATH**/ ?>