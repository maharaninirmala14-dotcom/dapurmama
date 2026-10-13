<div>
<style>
    .bo-page {
        padding: 10px 0 40px;
    }
    .bo-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .bo-header {
        padding: 22px 26px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .bo-header h2 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bo-filter-bar {
        padding: 18px 26px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .bo-input {
        padding: 9px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.88rem;
        color: #1e293b;
        background: #ffffff;
        outline: none;
    }
    .bo-input:focus {
        border-color: #2b5352;
    }
    .bo-table-wrap {
        overflow-x: auto;
        width: 100%;
    }
    .bo-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
        text-align: left;
    }
    .bo-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 16px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }
    .bo-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .bo-table tr:hover {
        background-color: #fbfcfe;
    }
    .bo-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
    }
    .bo-status-menunggu { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .bo-status-diproses { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .bo-status-selesai { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .bo-status-batal { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    .bo-btn-detail {
        background: #f0fdfa;
        color: #0f766e;
        border: 1.5px solid #99f6e4;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }
    .bo-btn-detail:hover {
        background: #ccfbf1;
        color: #115e59;
    }

    /* ─── MODAL ─── */
    .bo-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .bo-modal-dialog {
        background: #ffffff;
        border-radius: 20px;
        max-width: 840px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
    }
    .bo-modal-head {
        padding: 20px 26px;
        border-bottom: 1.5px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }
    .bo-modal-head h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e293b;
    }
    .bo-modal-body {
        padding: 24px 26px;
        flex: 1;
        overflow-y: auto;
    }
    .bo-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 22px;
        background: #f8fafc;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    .bo-detail-item .lbl {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
    }
    .bo-detail-item .val {
        font-size: 0.98rem;
        font-weight: 800;
        color: #1e293b;
        margin-top: 3px;
    }
</style>

<div class="bo-page">

    <div class="bo-card">
        
        <!-- HEADER -->
        <div class="bo-header">
            <div>
                <h2>
                    <i class="fa-solid fa-clipboard-list" style="color: #2b5352;"></i>
                    Riwayat Pemesanan Berdasarkan Budget
                </h2>
                <div style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">
                    Semua pesanan pelanggan yang disimpan dari sistem rekomendasi budget tersimpan permanen di sini.
                </div>
            </div>
            <div>
                <span style="background: #e6fffa; color: #2b5352; border: 1px solid #b2f5ea; padding: 6px 14px; border-radius: 999px; font-weight: 700; font-size: 0.8rem;">
                    Total: {{ $orders->total() }} Pesanan
                </span>
            </div>
        </div>

        <!-- FILTER BAR -->
        <div class="bo-filter-bar">
            <!-- Pencarian -->
            <div style="flex: 1; min-width: 220px;">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari nama pemesan atau nomor order..." 
                       class="bo-input" 
                       style="width: 100%;">
            </div>

            <!-- Filter Status -->
            <select wire:model.live="statusFilter" class="bo-input">
                <option value="">Semua Status</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Diproses">Diproses</option>
                <option value="Selesai">Selesai</option>
                <option value="Batal">Batal</option>
            </select>

            <!-- Filter Tanggal Mulai -->
            <div>
                <input type="date" wire:model.live="startDate" class="bo-input" title="Dari Tanggal">
            </div>

            <!-- Filter Tanggal Selesai -->
            <div>
                <input type="date" wire:model.live="endDate" class="bo-input" title="Sampai Tanggal">
            </div>

            @if($search || $statusFilter || $startDate || $endDate)
                <button type="button" wire:click="resetFilters" style="background:#fee2e2; color:#991b1b; border:none; padding:8px 12px; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer;">
                    <i class="fa-solid fa-xmark"></i> Reset Filter
                </button>
            @endif
        </div>

        <!-- TABEL RIWAYAT PESANAN -->
        <div class="bo-table-wrap">
            <table class="bo-table">
                <thead>
                    <tr>
                        <th>Nomor Pesanan</th>
                        <th>Waktu Pemesanan</th>
                        <th>Nama Pemesan</th>
                        <th style="text-align: right;">Total Budget</th>
                        <th style="text-align: center;">Jumlah Paket</th>
                        <th style="text-align: right;">Budget/Paket</th>
                        <th style="text-align: right;">Total Harga</th>
                        <th style="text-align: right;">Sisa Budget</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <!-- Nomor Pesanan -->
                            <td style="font-weight: 800; color: #1e293b;">
                                {{ $order->order_number }}
                            </td>

                            <!-- Tanggal & Waktu -->
                            <td style="font-size: 0.82rem; color: #64748b;">
                                <div>{{ $order->created_at->format('d M Y') }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">{{ $order->created_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Nama Pemesan -->
                            <td>
                                <div style="font-weight: 700; color: #1e293b;">{{ $order->customer_name }}</div>
                                @if(!empty($order->notes))
                                    <div style="font-size: 0.72rem; color: #0f766e; margin-top: 2px;">
                                        <i class="fa-solid fa-tag"></i> {{ Str::limit($order->notes, 45) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Total Budget -->
                            <td style="text-align: right; font-weight: 700;">
                                Rp {{ number_format($order->total_budget, 0, ',', '.') }}
                            </td>

                            <!-- Jumlah Paket -->
                            <td style="text-align: center;">
                                <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.85rem;">
                                    {{ $order->package_quantity }} Paket
                                </span>
                            </td>

                            <!-- Rata-rata Budget per Paket -->
                            <td style="text-align: right; color: #64748b;">
                                Rp {{ number_format($order->average_budget_per_package, 0, ',', '.') }}
                            </td>

                            <!-- Total Harga Pesanan -->
                            <td style="text-align: right; font-weight: 800; color: #0f766e;">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>

                            <!-- Sisa Budget -->
                            <td style="text-align: right; font-weight: 700; color: #15803d;">
                                Rp {{ number_format($order->remaining_budget, 0, ',', '.') }}
                            </td>

                            <!-- Status Pemesanan -->
                            <td style="text-align: center;">
                                @php
                                    $st = strtolower($order->status);
                                    $badgeClass = match($st) {
                                        'diproses' => 'bo-status-diproses',
                                        'selesai'  => 'bo-status-selesai',
                                        'batal'    => 'bo-status-batal',
                                        default    => 'bo-status-menunggu',
                                    };
                                @endphp
                                <span wire:click="openUpdateStatus({{ $order->id }})" 
                                      class="bo-badge-status {{ $badgeClass }}" 
                                      title="Klik untuk ubah status">
                                    {{ $order->status }} <i class="fa-solid fa-pen" style="font-size: 0.65rem;"></i>
                                </span>
                            </td>

                            <!-- Tombol Detail -->
                            <td style="text-align: center;">
                                <button type="button" wire:click="viewDetail({{ $order->id }})" class="bo-btn-detail">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>
                                Belum ada riwayat pemesanan yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINASI -->
        <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
            {{ $orders->links() }}
        </div>

    </div>

    <!-- MODAL DETAIL RINCIAN PESANAN -->
    @if($showDetailModal && $selectedOrder)
        <div class="bo-modal-backdrop" @click.self="$wire.closeDetail()">
            <div class="bo-modal-dialog">
                
                <div class="bo-modal-head">
                    <div>
                        <h3>Rincian Pesanan #{{ $selectedOrder->order_number }}</h3>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                            Dipesan pada {{ $selectedOrder->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                    <button type="button" wire:click="closeDetail" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#64748b;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="bo-modal-body">
                    
                    <!-- RINGKASAN PEMESAN -->
                    <div class="bo-detail-grid">
                        <div class="bo-detail-item">
                            <div class="lbl">Nama Pemesan</div>
                            <div class="val">{{ $selectedOrder->customer_name }}</div>
                        </div>
                        <div class="bo-detail-item">
                            <div class="lbl">Total Budget</div>
                            <div class="val">Rp {{ number_format($selectedOrder->total_budget, 0, ',', '.') }}</div>
                        </div>
                        <div class="bo-detail-item">
                            <div class="lbl">Jumlah Paket</div>
                            <div class="val">{{ $selectedOrder->package_quantity }} Paket</div>
                        </div>
                        <div class="bo-detail-item">
                            <div class="lbl">Rata-rata Budget/Paket</div>
                            <div class="val">Rp {{ number_format($selectedOrder->average_budget_per_package, 0, ',', '.') }}</div>
                        </div>
                        <div class="bo-detail-item">
                            <div class="lbl">Total Harga Pesanan</div>
                            <div class="val" style="color: #0f766e;">Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="bo-detail-item">
                            <div class="lbl">Sisa Budget</div>
                            <div class="val" style="color: #15803d;">Rp {{ number_format($selectedOrder->remaining_budget, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if(!empty($selectedOrder->notes))
                        <div style="background: #f0fdfa; border: 1.5px solid #99f6e4; color: #0f766e; padding: 12px 16px; border-radius: 10px; font-size: 0.82rem; margin-bottom: 18px; font-weight: 600;">
                            <i class="fa-solid fa-calculator"></i> <strong>Parameter Rekomendasi SAW:</strong> {{ $selectedOrder->notes }}
                        </div>
                    @endif

                    <!-- TABEL RINCIAN TIAP PAKET -->
                    <h4 style="margin: 0 0 12px; font-size: 1rem; font-weight: 800; color: #1e293b;">
                        Rincian Paket Menu (Tiap Paket: Makanan, Minuman, & Harga Satuan)
                    </h4>

                    @php
                        $packagesGrouped = $selectedOrder->items->groupBy('package_number');
                    @endphp

                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 18px;">
                        <table class="bo-table" style="font-size: 0.85rem;">
                            <thead>
                                <tr>
                                    <th style="width: 80px; text-align: center;">Paket</th>
                                    <th>Menu Makanan</th>
                                    <th>Menu Minuman</th>
                                    <th>Menu Snack</th>
                                    <th style="text-align: right;">Total Paket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packagesGrouped as $pkgNum => $items)
                                    @php
                                        $food  = $items->firstWhere('menu_category', 'Makanan');
                                        $drink = $items->firstWhere('menu_category', 'Minuman');
                                        $snack = $items->firstWhere('menu_category', 'Snack');
                                        $pkgSubtotal = $items->sum('menu_price');
                                    @endphp
                                    <tr>
                                        <td style="text-align: center; font-weight: 800; background: #f8fafc;">
                                            Paket #{{ $pkgNum }}
                                        </td>
                                        <td>
                                            @if($food)
                                                <div style="font-weight: 700; color: #1e293b;">{{ $food->menu_name }}</div>
                                                <div style="font-size: 0.75rem; color: #64748b;">Rp {{ number_format($food->menu_price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($drink)
                                                <div style="font-weight: 700; color: #1e293b;">{{ $drink->menu_name }}</div>
                                                <div style="font-size: 0.75rem; color: #64748b;">Rp {{ number_format($drink->menu_price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($snack)
                                                <div style="font-weight: 700; color: #1e293b;">{{ $snack->menu_name }}</div>
                                                <div style="font-size: 0.75rem; color: #64748b;">Rp {{ number_format($snack->menu_price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; font-weight: 800; color: #0f766e;">
                                            Rp {{ number_format($pkgSubtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div style="padding: 16px 26px; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: flex-end;">
                    <button type="button" wire:click="closeDetail" class="bo-input" style="cursor: pointer; font-weight: 700;">
                        Tutup Rincian
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- MODAL UBAH STATUS PESANAN -->
    @if($showStatusModal)
        <div class="bo-modal-backdrop" @click.self="$wire.closeStatusModal()">
            <div class="bo-modal-dialog" style="max-width: 420px;">
                <div class="bo-modal-head">
                    <h3 style="font-size: 1.1rem;">Ubah Status Pesanan</h3>
                    <button type="button" wire:click="closeStatusModal" style="background:none; border:none; font-size:1.1rem; cursor:pointer;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="bo-modal-body">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                        Pilih Status Baru:
                    </label>
                    <select wire:model="newStatus" class="bo-input" style="width: 100%; padding: 10px;">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Batal">Batal</option>
                    </select>
                </div>
                <div style="padding: 14px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" wire:click="closeStatusModal" class="bo-input" style="cursor: pointer;">Batal</button>
                    <button type="button" wire:click="updateStatus" class="bo-btn-detail" style="background:#2b5352; color:#fff; border-color:#2b5352;">
                        Simpan Status
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
</div>
