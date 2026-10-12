<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dapur Mama Delisha</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @livewireStyles

    <style>
        /* === PERBAIKAN RESPONSIVE: Reset Dasar === */
        * {
            box-sizing: border-box; /* Memastikan padding tidak menambah lebar elemen */
        }
        
        html, body {
            overflow-x: hidden; /* Mencegah horizontal scroll di HP */
            width: 100%;
            margin: 0;
            padding: 0;
        }
        /* ========================================= */

        :root {
            --primary: #2b5352 !important;
            --primary-dark: #1e3d3c !important;
        }
        .btn-primary, .form-btn {
            background-color: #2b5352 !important;
        }
        .btn-primary:hover, .form-btn:hover {
            background-color: #1e3d3c !important;
        }

        /* Styling responsif untuk mobile */
        @media (max-width: 768px) {
            .desktop-menu, .desktop-auth {
                display: none !important;
            }
            .mobile-menu-btn {
                display: flex !important;
            }
            /* PERBAIKAN: Mengecilkan padding navbar di HP agar logo tidak tergencet */
            .navbar {
                padding: 14px 15px !important; 
            }
        }
        @media (min-width: 769px) {
            .mobile-menu-btn, .mobile-dropdown {
                display: none !important;
            }
        }

        /* PERBAIKAN UTAMA: Memaksa tata letak HP agar turun ke bawah secara rapi dan tidak terpotong */
        @media (max-width: 992px) {
            .hero-grid, .landing-hero .container, .hero-shell {
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                gap: 24px !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            .hero-copy, .hero-visual {
                width: 100% !important;
                max-width: 100% !important;
                flex: unset !important;
            }
        }
    </style>
</head>

<body>

<!-- =========================
     TOAST NOTIFICATION
========================== -->
<div class="alert-toast-container"
     x-data="{
         notifications: [],
         add(message,type='success'){
             const id = Date.now();
             this.notifications.push({id,message,type});
             setTimeout(()=>this.remove(id),4000);
         },
         remove(id){
             this.notifications=this.notifications.filter(n=>n.id!==id)
         }
     }"
     @notify.window="add($event.detail.message,$event.detail.type)">

    @if(session('success'))
        <div class="alert-toast success"
             x-data="{show:true}"
             x-show="show"
             x-transition
             x-init="setTimeout(()=>show=false,4000)">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>

            <button type="button" @click="show=false">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-toast error"
             x-data="{show:true}"
             x-show="show"
             x-transition
             x-init="setTimeout(()=>show=false,4000)">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>

            <button type="button" @click="show=false">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <template x-for="n in notifications" :key="n.id">
        <div :class="'alert-toast '+n.type">

            <template x-if="n.type=='success'">
                <i class="fa-solid fa-circle-check"></i>
            </template>

            <template x-if="n.type=='error'">
                <i class="fa-solid fa-circle-exclamation"></i>
            </template>

            <span x-text="n.message"></span>

            <button type="button" @click="remove(n.id)">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>
    </template>

</div>

<!-- =========================
        NAVBAR
========================== -->
<nav class="navbar" x-data="{ mobileMenuOpen: false }" style="display: flex; justify-content: space-between; align-items: center; padding: 14px 40px; background: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.04); position: sticky; top: 0; z-index: 1000;">
    
    <!-- Sisi Kiri: Logo & Nama Brand -->
    <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; z-index: 2;">
        <img src="{{ asset('images/Logo Dapur Mama Delisha.jpeg') }}" alt="Logo" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover;" onerror="this.src='{{ asset('images/logo.png') }}'">
        <span style="font-weight: 800; font-size: 1.1rem; color: #1e2a2d; letter-spacing: -0.3px;">Dapur Mama Delisha</span>
    </a>

    <!-- Sisi Tengah: Menu Navigasi Desktop -->
    <div style="display: flex; gap: 30px; align-items: center;" class="desktop-menu">
        <a href="{{ url('/') }}" style="text-decoration: none; color: {{ request()->routeIs('home') || request()->is('/') ? '#1e2a2d' : '#475569' }}; font-weight: {{ request()->routeIs('home') || request()->is('/') ? '700' : '500' }}; font-size: 0.95rem; transition: color 0.2s;">Beranda</a>
        
        <a href="{{ route('packages') }}" style="text-decoration: none; color: {{ request()->routeIs('packages') ? '#1e2a2d' : '#475569' }}; font-weight: {{ request()->routeIs('packages') ? '700' : '500' }}; font-size: 0.95rem; transition: color 0.2s;">Cara Pemesanan</a>

        <a href="{{ route('customer.budget-planner') }}" style="text-decoration: none; color: {{ request()->routeIs('customer.budget-planner') ? '#1e2a2d' : '#475569' }}; font-weight: {{ request()->routeIs('customer.budget-planner') ? '700' : '500' }}; font-size: 0.95rem; transition: color 0.2s;">Rencana Budget</a>
    </div>

    <!-- Sisi Kanan: Status Auth & Tombol Masuk/Keranjang (Desktop) -->
    <div style="display: flex; align-items: center; gap: 15px; z-index: 2;" class="desktop-auth">
        @auth
            @if(Auth::user()->isCustomer())
                <a href="{{ route('cart') }}" class="nav-link nav-link-cart {{ Request::is('cart') ? 'active' : '' }}" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem;">
                    <i class="fa-solid fa-basket-shopping"></i> Keranjang
                </a>
                <a href="{{ route('customer.orders') }}" class="nav-link {{ Request::is('customer/orders*') ? 'active' : '' }}" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem;">
                    <i class="fa-solid fa-receipt"></i> Pesanan
                </a>
            @elseif(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-link" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem;">
                    <i class="fa-solid fa-gauge"></i> Panel Admin
                </a>
            @endif

            <a href="{{ route('customer.profile') }}" class="user-pill" title="Profil Saya" style="text-decoration: none; color: #1e2a2d; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-circle-user" style="font-size: 1.1rem; color: #2b5352;"></i>
                <span>{{ Auth::user()->name }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-logout" title="Keluar Akun" style="background: #fee2e2; color: #991b1b; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        @endauth

        @guest
            @unless(request()->routeIs('login'))
                <a href="{{ route('login') }}" style="background: #2b5352; color: #ffffff; padding: 10px 22px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(43,83,82,0.2); transition: background 0.2s;">
                    <i class="fa-solid fa-user"></i> Masuk
                </a>
            @endunless
        @endguest
    </div>

    <!-- Tombol Hamburger (Hanya muncul di HP) -->
    <div class="mobile-menu-btn" style="display: none; align-items: center;">
        <button @click="mobileMenuOpen = !mobileMenuOpen" style="background: none; border: none; font-size: 1.3rem; color: #1e2a2d; cursor: pointer; padding: 5px;">
            <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
        </button>
    </div>

    <!-- Dropdown Menu Mobile -->
    <div x-show="mobileMenuOpen" 
         x-transition
         class="mobile-dropdown"
         style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 15px 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); flex-direction: column; gap: 12px;">
        
        <div style="display: flex; flex-direction: column; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
            <a href="{{ url('/') }}" style="text-decoration: none; color: #1e2a2d; font-weight: 600; font-size: 0.95rem;">Beranda</a>
            <a href="{{ route('packages') }}" style="text-decoration: none; color: #1e2a2d; font-weight: 600; font-size: 0.95rem;">Cara Pemesanan</a>
            <a href="{{ route('customer.budget-planner') }}" style="text-decoration: none; color: #1e2a2d; font-weight: 600; font-size: 0.95rem;">Rencana Budget</a>
        </div>

        @auth
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @if(Auth::user()->isCustomer())
                    <a href="{{ route('cart') }}" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-basket-shopping" style="color: #2b5352;"></i> Keranjang
                    </a>
                    <a href="{{ route('customer.orders') }}" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-receipt" style="color: #2b5352;"></i> Pesanan
                    </a>
                @elseif(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: #475569; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-gauge" style="color: #2b5352;"></i> Panel Admin
                    </a>
                @endif

                <a href="{{ route('customer.profile') }}" style="text-decoration: none; color: #1e2a2d; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-user" style="color: #2b5352;"></i> <span>{{ Auth::user()->name }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" style="margin:0; padding-top: 5px;">
                    @csrf
                    <button type="submit" style="width: 100%; background: #fee2e2; color: #991b1b; border: none; padding: 8px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 5px;">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        @endauth

        @guest
            @unless(request()->routeIs('login'))
                <div style="padding-top: 5px;">
                    <a href="{{ route('login') }}" style="width: 100%; background: #2b5352; color: #ffffff; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px rgba(43,83,82,0.2);">
                        <i class="fa-solid fa-user"></i> Masuk
                    </a>
                </div>
            @endunless
        @endguest
    </div>

</nav>

<!-- =========================
        CONTENT
========================== -->
<main>
    {{ $slot ?? '' }}
    @yield('content')
</main>

<!-- =========================
        FOOTER
========================== -->
<footer class="site-footer" id="kontak" style="background: #2b5352; color: #ffffff; padding: 50px 20px 20px 20px;">
    <div class="container footer-inner" style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px;">
        <div class="footer-brand">
            <div class="brand-mini" style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ asset('images/Logo Dapur Mama Delisha.jpeg') }}" alt="Logo" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                <span style="font-weight: 800; font-size: 1.05rem; color: #ffffff;">Dapur Mama <strong>Delisha</strong></span>
            </div>
            <p style="margin-top: 15px; line-height: 1.6; color: #cbd5e1; font-size: 0.9rem;">
                Menyajikan aneka menu catering rumahan higienis, lezat, halal dan harga bersahabat.
            </p>
        </div>

        <div class="footer-links-column">
            <h4 style="color: #ffffff; margin-bottom: 15px; font-size: 1rem;">Menu</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
                <li><a href="{{ url('/') }}" style="color: #cbd5e1; text-decoration: none; font-size: 0.9rem;">Beranda</a></li>
                <li><a href="{{ route('packages') }}" style="color: #cbd5e1; text-decoration: none; font-size: 0.9rem;">Cara Pemesanan</a></li>
            </ul>
        </div>

        <div class="footer-links-column">
            <h4 style="color: #ffffff; margin-bottom: 15px; font-size: 1rem;">Kontak Kami</h4>
            <ul class="contact-list" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; color: #cbd5e1; font-size: 0.9rem;">
                <li><i class="fa-solid fa-phone"></i> 0895605407825</li>
                <li><i class="fa-solid fa-envelope"></i> dapurmamadelisha@gmail.com</li>
                <li><i class="fa-solid fa-location-dot"></i> Dukuh Kergan RT 03 RW 05, Desa Wangen, Kecamatan Polanharjo, Kabupaten Klaten</li>
            </ul>
        </div>

        <div class="footer-links-column">
            <h4 style="color: #ffffff; margin-bottom: 15px; font-size: 1rem;">Ikuti Kami</h4>
            <ul class="social-list" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; color: #cbd5e1; font-size: 0.9rem;">
                <li><i class="fa-brands fa-instagram"></i> @dapurmamadelisha</li>
                <li><i class="fa-brands fa-facebook-f"></i> Dapur Mama Delisha</li>
                <li><i class="fa-brands fa-tiktok"></i> @mamadelisha</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 20px; text-align: center; color: #cbd5e1; font-size: 0.85rem;">
        <div class="container">
            © {{ date('Y') }} Dapur Mama Delisha. All rights reserved.
        </div>
    </div>
</footer>

@livewireScripts

</body>
</html>