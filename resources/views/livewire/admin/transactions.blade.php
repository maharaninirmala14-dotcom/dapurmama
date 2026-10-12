<div class="transactions-page" style="width: 100%; box-sizing: border-box; max-width: 100%; overflow-x: hidden;">
    
    <style>
        /* CSS Responsif khusus untuk Halaman Transaksi */
        .trx-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
            width: 100%;
            box-sizing: border-box;
        }
        .trx-filter-container {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 992px) {
            .trx-stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 768px) {
            .trx-stats-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
            .trx-filter-container {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 12px !important;
            }
            .trx-filter-container > div {
                width: 100% !important;
            }
            .trx-filter-container input, 
            .trx-filter-container select {
                width: 100% !important;
                box-sizing: border-box !important;
            }
        }
    </style>

    <!-- Header Halaman -->
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">Transaksi & Keuangan</h1>
        <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Kelola rekap pembayaran, status transaksi, dan arus kas masuk.</p>
    </div>

    <!-- 4 Kartu Statistik Atas (Responsif Otomatis) -->
    <div class="trx-stats-grid">
        
        <!-- Card 1 -->
        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #0f766e; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Transaksi</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $totalTransactions }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">Keseluruhan</span>
                <span style="font-size: 0.8rem; color: #0f766e; font-weight: 600;">Arus Kas</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #15803d; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Pendapatan Hari Ini</div>
                    <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                <span style="font-size: 0.8rem; color: #15803d; font-weight: 600;">Pemasukan</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Pembayaran Lunas</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $completedTransactions }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">{{ $completedPercentage }}%</span>
                <span style="font-size: 0.8rem; color: #d97706; font-weight: 600;">Sukses</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #f3e8ff; display: flex; align-items: center; justify-content: center; color: #7e22ce; font-size: 18px; flex-shrink: 0;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Rata-Rata Transaksi</div>
                    <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">Per pembayaran</span>
                <span style="font-size: 0.8rem; color: #7e22ce; font-weight: 600;">Statistik</span>
            </div>
        </div>

    </div>

    <!-- Filter & Search Bar Panel (Responsif) -->
    <div class="trx-filter-container">
        <div style="position: relative; width: 100%;">
            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no. transaksi / pelanggan..." style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; outline: none; box-sizing: border-box;">
        </div>

        <div style="width: 100%;">
            <select wire:model.live="status" style="width: 100%; padding: 9px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; background: #fff; color: #334155; outline: none; cursor: pointer; box-sizing: border-box;">
                <option value="">Semua Status Pembayaran</option>
                <option value="paid">Lunas (Berhasil)</option>
                <option value="dp">DP 50% (Belum Lunas)</option>
                <option value="pending">Menunggu Pembayaran (COD)</option>
                <option value="failed">Gagal / Dibatalkan</option>
            </select>
        </div>
    </div>

    <!-- Tabel Daftar Keuangan & Transaksi (Dengan Scroll Horizontal Mandiri) -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; width: 100%; box-sizing: border-box;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
            <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a; margin: 0;">Riwayat Keuangan & Pembayaran Masuk</h3>
        </div>

        <div style="overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem; min-width: 700px;">
                <thead>
                    <tr style="background-color: #f8fafc; color: #64748b; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">NO. TRANSAKSI</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">TANGGAL BAYAR</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">PELANGGAN</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">NOMINAL (TOTAL)</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">METODE PEMBAYARAN</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap;">STATUS KEUANGAN</th>
                        <th style="padding: 12px 16px; font-weight: 700; white-space: nowrap; text-align: center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                            <td style="padding: 14px 16px; font-weight: 700; color: #0f172a; white-space: nowrap;">{{ $trx->order_code }}</td>
                            <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $trx->created_at->format('d M Y H:i') }}</td>
                            <td style="padding: 14px 16px; color: #334155; font-weight: 600; white-space: nowrap;">{{ $trx->user->name ?? 'Pelanggan' }}</td>
                            <td style="padding: 14px 16px; font-weight: 700; color: #0f766e; white-space: nowrap;">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                            <td style="padding: 14px 16px; color: #475569; white-space: nowrap;">{{ $trx->payment_method ?? 'Tunai' }}</td>
                            <td style="padding: 14px 16px; white-space: nowrap;">
                                @php
                                    $pStatus = $trx->payment_status;
                                    $pMethod = $trx->payment_method;
                                @endphp

                                @if($pStatus == 'paid' || str_contains(strtolower($pMethod), 'lunas'))
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fa-solid fa-check" style="font-size: 10px;"></i> Lunas
                                    </span>
                                @elseif(str_contains($pStatus, 'DP 50%') || str_contains($pMethod, 'DP 50%'))
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #fef3c7; color: #d97706; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fa-solid fa-clock" style="font-size: 10px;"></i> DP 50% (Belum Lunas)
                                    </span>
                                @elseif(str_contains($pStatus, 'COD') || str_contains($pMethod, 'COD') || $pStatus == 'pending')
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #fef3c7; color: #b45309; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fa-solid fa-clock" style="font-size: 10px;"></i> Menunggu Pembayaran (COD)
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fa-solid fa-xmark" style="font-size: 10px;"></i> Gagal / Batal
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                                <a href="{{ route('admin.orders') }}" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; padding: 6px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-file-invoice" style="font-size: 11px;"></i> Rincian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #94a3b8;">Tidak ada data transaksi keuangan yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION BERSIH & RAPI -->
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
</div>