<div>
    <style>
        .customer-orders-container {
            width: 100%;
            box-sizing: border-box;
            overflow-x: hidden !important;
        }
        .customer-orders-inner {
            padding: 30px 24px 60px 24px;
            max-width: 1280px;
            margin: 0 auto;
            box-sizing: border-box;
            width: 100%;
        }
        .orders-main-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
            width: 100%;
            box-sizing: border-box;
        }

        /* Responsive Media Query untuk HP & Tablet */
        @media (max-width: 1024px) {
            .orders-main-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 768px) {
            .customer-orders-inner {
                padding: 12px 10px 40px 10px !important;
            }
            .orders-main-grid {
                gap: 16px !important;
            }
            /* Menyesuaikan padding card di HP */
            .customer-orders-inner > div > div {
                padding: 16px !important;
            }
            /* Tombol tab kategori pesanan aktif/riwayat */
            .orders-tab-row {
                flex-direction: column !important;
                gap: 8px !important;
            }
            .orders-tab-row a {
                width: 100% !important;
                text-align: center !important;
                box-sizing: border-box !important;
            }
        }
    </style>

    <div class="customer-orders-container">
        <div class="customer-orders-inner">
            
            <!-- HEADER JUDUL -->
            <div style="margin-bottom: 20px;">
                <h1 style="font-size: 1.5rem; font-weight: 800; color: #1e2a2d; margin-bottom: 2px;">Pesanan Saya</h1>
                <p style="color: #64748b; font-size: 0.85rem;">Pantau status dan riwayat semua pesanan catering Anda</p>
            </div>

            <div class="orders-main-grid">
                
                <!-- BAGIAN KIRI: TABEL DAFTAR PESANAN -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); box-sizing: border-box; width: 100%; overflow: hidden;">
                    
                    <!-- TAB FILTER MENGGUNAKAN LINK URL BIASA -->
                    <div class="orders-tab-row" style="display: flex; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
                        <a href="{{ route('customer.orders') }}?tab=active" style="background: {{ ($activeTab ?? 'active') === 'active' ? '#1e5d5c' : '#f1f5f9' }}; color: {{ ($activeTab ?? 'active') === 'active' ? '#ffffff' : '#475569' }}; text-decoration: none; padding: 8px 18px; border-radius: 999px; font-weight: 700; font-size: 0.85rem; display: inline-block; box-sizing: border-box;">
                            Pesanan Aktif ({{ count($activeOrders ?? []) }})
                        </a>
                        <a href="{{ route('customer.orders') }}?tab=history" style="background: {{ ($activeTab ?? 'active') === 'history' ? '#1e5d5c' : '#f1f5f9' }}; color: {{ ($activeTab ?? 'history') === 'history' ? '#ffffff' : '#475569' }}; text-decoration: none; padding: 8px 18px; border-radius: 999px; font-weight: 700; font-size: 0.85rem; display: inline-block; box-sizing: border-box;">
                            Riwayat Pesanan ({{ count($historyOrders ?? []) }})
                        </a>
                    </div>

                    <!-- TABEL DATA -->
                    @php
                        $displayOrders = ($activeTab ?? 'active') === 'active' ? ($activeOrders ?? collect()) : ($historyOrders ?? collect());
                    @endphp

                    @if($displayOrders->isEmpty())
                        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                            <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
                            <p style="font-size: 0.95rem; font-weight: 600;">Belum ada pesanan di kategori ini.</p>
                        </div>
                    @else
                        <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; min-width: 550px;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.78rem; text-transform: uppercase;">
                                        <th style="padding-bottom: 12px; white-space: nowrap;">No. Pesanan</th>
                                        <th style="padding-bottom: 12px; white-space: nowrap;">Tanggal</th>
                                        <th style="padding-bottom: 12px; white-space: nowrap;">Total</th>
                                        <th style="padding-bottom: 12px; white-space: nowrap;">Status</th>
                                        <th style="padding-bottom: 12px; text-align: right; white-space: nowrap;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($displayOrders as $order)
                                    <tr style="border-bottom: 1px solid #f1f5f9;" wire:key="order-{{ $order->id }}">
                                        <td style="padding: 16px 0; white-space: nowrap;">
                                            <div style="font-weight: 700; color: #1e2a2d;">{{ $order->order_code }}</div>
                                            <div style="font-size: 0.75rem; color: #64748b;">
                                                {{ $order->orderDetails->count() }} item produk
                                            </div>
                                        </td>
                                        <td style="padding: 16px 0; color: #475569; white-space: nowrap;">
                                            <div>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</div>
                                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ $order->delivery_time }}</div>
                                        </td>
                                        <td style="padding: 16px 0; font-weight: 700; color: #1e5d5c; white-space: nowrap;">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        <td style="padding: 16px 0; white-space: nowrap;">
                                            @php
                                                $badgeBg = '#fef3c7'; $badgeColor = '#92400e';
                                                if($order->status === 'Diproses') { $badgeBg = '#e0f2fe'; $badgeColor = '#0369a1'; }
                                                elseif($order->status === 'Selesai') { $badgeBg = '#dcfce7'; $badgeColor = '#15803d'; }
                                                elseif($order->status === 'Dibatalkan') { $badgeBg = '#fee2e2'; $badgeColor = '#b91c1c'; }
                                            @endphp
                                            <span style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td style="padding: 16px 0; text-align: right; white-space: nowrap;">
                                            <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                                
                                                <button type="button" wire:click="showDetail({{ $order->id }})" style="background: #f1f5f9; border: none; color: #1e5d5c; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                                                    Detail
                                                </button>

                                                @if(in_array($order->status, ['Menunggu', 'Diproses']))
                                                    <button type="button" wire:click="confirmCancelOrder({{ $order->id }})" style="background: #fee2e2; border: none; color: #b91c1c; padding: 6px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                                                        Batal
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>

                <!-- BAGIAN KANAN: RINGKASAN & BANTUAN -->
                <div style="display: flex; flex-direction: column; gap: 20px; width: 100%; box-sizing: border-box;">
                    
                    <!-- Ringkasan Statistik Pesanan -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); box-sizing: border-box;">
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e2a2d; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                            Ringkasan Pesanan
                        </h3>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 0.88rem; color: #475569;">
                            <li style="display: flex; justify-content: space-between;">
                                <span>Semua Pesanan</span>
                                <span style="font-weight: 700; color: #1e2a2d;">{{ count($allOrders ?? []) }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between;">
                                <span><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#0369a1; margin-right:6px;"></span> Aktif / Diproses</span>
                                <span style="font-weight: 700; color: #1e2a2d;">{{ count($activeOrders ?? []) }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between;">
                                <span><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#15803d; margin-right:6px;"></span> Selesai</span>
                                <span style="font-weight: 700; color: #1e2a2d;">{{ ($allOrders ?? collect())->where('status', 'Selesai')->count() }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between;">
                                <span><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#b91c1c; margin-right:6px;"></span> Dibatalkan</span>
                                <span style="font-weight: 700; color: #1e2a2d;">{{ ($allOrders ?? collect())->where('status', 'Dibatalkan')->count() }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Bantuan WhatsApp -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); box-sizing: border-box;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fa-solid fa-headset" style="font-size: 1.3rem; color: #1e5d5c;"></i>
                            <h3 style="font-size: 1rem; font-weight: 700; color: #1e2a2d; margin: 0;">Butuh Bantuan?</h3>
                        </div>
                        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.4; margin-bottom: 14px;">
                            Ada kendala dengan pesanan catering Anda? Hubungi admin kami segera.
                        </p>
                        <a href="https://wa.me/6287738352816?text={{ urlencode('Halo Admin Dapur Mama Delisha, saya ingin bertanya terkait pesanan catering saya.') }}" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: center; gap: 6px; background: #25d366; color: #fff; padding: 10px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 0.85rem; box-sizing: border-box;">
                            <i class="fa-brands fa-whatsapp"></i> Hubungi Admin
                        </a>
                    </div>

                </div>

            </div>

            <!-- MODAL DETAIL PESANAN -->
            @if(isset($detailOrder) && $detailOrder)
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 15px; box-sizing: border-box;">
                <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; padding: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); box-sizing: border-box;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 16px;">
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e2a2d; margin: 0;">
                            Detail Pesanan {{ $detailOrder->order_code }}
                        </h3>
                        <button type="button" wire:click="closeDetail" style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div style="font-size: 0.85rem; color: #475569; display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        <div><strong>Nama Penerima:</strong> {{ $detailOrder->recipient_name }}</div>
                        <div><strong>No. WhatsApp:</strong> {{ $detailOrder->phone_number }}</div>
                        <div><strong>Alamat Pengiriman:</strong> {{ $detailOrder->shipping_address }}</div>
                        <div><strong>Tanggal Kirim:</strong> {{ $detailOrder->order_date }} ({{ $detailOrder->delivery_time }})</div>
                        <div><strong>Metode Pembayaran:</strong> {{ $detailOrder->payment_method }}</div>
                        <div><strong>Status:</strong> <span style="font-weight: 700; color: #1e5d5c;">{{ $detailOrder->status }}</span></div>
                    </div>

                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin-bottom: 10px;">Daftar Item Menu:</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;">
                        @foreach($detailOrder->orderDetails as $detail)
                        @php
                            $menuName = optional($detail->menu)->name 
                                    ?? optional($detail->package)->name 
                                    ?? (isset($detail->menu_id) ? optional(\App\Models\Menu::find($detail->menu_id))->name : null)
                                    ?? 'Menu Catering';
                        @endphp
                        <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; box-sizing: border-box;">
                            <div>
                                <div style="font-weight: 700; color: #1e2a2d;">
                                    {{ $menuName }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $detail->qty }}x @ Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
                            </div>
                            <div style="font-weight: 700; color: #1e5d5c; white-space: nowrap;">
                                Rp {{ number_format($detail->total, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 14px; font-weight: 800; font-size: 1.05rem; color: #1e2a2d;">
                        <span>Total Pembayaran</span>
                        <span style="color: #1e5d5c;">Rp {{ number_format($detailOrder->total_price, 0, ',', '.') }}</span>
                    </div>

                    <div style="margin-top: 24px; text-align: right;">
                        <button type="button" wire:click="closeDetail" style="background: #1e5d5c; color: #ffffff; border: none; padding: 8px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 0.85rem;">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- MODAL KONFIRMASI BATALKAN PESANAN -->
    @if($showCancelModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 30px 24px; text-align: center;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #b91c1c; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 16px auto;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div style="font-weight: 700; color: #1e2a2d; font-size: 0.95rem; margin-bottom: 8px;">Batalkan Pesanan</div>
            <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">Pesanan #{{ $cancelOrderCode }} akan dibatalkan dan tidak dapat diproses kembali.</div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" wire:click="closeCancelModal" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                <button type="button" wire:click="cancelOrder" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #b91c1c; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Ya, Batalkan</button>
            </div>
        </div>
    </div>
    @endif
</div>