<?php $__env->startSection('content'); ?>

<style>
    @media (max-width: 768px) {
        /* Mengubah grid menjadi 1 kolom */
        .reason-grid, .card-grid {
            display: grid !important;
            grid-template-columns: 1fr !important; 
            gap: 15px !important;
        }

        /* Menyesuaikan gambar tumpeng hero */
        .hero-visual img {
            height: 250px !important; 
        }
        
        .hero-actions {
            justify-content: center;
        }

        /* Perbaikan section Tentang Kami */
        .about-wrapper {
            padding: 30px 20px !important; 
        }
        
        .about-flex {
            flex-direction: column !important; 
            gap: 20px !important;
            text-align: center;
        }
        
        .about-divider {
            width: 100% !important; 
            height: 2px !important;
            margin: 15px 0;
        }

        .about-header {
            justify-content: center !important; 
        }
        
        .about-header .line-decor {
            display: none; 
        }
    }
</style>

<!-- HERO SECTION -->
<section class="landing-hero">
    <div class="container hero-shell">
        <div class="hero-grid">
            <div class="hero-copy">
                <p class="eyebrow">Catering Rumahan • Lezat • Higienis • Terpercaya</p>
                <h1>Nikmati Hidangan Lezat <span>untuk Setiap Momen</span></h1>
                <p class="lead">
                    Dapur Mama Delisha siap menyajikan hidangan rumahan yang lezat, sehat, dan sesuai kebutuhan Anda, mulai dari keluarga, kantor, hingga acara spesial.
                </p>

                <!-- FORM PEMESANAN & REKOMENDASI BUDGET PELANGGAN PADA HALAMAN UTAMA -->
                <div style="margin-top: 24px; background: #ffffff; padding: 20px; border-radius: 16px; border: 1.5px solid #cbd5e1; box-shadow: 0 6px 20px rgba(0,0,0,0.06); max-width: 600px;">
                    <div style="font-size: 0.85rem; font-weight: 800; color: #134e4a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-calculator" style="color: #0d9488;"></i> Form Rekomendasi Menu Sesuai Budget
                    </div>
                    <form action="<?php echo e(route('customer.budget-planner')); ?>" method="GET" id="heroBudgetForm">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 12px;">
                            
                            <!-- 1. Nama Pemesan -->
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <label for="heroCustomerName" style="font-size: 0.78rem; font-weight: 700; color: #334155;">Nama Pemesan</label>
                                <input type="text" id="heroCustomerName" name="customer_name" placeholder="Contoh: Budi" style="width: 100%; padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; font-weight: 600; color: #1e293b; outline: none; box-sizing: border-box;" required>
                            </div>

                            <!-- 2. Total Budget -->
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <label for="heroTotalBudget" style="font-size: 0.78rem; font-weight: 700; color: #334155;">Total Budget</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-weight: 700; color: #64748b; font-size: 0.85rem;">Rp</span>
                                    <input type="number" id="heroTotalBudget" name="total_budget" placeholder="150000" min="1000" step="500" style="width: 100%; padding: 9px 10px 9px 36px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; font-weight: 700; color: #1e293b; outline: none; box-sizing: border-box;" required>
                                </div>
                            </div>

                            <!-- 3. Jumlah Paket -->
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <label for="heroPackageQty" style="font-size: 0.78rem; font-weight: 700; color: #334155;">Jumlah Paket</label>
                                <input type="number" id="heroPackageQty" name="package_quantity" value="5" min="1" step="1" placeholder="5" style="width: 100%; padding: 9px 12px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; font-weight: 700; color: #1e293b; outline: none; box-sizing: border-box;" required>
                            </div>

                            <!-- 4. Rata-rata Budget per Paket (Auto Calculate) -->
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <label style="font-size: 0.78rem; font-weight: 700; color: #0f766e;">Rata-rata / Paket (Auto)</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-weight: 700; color: #0d9488; font-size: 0.85rem;">Rp</span>
                                    <input type="text" id="heroAvgBudgetDisplay" value="0" readonly style="width: 100%; padding: 9px 10px 9px 36px; border: 1.5px solid #99f6e4; background: #f0fdfa; border-radius: 8px; font-size: 0.9rem; font-weight: 800; color: #0f766e; outline: none; box-sizing: border-box; cursor: not-allowed;" title="Rata-rata Budget per Paket = Total Budget ÷ Jumlah Paket">
                                </div>
                            </div>

                        </div>

                        <!-- Tombol Cari Rekomendasi -->
                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 10px;">
                            <span style="font-size: 0.75rem; color: #64748b; font-style: italic;">
                                Rata-rata: Total Budget &divide; Jumlah Paket
                            </span>
                            <button type="submit" style="background-color: #2b5352; color: white; border: none; padding: 11px 22px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; transition: background 0.2s;">
                                <i class="fa-solid fa-magnifying-glass"></i> Cari Rekomendasi
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const budgetInput = document.getElementById('heroTotalBudget');
                        const qtyInput = document.getElementById('heroPackageQty');
                        const avgDisplay = document.getElementById('heroAvgBudgetDisplay');

                        function updateHeroAvg() {
                            if (!budgetInput || !qtyInput || !avgDisplay) return;
                            const budget = parseFloat(budgetInput.value) || 0;
                            const qty = parseInt(qtyInput.value, 10) || 0;
                            if (qty > 0 && budget > 0) {
                                const avg = Math.round(budget / qty);
                                avgDisplay.value = new Intl.NumberFormat('id-ID').format(avg);
                            } else {
                                avgDisplay.value = '0';
                            }
                        }

                        if (budgetInput && qtyInput) {
                            budgetInput.addEventListener('input', updateHeroAvg);
                            qtyInput.addEventListener('input', updateHeroAvg);
                            updateHeroAvg();
                        }
                    });
                </script>

                <!-- TOMBOL AKSI -->
                <div class="hero-actions" style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
                    <a href="<?php echo e(route('catalog')); ?>" class="btn-lihat-katalog" style="background-color: #2b5352; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem;">
                        <i class="fa-solid fa-utensils"></i> Lihat Katalog
                    </a>
                    <a href="<?php echo e(route('contact.order')); ?>" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; border: 2px solid var(--primary); color: var(--primary); padding: 8px 18px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                        <i class="fa-solid fa-bag-shopping"></i> Pesan Sekarang
                    </a>
                </div>

                <div class="brand-trust" style="margin-top: 30px;">
                    <span><i class="fa-solid fa-badge-check"></i> Bahan Berkualitas</span>
                    <span><i class="fa-solid fa-star"></i> Rasa Rumahan</span>
                    <span><i class="fa-solid fa-truck-fast"></i> Delivery Tepat</span>
                </div>
            </div>

            <!-- HERO VISUAL -->
            <div class="hero-visual">
                <div style="position: relative; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.12); background: #ffffff; width: 100%; border: 6px solid #ffffff;">
                    <img src="<?php echo e(asset('images/tumpeng.png')); ?>" alt="Tumpeng Dapur Mama Delisha" style="width: 100% !important; height: 380px !important; object-fit: cover !important; display: block !important; border-radius: 18px !important;">
                    
                    <div style="position: absolute; top: 20px; right: 20px; background: rgba(255, 255, 255, 0.95); padding: 8px 14px; border-radius: 20px; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.85rem; color: #2b5352; backdrop-filter: blur(4px); box-shadow: 0 4px 6px rgba(0,0,0,0.08);">
                        <i class="fa-solid fa-sparkles"></i>
                        <span>Fresh Daily</span>
                    </div>

                    <div style="position: absolute; bottom: 20px; left: 20px; background: rgba(255, 255, 255, 0.95); padding: 8px 14px; border-radius: 20px; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.85rem; color: #2b5352; backdrop-filter: blur(4px); box-shadow: 0 4px 6px rgba(0,0,0,0.08);">
                        <i class="fa-solid fa-bowl-food"></i>
                        <span>Rasa Istimewa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MENU FAVORIT -->
<section class="section menu-showcase" id="menu-favorit">
    <div class="container">
        <div class="menu-header-block">
            <p class="section-kicker">Menu Favorit Kami</p>
            <h2>Pilihan Menu Terbaik untuk Anda</h2>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!($featuredFromApriori ?? false)): ?>
                <p style="font-size: 0.9rem; color: #64748b; margin-top: 8px;">Data analisis Apriori belum cukup untuk menampilkan rekomendasi — berikut menu populer kami.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="card-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $featuredMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $name = $menu->name;
                    $price = $menu->price;
                    $description = $menu->description;
                    $categoryName = $menu->category->name ?? 'Katering Spesial';
                    
                    $imagePath = $menu->image ? (filter_var($menu->image, FILTER_VALIDATE_URL) ? $menu->image : asset('storage/' . $menu->image)) : asset('images/tumpeng.png');
                ?>

                <article class="menu-card" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); display: flex; flex-direction: column;">
                    <div style="position: relative; width: 100%; height: 260px; background: #f8fafc; overflow: hidden;">
                        <img src="<?php echo e($imagePath); ?>" alt="<?php echo e($name); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" onerror="this.onerror=null; this.src='<?php echo e(asset('images/tumpeng.png')); ?>';">
                        <span class="menu-badge" style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: #2b5352; box-shadow: 0 2px 5px rgba(0,0,0,0.1);"><?php echo e(($featuredFromApriori ?? false) ? 'FAVORIT' : 'POPULAR'); ?></span>
                    </div>
                    
                    <div class="menu-body" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin-bottom: 10px;"><?php echo e($name); ?></h3>
                        <div class="menu-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span class="menu-category" style="font-size: 0.78rem; font-weight: 700; color: #2b5352; text-transform: uppercase;"><?php echo e($categoryName); ?></span>
                            <span class="menu-price" style="font-size: 1rem; font-weight: 700; color: #1e293b;">Rp <?php echo e(number_format($price, 0, ',', '.')); ?></span>
                        </div>
                        <p style="font-size: 0.88rem; color: #64748b; line-height: 1.6; margin: 0;"><?php echo e(Str::limit($description, 90)); ?></p>
                    </div>
                </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
                    Belum ada menu favorit yang tersedia dari hasil analisis Apriori.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<!-- KENAPA MEMILIH KAMI -->
<section class="section why-us" id="why-us">
    <div class="container">
        <div class="section-head why-head">
            <h2>Kenapa Memilih Dapur Mama Delisha?</h2>
        </div>
        <div class="reason-grid">
            <div class="reason-box">
                <div class="reason-icon"><i class="fa-solid fa-utensils"></i></div>
                <h3>Beragam Pilihan Menu</h3>
                <p>Terbagi berbagai pilihan menu yang bisa disesuaikan dengan kebutuhan Anda.</p>
            </div>
            <div class="reason-box active">
                <div class="reason-icon"><i class="fa-solid fa-seedling"></i></div>
                <h3>Rasa Berkualitas</h3>
                <p>Semua bahan dibuat dengan rasa yang otentik dan sesuai selera keluarga.</p>
            </div>
            <div class="reason-box">
                <div class="reason-icon"><i class="fa-solid fa-user-group"></i></div>
                <h3>Cocok untuk Semua Acara</h3>
                <p>Siap melayani kebutuhan harian, rapat, syukuran, dan acara besar lainnya.</p>
            </div>
            <div class="reason-box">
                <div class="reason-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                <h3>Pesanan Lebih Mudah</h3>
                <p>Proses pemesanan cepat, praktis, dan terstruktur untuk kebutuhanmu.</p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION TENTANG KAMI -->
<section class="section about-us py-5" id="about-us">
    <div class="container">
        <div class="about-wrapper" style="background: linear-gradient(180deg, #fbf8f2 0%, #f5f1e8 100%); border-radius: 24px; padding: 60px 60px; box-shadow: 0 10px 30px rgba(26,60,52,0.08); border: 1px solid rgba(26,60,52,0.06); width: 100%;">

            <div class="about-flex" style="display: flex; align-items: center; gap: 40px; width: 100%; text-align: left;">

                <div style="flex-shrink: 0; width: 180px; height: 180px; border-radius: 50%; background: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(26,60,52,0.12); border: 4px solid #ffffff; outline: 1px solid #e6dfd0;">
                    <img src="<?php echo e(asset('images/Logo Dapur Mama Delisha.jpeg')); ?>" alt="Logo Dapur Mama Delisha" style="width: 88%; height: 88%; object-fit: contain; border-radius: 50%;">
                </div>

                <div style="max-width: 720px;">
                    <div class="about-header" style="display: inline-flex; align-items: center; gap: 10px; background: rgba(26,60,52,0.06); padding: 6px 18px; border-radius: 999px; margin-bottom: 14px;">
                        <i class="fa-solid fa-leaf" style="color: #1a3c34; font-size: 0.75rem;"></i>
                        <span style="text-transform: uppercase; font-weight: 700; font-size: 0.72rem; letter-spacing: 2px; color: #1a3c34;">Tentang Kami</span>
                    </div>

                    <h2 style="color: #1a3c34; font-size: 2rem; font-weight: 700; margin-bottom: 10px; letter-spacing: -0.5px; line-height: 1.25;">
                        Tentang Dapur Mama Delisha
                    </h2>

                    <div style="width: 48px; height: 3px; background-color: #c9a24b; margin: 0 0 22px 0; border-radius: 2px;"></div>

                    <h5 style="font-size: 1.1rem; font-weight: 600; color: #2c3e50; margin-bottom: 14px;">
                        Masakan Rumahan dengan Cita Rasa Otentik
                    </h5>

                    <p style="color: #5c5c5c; font-size: 0.98rem; line-height: 1.8; margin-bottom: 14px;">
                        Dapur Mama Delisha berdiri sejak 2022 dengan visi menyajikan hidangan rumahan yang lezat, higienis, dan terjangkau. Kami menggunakan bahan-bahan berkualitas tinggi, diproses secara higienis, dan dikemas dengan rasa yang mengingatkan pada masakan keluarga.
                    </p>

                    <p style="color: #5c5c5c; font-size: 0.98rem; line-height: 1.8; margin-bottom: 28px;">
                        Setiap menu dirancang oleh chef berpengalaman dan disesuaikan untuk kebutuhan catering acara kecil hingga besar.
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                        <span style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; border: 1px solid #e6dfd0; padding: 8px 16px; border-radius: 999px; font-size: 0.82rem; font-weight: 600; color: #1a3c34; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                            <i class="fa-solid fa-calendar-check" style="color: #c9a24b;"></i> Berdiri Sejak 2022
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; border: 1px solid #e6dfd0; padding: 8px 16px; border-radius: 999px; font-size: 0.82rem; font-weight: 600; color: #1a3c34; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                            <i class="fa-solid fa-hand-holding-heart" style="color: #c9a24b;"></i> Higienis &amp; Berkualitas
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\malaa\resources\views/welcome.blade.php ENDPATH**/ ?>