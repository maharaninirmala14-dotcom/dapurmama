<div class="orders-page" style="width: 100%; box-sizing: border-box; max-width: 100%; overflow-x: hidden;">
    
    <style>
        /* CSS Responsif khusus untuk Halaman Manajemen Pesanan */
        .stats-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }
        .filter-search-container {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 992px) {
            .stats-cards-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 768px) {
            .stats-cards-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
            .filter-search-container {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 12px !important;
            }
            .filter-search-container > div {
                width: 100% !important;
            }
            .filter-search-container input, 
            .filter-search-container select {
                width: 100% !important;
                box-sizing: border-box !important;
            }
        }
    </style>

    <!-- Header Halaman -->
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">Manajemen Pesanan</h1>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Kelola status operasional dapur, jadwal pengiriman, dan rincian pesanan katering.</p>
    </div>

    <!-- 4 Kartu Statistik Ringkas (Responsif Otomatis) -->
    <div class="stats-cards-grid">
        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #0284c7; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Pesanan</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Sedang Diproses</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $processingOrders }}</div>
                </div>
            </div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fae8ff; display: flex; align-items: center; justify-content: center; color: #c026d3; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Siap Dikirim</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $shippingOrders }}</div>
                </div>
            </div>
        </div>

        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #15803d; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Pesanan Selesai</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $completedOrders }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar (Responsif) -->
    <div class="filter-search-container">
        <div style="position: relative; width: 100%;">
            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no. pesanan / nama pemesan..." style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; outline: none; box-sizing: border-box;">
        </div>

        <div style="width: 100%;">
            <select wire:model.live="statusFilter" style="width: 100%; padding: 9px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; background: #fff; color: #334155; outline: none; cursor: pointer; box-sizing: border-box;">
                <option value="">Semua Status Pesanan</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Diproses">Diproses</option>
                <option value="Siap Dikirim">Siap Dikirim</option>
                <option value="Selesai">Selesai</option>
                <option value="Dibatalkan">Dibatalkan</option>
            </select>
        </div>
    </div>

    <!-- Tabel Daftar Pesanan (Dengan Scroll Horizontal Mandiri) -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; width: 100%; box-sizing: border-box;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
            <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a; margin: 0;">Daftar Pesanan Masuk</h3>
        </div>

        <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; min-width: 650px;">
                <thead>
                    <tr style="background-color: #f8fafc; color: #64748b; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">NO. PESANAN</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">TANGGAL ACARA</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">PEMESAN</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">JUMLAH ITEM</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">STATUS PESANAN</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap; text-align: center;">AKSI / KELOLA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $order)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 16px; font-weight: 700; color: #0f172a; white-space: nowrap;">{{ $order->order_code }}</td>
                            <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $order->event_date ?? $order->created_at->format('d M Y') }}</td>
                            <td style="padding: 14px 16px; color: #334155; font-weight: 600; white-space: nowrap;">
                                {{ $order->customer_real_name ?? 'Pelanggan' }}
                                <div style="font-size: 0.75rem; color: #64748b; font-weight: normal;">{{ $order->customer_phone ?? '-' }}</div>
                            </td>
                            <td style="padding: 14px 16px; color: #475569; font-weight: 600; white-space: nowrap;">
                                @php
                                    $totalQty = $order->orderDetails->sum(function($item) {
                                        return $item->quantity ?? $item->qty ?? 1;
                                    });
                                    $isPackage = $order->orderDetails->contains(fn($item) => !is_null($item->package_id));
                                @endphp
                                {{ $totalQty }} {{ $isPackage ? 'Paket' : '' }}
                            </td>
                            <td style="padding: 14px 16px; white-space: nowrap;">
                                <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" 
                                    style="padding: 6px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; border: 1px solid #cbd5e1; outline: none; cursor: pointer;
                                    @if($order->status == 'Menunggu') background: #f1f5f9; color: #475569; 
                                    @elseif($order->status == 'Diproses') background: #ffedd5; color: #c2410c; 
                                    @elseif($order->status == 'Siap Dikirim') background: #fae8ff; color: #c026d3; 
                                    @elseif($order->status == 'Selesai') background: #dcfce7; color: #15803d; 
                                    @elseif($order->status == 'Dibatalkan') background: #fee2e2; color: #b91c1c; @endif">
                                    <option value="Menunggu" {{ $order->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                    <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>🔥 Diproses</option>
                                    <option value="Siap Dikirim" {{ $order->status == 'Siap Dikirim' ? 'selected' : '' }}>🚚 Siap Dikirim</option>
                                    <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                </select>
                            </td>
                            <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                                <button type="button" wire:click="showDetail({{ $order->id }})" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 6px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-eye" style="font-size: 11px;"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 30px; text-align: center; color: #94a3b8;">Tidak ada data pesanan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION MANUAL KOTAK RAPI DI TENGAH -->
        <div style="padding: 20px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; align-items: center; background: #ffffff;">
            @if ($transactions->hasPages())
                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap; justify-content: center;">
                    
                    {{-- Tombol Previous (<) --}}
                    @if ($transactions->onFirstPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #f8fafc; color: #cbd5e1; border: 1px solid #f1f5f9; border-radius: 8px; font-size: 0.85rem; cursor: not-allowed;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 12px;"></i>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage" wire:loading.attr="disabled" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #ffffff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 12px;"></i>
                        </button>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach (range(1, $transactions->lastPage()) as $page)
                        @if ($page == 1 || $page == $transactions->lastPage() || abs($page - $transactions->currentPage()) <= 2)
                            @if ($page == $transactions->currentPage())
                                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #2b5352; color: #ffffff; border: 1px solid #2b5352; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #ffffff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                    {{ $page }}
                                </button>
                            @endif
                        @elseif ($page == $transactions->currentPage() - 3 || $page == $transactions->currentPage() + 3)
                            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 38px; color: #94a3b8; font-size: 0.85rem;">...</span>
                        @endif
                    @endforeach

                    {{-- Tombol Next (>) --}}
                    @if ($transactions->hasMorePages())
                        <button type="button" wire:click="nextPage" wire:loading.attr="disabled" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #ffffff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i>
                        </button>
                    @else
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #f8fafc; color: #cbd5e1; border: 1px solid #f1f5f9; border-radius: 8px; font-size: 0.85rem; cursor: not-allowed;">
                            <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i>
                        </span>
                    @endif

                </div>
            @endif
        </div>
    </div>

    <!-- MODAL DETAIL PESANAN CUSTOMER -->
    @if($detailOrder)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; display: flex; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #fff; border-radius: 16px; width: 100%; max-width: 650px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.2); padding: 24px; text-align: left; box-sizing: border-box;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 16px;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #1e2a2d; margin: 0;">
                    Rincian Pesanan #{{ $detailOrder->order_code }}
                </h3>
                <button type="button" wire:click="closeDetail" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Informasi Pemesan -->
            <div style="margin-bottom: 16px; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <p style="margin: 0 0 4px 0; font-size: 0.85rem; color: #334155;"><strong>Nama Pelanggan:</strong> {{ optional($detailOrder->user)->name ?? '-' }}</p>
                <p style="margin: 0 0 4px 0; font-size: 0.85rem; color: #334155;"><strong>Tanggal Pesan:</strong> {{ $detailOrder->created_at->format('d M Y H:i') }}</p>
                <p style="margin: 0; font-size: 0.85rem; color: #334155;"><strong>Status Saat Ini:</strong> {{ $detailOrder->status }}</p>
            </div>

            <!-- Daftar Item Menu -->
            <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin: 0 0 10px 0;">Menu / Paket yang Dipesan:</h4>
            <div style="border: 1px solid #e2e8f0; border-radius: 10px; overflow-x: auto; margin-bottom: 16px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; min-width: 400px;">
                    <thead>
                        <tr style="background: #f1f5f9; color: #475569;">
                            <th style="padding: 10px 12px; text-align: left;">Menu/Paket</th>
                            <th style="padding: 10px 12px; text-align: center;">Jumlah</th>
                            <th style="padding: 10px 12px; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailOrder->orderDetails as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 12px; color: #1e2a2d; font-weight: 600;">
                                {{ $item->menu->name ?? $item->package->name ?? 'Produk Catering' }}
                            </td>
                            <td style="padding: 10px 12px; text-align: center; color: #475569;">
                                {{ $item->quantity ?? $item->qty ?? 1 }}x
                            </td>
                            <td style="padding: 10px 12px; text-align: right; color: #2b5352; font-weight: 700;">
                                Rp {{ number_format(($item->price ?? 0) * ($item->quantity ?? $item->qty ?? 1), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total & Rincian Pembayaran -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
                @php
                    $shippingFee = $detailOrder->shipping_fee ?? 0;
                    $subtotalMenu = $detailOrder->subtotal ?? max(($detailOrder->total_price ?? 0) - $shippingFee, 0);
                    $grandTotal = $subtotalMenu + $shippingFee;
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 0.88rem; color: #64748b;">
                    <span>Subtotal Menu:</span>
                    <span style="font-weight: 600; color: #1e2a2d;">Rp {{ number_format($subtotalMenu, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 0.88rem; color: #64748b;">
                    <span>Ongkos Kirim:</span>
                    <span style="font-weight: 600; color: #1e2a2d;">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; padding-top: 10px; font-size: 1rem; font-weight: 800; color: #1e2a2d;">
                    <span>Total Pembayaran:</span>
                    <span style="color: #2b5352; font-size: 1.15rem;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div style="text-align: right;">
                <button type="button" wire:click="closeDetail" style="background: #cbd5e1; color: #334155; border: none; padding: 9px 18px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    Tutup
                </button>
            </div>

        </div>
    </div>
    @endif
</div>