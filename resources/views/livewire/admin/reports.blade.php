@section('title', 'Laporan')
<div>
    <!-- CSS KHUSUS TAMPILAN CETAK / INVOICE E-COMMERCE -->
    <style>
        @media print {
            /* Sembunyikan elemen dashboard admin saat dicetak */
            .no-print, nav, aside, header, footer, .sidebar, .admin-sidebar, .admin-header {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            /* Tampilkan header khusus cetak dengan gaya invoice/laporan resmi */
            .print-report-header {
                display: block !important;
                text-align: center;
                border-bottom: 2px solid #134e4a;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            .print-report-header h1 {
                font-size: 1.5rem;
                font-weight: 800;
                color: #134e4a;
                margin: 0 0 5px 0;
            }
            .print-report-header p {
                font-size: 0.85rem;
                color: #555555;
                margin: 2px 0;
            }
            /* Rapikan tabel untuk cetak */
            .table-panel {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .admin-table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .admin-table th, .admin-table td {
                border: 1px solid #cbd5e1 !important;
                padding: 8px 12px !important;
                font-size: 0.82rem !important;
                color: #000 !important;
            }
            .admin-table th {
                background: #f1f5f9 !important;
                color: #134e4a !important;
            }
        }

        /* Sembunyikan header cetak khusus ini pada layar normal (non-print) */
        .print-report-header {
            display: none;
        }
    </style>

    <!-- Print Header (hanya muncul saat dicetak) -->
    <div class="print-report-header">
        <h1>DAPUR MAMA DELISHA</h1>
        <p><strong>LAPORAN RESMI USAHA CATERING</strong></p>
        <p>Jenis Laporan: {{ ucfirst($reportType) }} | Periode: {{ $startDate }} s/d {{ $endDate }}</p>
        <p>Dicetak pada: {{ now()->isoFormat('dddd, D MMMM Y HH:mm') }}</p>
    </div>

    <!-- Panel Filter & Tombol Aksi (Disembunyikan saat cetak) -->
    <div class="table-panel no-print" style="margin-bottom:24px;">
        <div class="table-header-bar" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                <select wire:model.live="reportType" class="form-control" style="width:220px;">
                    <option value="orders">Laporan Pesanan</option>
                    <option value="weekly">Laporan Mingguan</option>
                    <option value="monthly">Laporan Bulanan</option>
                </select>
                @if(in_array($reportType, ['orders', 'revenue', 'weekly', 'monthly']))
                <input type="date" wire:model.live="startDate" class="form-control" style="width:160px;">
                <span style="color:var(--text-muted);">s/d</span>
                <input type="date" wire:model.live="endDate" class="form-control" style="width:160px;">
                @endif
            </div>
            <div style="display:flex; gap:8px;">
                <button onclick="window.print()" class="btn-primary" style="padding:10px 16px; font-size:0.85rem; background:#134e4a; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                    <i class="fa-solid fa-print"></i> Cetak / PDF
                </button>
            </div>
        </div>
    </div>

    <!-- REPORT TABLE PANEL -->
    <div class="table-panel" style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="admin-table" style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                        @foreach($reportData['headers'] as $header)
                            <th style="padding:12px 16px; font-size:0.85rem; font-weight:700; color:#334155;">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['rows'] as $row)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        @foreach($row as $cell)
                            <td style="padding:12px 16px; font-size:0.85rem; color:#1e293b;">{{ $cell }}</td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($reportData['headers']) }}" style="text-align:center; color:#64748b; padding:40px;">Tidak ada data untuk laporan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(count($reportData['rows']) > 0)
        <div style="padding:16px 24px; border-top:1px solid #e2e8f0; background:#f8fafc; font-size:0.85rem; font-weight:700; color:#134e4a; display:flex; justify-content:space-between; align-items:center;">
            <span>Total Data: {{ count($reportData['rows']) }} baris</span>
            @if(in_array($reportType, ['revenue', 'orders', 'weekly', 'monthly']))
                <span>Total Nilai: Rp {{ number_format(collect($reportData['rows'])->sum(fn($r) => is_numeric($r[3] ?? 0) ? $r[3] : 0), 0, ',', '.') }}</span>
            @endif
        </div>
        @endif
    </div>

    <!-- Tanda Tangan Khusus Cetak (Hanya muncul saat dicetak) -->
    <div class="print-report-header" style="display:none; margin-top:40px; text-align:right; border:none;">
        <p>Klaten, {{ now()->isoFormat('D MMMM Y') }}</p>
        <p style="margin-top:50px;"><strong>Admin Dapur Mama Delisha</strong></p>
    </div>
</div>