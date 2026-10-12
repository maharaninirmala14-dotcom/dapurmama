<div>
    <!-- Leaflet CSS & JS for Interactive Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .leaflet-container { background: #e2e8f0 !important; width: 100% !important; height: 100% !important; }
        .leaflet-tile { display: block !important; }

        /* Sembunyikan panah atas-bawah bawaan input number */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .cart-layout-grid {
            grid-template-columns: 1fr 360px;
        }
        @media (max-width: 900px) {
            .cart-layout-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .cart-page-inner {
                padding: 16px 12px 40px 12px !important;
            }
            .cart-item-row {
                flex-wrap: wrap !important;
                position: relative;
                padding-bottom: 44px !important;
            }
            .cart-item-info {
                width: 100% !important;
                flex: 1 1 100% !important;
            }
            .cart-item-price {
                order: 3;
                font-size: 0.8rem !important;
            }
            .cart-item-qty {
                order: 4;
            }
            .cart-item-total {
                order: 5;
                text-align: left !important;
                min-width: 0 !important;
            }
            .cart-item-remove {
                position: absolute !important;
                top: 12px;
                right: 0;
            }
        }
    </style>

    <div style="width: 100%; overflow-x: hidden !important; box-sizing: border-box;">
        <div class="cart-page-inner" style="padding: 30px 24px 60px 24px; max-width: 1280px; margin: 0 auto; box-sizing: border-box;">
            
            <!-- HEADER HALAMAN -->
            <div style="margin-bottom: 20px;">
                <h1 style="font-size: 1.7rem; font-weight: 800; color: #1e2a2d; margin-bottom: 2px;">Keranjang</h1>
                <p style="color: #64748b; font-size: 0.9rem;">Periksa kembali pesanan Anda sebelum checkout</p>
            </div>

            @if(empty($cartItems))
                <div style="text-align:center; padding: 60px 20px; background: white; border-radius: 16px; border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-basket-shopping" style="font-size: 3.5rem; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h3 style="color:#1e2a2d">Keranjang Anda Masih Kosong</h3>
                    <p style="color:#64748b; margin: 10px 0 20px;">Tambahkan hidangan lezat dari Dapur Mama Delisha</p>
                    <a href="{{ route('catalog') }}" class="btn-primary" style="display: inline-block; background: #1e5d5c; color: #ffffff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none;">Lihat Katalog Menu</a>
                </div>
            @else
                <!-- LAYOUT UTAMA DUA KOLOM -->
                <div class="cart-layout-grid" style="display: grid !important; gap: 24px !important; align-items: start !important; width: 100% !important; box-sizing: border-box !important;">
                    
                    <!-- KOLOM KIRI (DAFTAR ITEM) -->
                    <div style="display: flex; flex-direction: column; gap: 20px; min-width: 0;">
                        
                        <!-- KOTAK UTAMA DAFTAR ITEM -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                            
                            <!-- HEADER DALAM KOTAK -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; margin-bottom: 2px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e2a2d; font-size: 0.95rem;">
                                    <i class="fa-solid fa-cart-shopping" style="color: #1e5d5c;"></i> 
                                    {{ count($cartItems) }} Jenis Menu dalam Keranjang
                                </div>
                                <button type="button" wire:click="confirmClearCart" style="background: transparent; border: 1px solid #fecaca; color: #dc2626; padding: 5px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-trash-can"></i> Hapus Semua
                                </button>
                            </div>

                            <!-- LIST ITEM PESANAN -->
                            @foreach($cartItems as $key => $item)
                            @php
                                $minLimit = $item['min_qty'] ?? 1;
                                $catLower = strtolower($item['category_name'] ?? '');
                                $isTumpeng = str_contains($catLower, 'tumpeng');
                                $isSnackKering = str_contains($catLower, 'kering');
                                $isSnackBasah = str_contains($catLower, 'basah') || str_contains($catLower, 'kue');
                                $isMinuman = str_contains($catLower, 'minum') || str_contains($catLower, 'es') || str_contains($catLower, 'teh');

                                // Penentuan Label Satuan Dinamis
                                if ($isSnackKering) {
                                    $unitLabel = 'Kg';
                                } elseif ($isSnackBasah) {
                                    $unitLabel = 'Pcs';
                                } elseif ($isMinuman) {
                                    $unitLabel = 'Gelas';
                                } else {
                                    $unitLabel = 'Kotak'; // Default untuk Nasi Box / Umum
                                }
                            @endphp
                            <div class="cart-item-row" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #f1f5f9; gap: 12px;" wire:key="cart-item-{{ $key }}">

                                <!-- GAMBAR & INFO PRODUK -->
                                <div class="cart-item-info" style="display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0;">
                                    <div style="width: 60px; height: 60px; border-radius: 10px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; border: 1px solid #e2e8f0;">
                                        @if(isset($item['image']) && !empty($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @elseif(isset($item['type']) && $item['type'] === 'package')
                                            <i class="fa-solid fa-box-open" style="font-size: 1.4rem; color: #1e5d5c;"></i>
                                        @else
                                            <i class="fa-solid fa-bowl-food" style="font-size: 1.4rem; color: #1e5d5c;"></i>
                                        @endif
                                    </div>
                                    <div style="min-width: 0; flex: 1;">
                                        <h4 style="font-size: 0.92rem; font-weight: 700; color: #1e2a2d; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item['name'] }}</h4>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            Kategori: {{ $item['category_name'] ?? 'Umum' }}
                                        </p>
                                        
                                        @if(!$isTumpeng)
                                        <span style="font-size: 0.7rem; color: #166534; font-weight: 700; background: #f0fdf4; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">
                                            <i class="fa-solid fa-circle-check"></i> Min. Pemesanan: {{ $minLimit }} {{ $unitLabel }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- HARGA SATUAN -->
                                <div class="cart-item-price" style="font-weight: 700; color: #1e5d5c; font-size: 0.88rem; white-space: nowrap; text-align: right;">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </div>

                                <!-- TOMBOL QUANTITY & INPUT LANGSUNG -->
                                <div class="cart-item-qty" style="display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; overflow: hidden; flex-shrink: 0;">
                                    <button type="button" wire:click="decrement('{{ $key }}')" wire:loading.attr="disabled" 
                                        style="background: transparent; border: none; width: 28px; height: 28px; display: grid; place-items: center; cursor: {{ $item['qty'] <= $minLimit ? 'not-allowed' : 'pointer' }}; color: {{ $item['qty'] <= $minLimit ? '#cbd5e1' : '#475569' }}; font-weight: 600;"
                                        {{ $item['qty'] <= $minLimit ? 'disabled' : '' }}>-</button>
                                    
                                    <!-- Kolom input angka bebas ketik tanpa panah bawaan -->
                                    <input type="number" min="{{ $minLimit }}" wire:blur="updateQty('{{ $key }}', $event.target.value)" value="{{ $item['qty'] }}" 
                                        style="width: 45px; text-align: center; font-size: 0.85rem; font-weight: 700; color: #1e2a2d; border: none; outline: none; background: transparent;" />
                                    
                                    <button type="button" wire:click="increment('{{ $key }}')" wire:loading.attr="disabled" style="background: transparent; border: none; width: 28px; height: 28px; display: grid; place-items: center; cursor: pointer; color: #475569; font-weight: 600;">+</button>
                                </div>

                                <!-- TOTAL HARGA PER ITEM -->
                                <div class="cart-item-total" style="font-weight: 700; color: #1e2a2d; font-size: 0.9rem; white-space: nowrap; text-align: right; min-width: 85px;">
                                    Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                </div>

                                <!-- TOMBOL HAPUS -->
                                <button type="button" class="cart-item-remove" wire:click="confirmRemoveItem('{{ $key }}')" style="background: transparent; border: none; color: #ef4444; cursor: pointer; font-size: 0.95rem; padding: 6px; flex-shrink: 0;">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>

                            </div>
                            @endforeach

                            <!-- TOMBOL LANJUT BELANJA -->
                            <div style="margin-top: 18px;">
                                <a href="{{ route('catalog') }}" style="display: inline-flex; align-items: center; gap: 8px; background: transparent; border: 1px solid #1e5d5c; color: #1e5d5c; padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 0.84rem; text-decoration: none;">
                                    <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
                                </a>
                            </div>

                        </div>

                    </div>

                    <!-- KOLOM KANAN (RINGKASAN BELANJA) -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px; position: sticky; top: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); box-sizing: border-box;">
                        <h2 style="font-size: 1.1rem; font-weight: 700; color: #1e2a2d; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                            Ringkasan Belanja
                        </h2>

                        <!-- DETAIL BELI PER ITEM -->
                        <div style="margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px dashed #e2e8f0;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 6px;">Detail Beli:</span>
                            @foreach($cartItems as $item)
                            @php
                                $catSummaryLower = strtolower($item['category_name'] ?? '');
                                if (str_contains($catSummaryLower, 'kering')) {
                                    $summaryUnit = 'Kg';
                                } elseif (str_contains($catSummaryLower, 'basah') || str_contains($catSummaryLower, 'kue')) {
                                    $summaryUnit = 'Pcs';
                                } elseif (str_contains($catSummaryLower, 'minum') || str_contains($catSummaryLower, 'es') || str_contains($catSummaryLower, 'teh')) {
                                    $summaryUnit = 'Gelas';
                                } else {
                                    $summaryUnit = 'Kotak';
                                }
                            @endphp
                            <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #334155; margin-bottom: 4px; gap: 8px;">
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">{{ $item['name'] }}</span>
                                <span style="font-weight: 600; color: #1e2a2d; flex-shrink: 0;">{{ $item['qty'] }} {{ $summaryUnit }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.85rem; color: #475569;">
                            <span>Ongkos Kirim</span>
                            <span style="font-weight: 600; color: #1e2a2d;">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 14px; margin-bottom: 16px;">
                            <span style="font-weight: 700; color: #1e2a2d; font-size: 0.95rem;">Total Harga</span>
                            <span style="font-weight: 800; color: #1e5d5c; font-size: 1.15rem;">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <!-- BANNER INFO KETENTUAN MENU -->
                        <div style="background: #f0fdfa; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 6px; font-weight: 700; color: #166534; font-size: 0.82rem; margin-bottom: 2px;">
                                <i class="fa-solid fa-circle-check"></i> Ketentuan Menu
                            </div>
                            <p style="font-size: 0.73rem; color: #15803d; margin: 0; line-height: 1.4;">
                                Ketentuan minimal pemesanan: Nasi Box Min. 20 kotak, Snack Basah Min. 20 pcs, Snack Kering Min. 1 kg, Tumpeng bebas, Minuman Bebas.
                            </p>
                        </div>

                        @auth
                            @if(count($cartItems) > 0)
                                <a href="{{ route('checkout') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #1e5d5c; color: #ffffff; padding: 11px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 0.88rem; text-align: center; box-shadow: 0 4px 12px rgba(30,93,92,0.2);">
                                    Checkout <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @endif
                        @else
                            <a href="https://wa.me/6287738352816" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #ea580c; color: #ffffff; padding: 11px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 0.88rem; text-align: center;">
                                <i class="fa-brands fa-whatsapp"></i> Hubungi Kami (WhatsApp)
                            </a>
                        @endauth

                    </div>

                </div>
            @endif
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS SATU ITEM -->
    @if($showRemoveModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 30px 24px; text-align: center;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 16px auto;">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div style="font-weight: 700; color: #1e2a2d; font-size: 0.95rem; margin-bottom: 8px;">Hapus Item Keranjang</div>
            <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">"{{ $removeName }}" akan dihapus dari keranjang Anda.</div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" wire:click="closeRemoveModal" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                <button type="button" wire:click="removeItem" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #ef4444; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Hapus</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL KONFIRMASI HAPUS SEMUA -->
    @if($showClearModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 30px 24px; text-align: center;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 16px auto;">
                <i class="fa-solid fa-cart-arrow-down"></i>
            </div>
            <div style="font-weight: 700; color: #1e2a2d; font-size: 0.95rem; margin-bottom: 8px;">Kosongkan Keranjang</div>
            <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">Semua item ({{ count($cartItems) }} menu) akan dihapus dari keranjang Anda.</div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" wire:click="closeClearModal" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                <button type="button" wire:click="clearCart" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #ef4444; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Hapus Semua</button>
            </div>
        </div>
    </div>
    @endif
</div>