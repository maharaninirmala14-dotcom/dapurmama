<div>
    <!-- Leaflet CSS & JS for Interactive Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .leaflet-container { background: #e2e8f0 !important; }
        .leaflet-tile { display: block !important; }

        @media (max-width: 900px) {
            .checkout-layout-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 640px) {
            .checkout-two-col {
                grid-template-columns: 1fr !important;
            }
            .container {
                padding-left: 12px !important;
                padding-right: 12px !important;
                box-sizing: border-box;
            }
            .step-indicator-bar > div {
                font-size: 0.78rem !important;
                padding: 8px 12px !important;
            }
        }
    </style>

    <div class="container" style="padding-top: 30px; padding-bottom: 80px;">

        <!-- HEADER -->
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #1e2a2d; margin: 0 0 6px 0;">Checkout Pesanan</h1>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Lengkapi detail pemesanan dan pilih metode pembayaran catering Anda</p>
        </div>

        <!-- STEP INDICATOR BAR -->
        <div class="step-indicator-bar" style="display:flex; justify-content:center; align-items:center; gap:15px; margin-bottom:30px; flex-wrap:wrap;">
            <div style="padding:10px 18px; border-radius:999px; background:{{ $step >= 1 ? '#2b5352' : '#e2e8f0' }}; color:{{ $step >= 1 ? '#fff' : '#64748b' }}; font-weight:700; font-size:0.9rem;">1. Detail Pemesanan</div>
            <div style="padding:10px 18px; border-radius:999px; background:{{ $step >= 2 ? '#2b5352' : '#e2e8f0' }}; color:{{ $step >= 2 ? '#fff' : '#64748b' }}; font-weight:700; font-size:0.9rem;">2. Metode Pembayaran</div>
            <div style="padding:10px 18px; border-radius:999px; background:{{ $step === 3 ? '#10b981' : '#e2e8f0' }}; color:{{ $step === 3 ? '#fff' : '#64748b' }}; font-weight:700; font-size:0.9rem;">3. Pesanan Berhasil</div>
        </div>

        @if(empty($cartItems) && $step !== 3)
            <div style="text-align:center; padding:60px; background:white; border-radius:16px; border:1px solid #e2e8f0;">
                <h3 style="color:#2b5352;">Keranjang Belanja Anda Kosong</h3>
                <a href="{{ route('catalog') }}" style="display:inline-block; margin-top:15px; padding:12px 24px; background:#2b5352; color:#fff; border-radius:10px; text-decoration:none;">Kembali ke Katalog</a>
            </div>
        @else

        <!-- ================= STEP 1: DETAIL PEMESANAN ================= -->
        @if($step === 1)
        <div class="cart-layout checkout-layout-grid" style="display:grid; grid-template-columns: 1fr 380px; gap:28px;">
            <div>
                <div style="background:#fff; border-radius:16px; padding:28px; border:1px solid #e2e8f0; box-shadow:0 4px 20px rgba(0,0,0,0.03);">
                    <h2 style="font-size:1.3rem; margin-bottom:24px; color:#2b5352;">Detail Pemesanan</h2>

                    <div class="checkout-two-col" style="display:grid; grid-template-columns: 1fr 1fr; gap:18px;">
                        <div>
                            <label style="font-weight:700; color:#334155; display:block; margin-bottom:6px;">Nama Pemesan *</label>
                            <input type="text" wire:model="customerName" style="width:100%; padding:12px; border-radius:10px; border:1px solid #cbd5e1;">
                            @error('customerName') <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label style="font-weight:700; color:#334155; display:block; margin-bottom:6px;">Nomor HP *</label>
                            <input type="text" wire:model="phoneNumber" style="width:100%; padding:12px; border-radius:10px; border:1px solid #cbd5e1;">
                            @error('phoneNumber') <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div style="margin-top:16px;">
                        <label style="font-weight:700; color:#334155; display:block; margin-bottom:6px;">Alamat Lengkap *</label>
                        <textarea id="shipping-address-input" wire:model="shippingAddress" rows="3" placeholder="Contoh: Polanharjo, Klaten" style="width:100%; padding:12px; border-radius:10px; border:1px solid #cbd5e1;"></textarea>
                        @error('shippingAddress') <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- OPSI PENGIRIMAN -->
                    <div style="margin-top:16px; background:#f8fafc; padding:16px; border-radius:12px; border:1px solid #e2e8f0;">
                        <label style="font-weight:700; color:#2b5352; display:block; margin-bottom:10px;">Metode Pengiriman *</label>
                        <div style="display:flex; gap:20px;">
                            <label style="cursor:pointer;"><input type="radio" wire:model.live="deliveryMethod" name="deliveryMethodGroup" value="diambil"> Diambil Sendiri</label>
                            <label style="cursor:pointer;"><input type="radio" wire:model.live="deliveryMethod" name="deliveryMethodGroup" value="diantar"> Diantar Kurir (Otomatis Sesuai Peta)</label>
                        </div>

                        @if($deliveryMethod === 'diantar')
                            <div style="margin-top:12px; padding:10px; background:#f0fdfa; border-radius:8px; border:1px solid #99f6e4; display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:0.85rem; color:#0f766e;">Jarak Otomatis: <strong>{{ $distance }} KM</strong></span>
                                <span style="font-size:0.9rem; color:#2b5352; font-weight:700;">Ongkir: Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- TANGGAL & WAKTU PENGIRIMAN -->
                    <div class="checkout-two-col" style="display:grid; grid-template-columns: 1fr 1fr; gap:18px; margin-top:16px;">
                        
                        <!-- Tanggal Pengiriman dengan Preview Format Lengkap menggunakan Alpine.js mandiri -->
                        <div x-data="{ dateVal: '{{ $orderDate }}' }">
                            <label for="orderDate" style="font-weight:700; color:#334155; display:block; margin-bottom:6px;">Tanggal Pengiriman *</label>
                            <input type="date" id="orderDate" wire:model.live="orderDate" x-model="dateVal" min="{{ now()->format('Y-m-d') }}" style="width:100%; padding:12px; border-radius:10px; border:1px solid #cbd5e1; background:#fff;">
                            @error('orderDate') <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span> @enderror
                            
                            <!-- Kotak Preview Otomatis Hari, Tanggal, Bulan, Tahun -->
                            <div style="margin-top: 8px; padding: 10px 14px; background: #f0fdfa; border: 1.5px solid #99f6e4; border-radius: 10px; font-size: 0.85rem; color: #0f766e; font-weight: 700;">
                                <i class="fa-solid fa-calendar-days" style="margin-right: 6px;"></i> 
                                <span x-text="dateVal ? new Date(dateVal + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Silakan pilih tanggal pengiriman'"></span>
                            </div>
                        </div>

                        <!-- Waktu Pengiriman Bebas (Jam & Menit) -->
                        <div>
                            <label for="deliveryTime" style="font-weight:700; color:#334155; display:block; margin-bottom:6px;">Waktu Pengiriman *</label>
                            <input type="time" id="deliveryTime" wire:model="deliveryTime" style="width:100%; padding:12px; border-radius:10px; border:1px solid #cbd5e1; background:#fff;">
                            @error('deliveryTime') <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span> @enderror
                            <div style="font-size:0.75rem; color:#64748b; margin-top:6px;">Contoh: 13:15 (Bisa diketik atau dipilih bebas)</div>
                        </div>

                    </div>

                    <!-- PETA (Hanya tampil jika metode pengiriman 'diantar') -->
                    @if($deliveryMethod === 'diantar')
                    <div style="margin-top:20px;" wire:ignore>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <label style="font-weight:700; color:#334155;"><i class="fa-solid fa-location-dot" style="color:#ef4444;"></i> Peta Lokasi Pengiriman (Geser pin jika perlu)</label>
                            <button type="button" onclick="locateMe()" style="background:#e0f2fe; color:#0284c7; border:none; padding:6px 12px; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer;">Gunakan Lokasi Saya</button>
                        </div>
                        <div id="checkout-map" style="height: 280px; width: 100%; border-radius: 12px; border: 2px solid #2b5352; z-index:1; position:relative;"></div>
                        <p style="font-size:0.78rem; color:#94a3b8; margin-top:6px;">Koordinat Terpilih: <strong id="coords-text">{{ $latitude }}, {{ $longitude }}</strong></p>
                    </div>
                    @endif

                    <div style="margin-top:16px;">
                        <label style="font-weight:700; color:#334155; display:block; margin-bottom:6px;">Catatan Tambahan (Opsional)</label>
                        <textarea wire:model="notes" rows="2" style="width:100%; padding:12px; border-radius:10px; border:1px solid #cbd5e1;"></textarea>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR RINGKASAN -->
            <div>
                <div style="background:#fff; border-radius:16px; padding:24px; border:1px solid #e2e8f0; position:sticky; top:20px;">
                    <h2 style="font-size:1.2rem; margin-bottom:18px; color:#2b5352;">Ringkasan Pesanan</h2>
                    
                    <div style="max-height:180px; overflow-y:auto; margin-bottom:14px;">
                        @foreach($cartItems as $item)
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.85rem; border-bottom:1px dashed #f1f5f9; padding-bottom:6px;">
                            <span>{{ Str::limit($item['name'], 18) }} ({{ $item['qty'] }}x)</span>
                            <span style="font-weight:700;">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="background:#f8fafc; border-radius:10px; padding:14px; margin-bottom:18px; font-size:0.9rem;">
                        <div style="display:flex; justify-content:space-between; color:#64748b;"><span>Subtotal:</span> <span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                        <div style="display:flex; justify-content:space-between; color:#64748b; margin-top:4px;"><span>Ongkir:</span> <span>Rp {{ number_format($shippingFee, 0, ',', '.') }}</span></div>
                        <div style="display:flex; justify-content:space-between; font-size:1.1rem; font-weight:800; color:#2b5352; border-top:1px dashed #cbd5e1; padding-top:8px; margin-top:8px;">
                            <span>Grand Total</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button wire:click="goToPayment" wire:loading.attr="disabled" style="width:100%; padding:14px; background:#2b5352; color:#fff; border:none; border-radius:12px; font-weight:700; cursor:pointer;">
                        Lanjut ke Pembayaran &rarr;
                    </button>
                    
                    <a href="{{ route('cart') }}" style="display:block; text-align:center; margin-top:12px; color:#475569; font-size:0.85rem; font-weight:600; text-decoration:none;">Kembali ke Keranjang</a>
                </div>
            </div>
        </div>
        @endif

        <!-- ================= STEP 2: METODE PEMBAYARAN & RINCIAN PESANAN ================= -->
        @if($step === 2)
        <div class="checkout-layout-grid" style="display:grid; grid-template-columns: 1fr 380px; gap:28px;">
            <div style="background:#fff; border-radius:16px; padding:28px; border:1px solid #e2e8f0; box-shadow:0 4px 20px rgba(0,0,0,0.03);">
                <h2 style="color:#2b5352; margin-bottom:20px; font-size:1.3rem;">Pilih Metode Pembayaran</h2>
                
                <div style="margin-bottom:24px;">
                    <label style="font-weight:700; color:#334155; display:block; margin-bottom:10px;">Ketentuan Pembayaran</label>
                    <div style="display:flex; gap:12px;">
                        <button type="button" wire:click="$set('paymentType', 'dp')" style="flex:1; padding:12px; border-radius:10px; border:2px solid {{ $paymentType === 'dp' ? '#2b5352' : '#cbd5e1' }}; background:{{ $paymentType === 'dp' ? '#f0fdfa' : '#fff' }}; color:{{ $paymentType === 'dp' ? '#2b5352' : '#334155' }}; font-weight:700; cursor:pointer; text-align:left;">
                            <div style="font-size:0.95rem;">Bayar DP 50%</div>
                            <div style="font-size:0.75rem; color:#64748b; font-weight:normal; margin-top:2px;">Sisa dibayar saat pesanan tiba</div>
                        </button>
                        <button type="button" wire:click="$set('paymentType', 'lunas')" style="flex:1; padding:12px; border-radius:10px; border:2px solid {{ $paymentType === 'lunas' ? '#2b5352' : '#cbd5e1' }}; background:{{ $paymentType === 'lunas' ? '#f0fdfa' : '#fff' }}; color:{{ $paymentType === 'lunas' ? '#2b5352' : '#334155' }}; font-weight:700; cursor:pointer; text-align:left;">
                            <div style="font-size:0.95rem;">Bayar Lunas 100%</div>
                            <div style="font-size:0.75rem; color:#64748b; font-weight:normal; margin-top:2px;">Lunas di awal</div>
                        </button>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="font-weight:700; color:#334155; display:block; margin-bottom:10px;">Metode / Saluran Pembayaran</label>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        
                        <!-- Transfer Bank -->
                        <label style="display:flex; align-items:flex-start; gap:12px; padding:14px; border:2px solid {{ $paymentMethod === 'transfer' ? '#2b5352' : '#cbd5e1' }}; border-radius:10px; cursor:pointer; background:{{ $paymentMethod === 'transfer' ? '#f0fdfa' : '#f8fafc' }};">
                            <input type="radio" wire:model.live="paymentMethod" name="paymentMethodGroup" value="transfer" style="width:18px; height:18px; margin-top:2px;">
                            <div style="width:100%;">
                                <div style="font-weight:700; color:#1e293b;"><i class="fa-solid fa-building-columns" style="color:#2b5352; margin-right:6px;"></i> Transfer Bank (BRI, ShopeePay, DANA)</div>
                                <div style="font-size:0.8rem; color:#64748b; margin-bottom:8px;">Silakan transfer ke salah satu rekening di bawah ini:</div>
                                @if($paymentMethod === 'transfer')
                                    <div style="background:#fff; padding:12px; border-radius:8px; border:1px solid #cbd5e1; font-size:0.85rem; display:flex; flex-direction:column; gap:6px;">
                                        <div><strong>Bank BRI:</strong> 1234-01-005678-50-3 (a.n. Dapur Mama Delisha)</div>
                                        <div><strong>ShopeePay / DANA:</strong> 0895-6054-07825 (a.n. Dapur Mama Delisha)</div>
                                    </div>
                                @endif
                            </div>
                        </label>

                        <!-- COD -->
                        <label style="display:flex; align-items:flex-start; gap:12px; padding:14px; border:2px solid {{ $paymentMethod === 'cod' ? '#2b5352' : '#cbd5e1' }}; border-radius:10px; cursor:pointer; background:{{ $paymentMethod === 'cod' ? '#f0fdfa' : '#f8fafc' }};">
                            <input type="radio" wire:model.live="paymentMethod" name="paymentMethodGroup" value="cod" style="width:18px; height:18px; margin-top:2px;">
                            <div style="width:100%;">
                                <div style="font-weight:700; color:#1e293b;"><i class="fa-solid fa-hand-holding-dollar" style="color:#2b5352; margin-right:6px;"></i> Bayar di Tempat (COD)</div>
                                <div style="font-size:0.8rem; color:#64748b;">Bayar tunai langsung kepada kurir saat pesanan sampai</div>
                            </div>
                        </label>

                    </div>
                </div>

                @php $paymentSelected = in_array($paymentMethod, ['transfer', 'cod']); @endphp
                @unless($paymentSelected)
                    <p style="margin-top:24px; font-size:0.85rem; color:#c2410c; font-weight:600;"><i class="fa-solid fa-circle-info" style="margin-right:6px;"></i> Silakan pilih metode pembayaran terlebih dahulu untuk melanjutkan.</p>
                @endunless

                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:{{ $paymentSelected ? '30px' : '10px' }};">
                    <button type="button" wire:click="backToDetails" style="padding:12px 20px; border-radius:10px; border:1px solid #cbd5e1; background:#fff; font-weight:700; cursor:pointer;">&larr; Kembali</button>
                    <button type="button" wire:click="placeOrder" @disabled(!$paymentSelected) style="padding:14px 28px; background:{{ $paymentSelected ? '#2b5352' : '#94a3b8' }}; color:#fff; border:none; border-radius:12px; font-weight:700; cursor:{{ $paymentSelected ? 'pointer' : 'not-allowed' }}; opacity:{{ $paymentSelected ? '1' : '0.7' }};">Konfirmasi Pesanan</button>
                </div>
            </div>

            <div>
                <div style="background:#fff; border-radius:16px; padding:24px; border:1px solid #e2e8f0; position:sticky; top:20px;">
                    <h3 style="font-size:1.1rem; margin-bottom:16px; color:#2b5352; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">Rincian Pesanan Anda</h3>
                    
                    <div style="max-height:200px; overflow-y:auto; margin-bottom:14px;">
                        @foreach($cartItems as $item)
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.85rem;">
                            <span>{{ $item['name'] }} ({{ $item['qty'] }}x)</span>
                            <span style="font-weight:700;">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="background:#f8fafc; border-radius:10px; padding:14px; font-size:0.9rem;">
                        <div style="display:flex; justify-content:space-between; color:#64748b;"><span>Subtotal:</span> <span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                        <div style="display:flex; justify-content:space-between; color:#64748b; margin-top:4px;"><span>Ongkir:</span> <span>Rp {{ number_format($shippingFee, 0, ',', '.') }}</span></div>
                        <div style="display:flex; justify-content:space-between; font-size:1rem; font-weight:700; color:#1e2a2d; border-top:1px dashed #cbd5e1; padding-top:8px; margin-top:8px;">
                            <span>Grand Total</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <!-- RINCIAN DP & SISA BAYAR -->
                        @if($paymentType === 'dp' && $paymentMethod !== 'cod')
                        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e2e8f0; display:flex; flex-direction:column; gap:4px;">
                            <div style="display:flex; justify-content:space-between; color:#0f766e; font-weight:700;">
                                <span>Bayar DP 50% (Sekarang):</span> 
                                <span>Rp {{ number_format($grandTotal * 0.5, 0, ',', '.') }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; color:#c2410c; font-weight:700; font-size:0.85rem;">
                                <span>Sisa Bayar (Saat Tiba):</span> 
                                <span>Rp {{ number_format($grandTotal * 0.5, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- ================= STEP 3: BERHASIL ================= -->
        @if($step === 3 && $createdOrder)
        <div style="max-width:700px; margin:0 auto; background:#fff; border-radius:20px; padding:36px; border:1px solid #e2e8f0; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
            <div style="text-align:center; margin-bottom:24px;">
                <div style="width:70px; height:70px; border-radius:50%; background:#10b981; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:2rem; margin-bottom:12px; box-shadow:0 4px 12px rgba(16,185,129,0.3);"><i class="fa-solid fa-check"></i></div>
                <h2 style="color:#0f172a; font-size:1.6rem; font-weight:800; margin-bottom:4px;">Pesanan Berhasil Dibuat!</h2>
                <p style="color:#64748b; font-size:0.9rem;">Terima kasih telah memesan. Pesanan Anda sedang diproses oleh dapur kami.</p>
            </div>

            <div style="background:#f8fafc; border-radius:14px; padding:20px; border:1px solid #e2e8f0; margin-bottom:24px;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; font-size:0.9rem; margin-bottom:14px; border-bottom:1px dashed #cbd5e1; padding-bottom:14px;">
                    <div>
                        <span style="color:#64748b; display:block; font-size:0.8rem;">ID Pesanan:</span>
                        <strong style="color:#2b5352; font-size:1rem;">{{ $createdOrder->order_code }}</strong>
                    </div>
                    <div>
                        <span style="color:#64748b; display:block; font-size:0.8rem;">Metode Pembayaran:</span>
                        <strong style="color:#1e293b; text-transform: uppercase;">
                            {{ $createdOrder->payment_method ?? $paymentMethod }} 
                            @if(!str_contains(strtolower($createdOrder->payment_method ?? ''), 'lunas') && ($createdOrder->payment_type ?? $paymentType) === 'lunas')
                                (LUNAS)
                            @endif
                        </strong>
                    </div>
                    <div>
                        <span style="color:#64748b; display:block; font-size:0.8rem;">Nama Penerima:</span>
                        <strong style="color:#1e293b;">{{ $createdOrder->recipient_name ?? $customerName }}</strong>
                    </div>
                    <div>
                        <span style="color:#64748b; display:block; font-size:0.8rem;">Nomor HP:</span>
                        <strong style="color:#1e293b;">{{ $createdOrder->phone_number }}</strong>
                    </div>
                </div>

                <div style="font-size:0.9rem; margin-bottom:12px;">
                    <span style="color:#64748b; display:block; font-size:0.8rem;">Alamat Pengiriman / Pengambilan:</span>
                    <p style="color:#334155; margin:2px 0 0 0; font-weight:600;">{{ $createdOrder->shipping_address }}</p>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; font-size:0.9rem; background:#fff; padding:12px; border-radius:8px; border:1px solid #e2e8f0;">
                    <div>
                        <span style="color:#64748b; display:block; font-size:0.75rem;">Jadwal Kirim:</span>
                        <strong>{{ $createdOrder->order_date }} ({{ $createdOrder->delivery_time }})</strong>
                    </div>
                    <div>
                        <span style="color:#64748b; display:block; font-size:0.75rem;">Total Pembayaran:</span>
                        <strong style="color:#2b5352; font-size:1.05rem;">Rp {{ number_format($createdOrder->total_price ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap:12px; justify-content:center;">
                <a href="{{ route('catalog') }}" style="flex:1; padding:12px 20px; background:#2b5352; color:#fff; border-radius:10px; text-decoration:none; font-weight:700; text-align:center;">Kembali ke Katalog</a>
            </div>
        </div>
        @endif

        @endif
    </div>

    <!-- SCRIPT PETA & AUTO-GEOCODING ALAMAT -->
    <script>
        let checkoutMap = null;
        let checkoutMarker = null;
        const STORE_LAT = -7.605652;
        const STORE_LNG = 110.638520;
        let addressTimer = null;

        function initCheckoutMap(lat = STORE_LAT, lng = STORE_LNG) {
            const mapEl = document.getElementById('checkout-map');
            if (!mapEl) return;

            setTimeout(() => {
                if (checkoutMap) {
                    checkoutMap.remove();
                    checkoutMap = null;
                }

                checkoutMap = L.map('checkout-map', {
                    zoomControl: true,
                    dragging: true
                }).setView([lat, lng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(checkoutMap);

                checkoutMarker = L.marker([lat, lng], { draggable: true }).addTo(checkoutMap);

                function updateCoords(lat, lng) {
                    const coordText = document.getElementById('coords-text');
                    if (coordText) {
                        coordText.innerText = lat.toFixed(6) + ', ' + lng.toFixed(6);
                    }
                    @this.set('latitude', lat, true);
                    @this.set('longitude', lng, true);
                }

                checkoutMarker.on('dragend', function () {
                    const pos = checkoutMarker.getLatLng();
                    updateCoords(pos.lat, pos.lng);
                });

                checkoutMap.on('click', function (e) {
                    checkoutMarker.setLatLng(e.latlng);
                    updateCoords(e.latlng.lat, e.latlng.lng);
                });

                checkoutMap.invalidateSize();
            }, 300);
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('initMap', () => {
                setTimeout(() => {
                    initCheckoutMap();
                }, 250);
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('checkout-map')) {
                initCheckoutMap();
            }
        });

        document.addEventListener('input', function (e) {
            if (e.target && e.target.id === 'shipping-address-input') {
                clearTimeout(addressTimer);
                const query = e.target.value.trim();
                if (query.length > 4) {
                    addressTimer = setTimeout(() => {
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.length > 0) {
                                    const lat = parseFloat(data[0].lat);
                                    const lon = parseFloat(data[0].lon);
                                    if (checkoutMap && checkoutMarker) {
                                        checkoutMap.setView([lat, lon], 15);
                                        checkoutMarker.setLatLng([lat, lon]);
                                        document.getElementById('coords-text').innerText = lat.toFixed(6) + ', ' + lon.toFixed(6);
                                        @this.set('latitude', lat, true);
                                        @this.set('longitude', lon, true);
                                        setTimeout(() => { checkoutMap.invalidateSize(); }, 200);
                                    }
                                }
                            })
                            .catch(err => console.log('Geocoding error:', err));
                    }, 800);
                }
            }
        });

        function locateMe() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    if (checkoutMap && checkoutMarker) {
                        checkoutMap.setView([lat, lng], 16);
                        checkoutMarker.setLatLng([lat, lng]);
                        document.getElementById('coords-text').innerText = lat.toFixed(6) + ', ' + lng.toFixed(6);
                        @this.set('latitude', lat, true);
                        @this.set('longitude', lng, true);
                        setTimeout(() => { checkoutMap.invalidateSize(); }, 200);
                    }
                }, function () {
                    alert('Akses GPS diblokir browser. Silakan geser pin secara manual.');
                }, { timeout: 10000 });
            }
        }
    </script>
</div>