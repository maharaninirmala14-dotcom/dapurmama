<div>
<style>
    /* ─── DASAR HALAMAN ─── */
    .bp-layout {
        width: 100%;
        max-width: 1380px;
        margin: 0 auto;
        padding: 24px 20px 80px;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        box-sizing: border-box;
        color: #1e293b;
    }

    /* ─── HEADER / BANNER ATAS ─── */
    .bp-header-box {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .bp-title-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 14px;
    }
    .bp-page-title {
        margin: 0;
        font-size: 1.45rem;
        font-weight: 800;
        color: #134e4a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bp-page-desc {
        margin: 4px 0 0;
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
    }
    .bp-badge-apriori {
        background: #f0fdfa;
        border: 1.5px solid #99f6e4;
        color: #0f766e;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 5px 14px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ─── FORM INPUT PEMESANAN ─── */
    .bp-form-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 0.8fr 1.3fr 1fr 1fr auto;
        gap: 12px;
        align-items: flex-end;
    }
    @media (max-width: 1280px) {
        .bp-form-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 768px) {
        .bp-form-grid {
            grid-template-columns: 1fr;
        }
    }
    .bp-field-box {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .bp-field-box label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bp-input-prefix-box {
        position: relative;
        display: flex;
        align-items: center;
    }
    .bp-input-prefix-box span {
        position: absolute;
        left: 14px;
        font-weight: 700;
        color: #64748b;
        font-size: 0.95rem;
        pointer-events: none;
    }
    .bp-input-control {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.92rem;
        font-weight: 700;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }
    .bp-input-control:focus {
        border-color: #134e4a;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(19, 78, 74, 0.12);
    }
    .bp-input-prefix {
        padding-left: 44px;
    }
    .bp-input-readonly {
        background: #f1f5f9;
        color: #0f766e;
        border-color: #99f6e4;
        cursor: not-allowed;
    }

    .bp-btn-search {
        background: #134e4a;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 11px 22px;
        font-size: 0.92rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        height: 44px;
        white-space: nowrap;
    }
    .bp-btn-search:hover {
        background: #0b3d39;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(19, 78, 74, 0.25);
    }

    /* ─── QUICK PILLS ─── */
    .bp-quick-wrap {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
    }
    .bp-quick-pills {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .bp-quick-pills .txt {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        min-width: 130px;
    }
    .bp-quick-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .bp-quick-btn:hover {
        background: #ccfbf1;
        color: #0f766e;
        border-color: #99f6e4;
    }
    .bp-quick-btn.active {
        background: #134e4a;
        color: #ffffff;
        border-color: #134e4a;
    }

    /* ─── APRIORI ALGORITHM TRANSPARENCY CARD ─── */
    .bp-apriori-card {
        background: #f0fdfa;
        border: 1.5px solid #99f6e4;
        border-radius: 14px;
        padding: 14px 18px;
        margin-top: 14px;
        font-size: 0.82rem;
    }
    .bp-apriori-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 800;
        color: #0f766e;
        margin-bottom: 8px;
        flex-wrap: wrap;
        gap: 6px;
    }
    .bp-apriori-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    @media (max-width: 860px) {
        .bp-apriori-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .bp-apriori-metric {
        background: #ffffff;
        border: 1px solid #ccfbf1;
        padding: 8px 12px;
        border-radius: 8px;
    }
    .bp-apriori-metric .m-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.78rem;
    }
    .bp-apriori-metric .m-meta {
        font-size: 0.72rem;
        color: #0f766e;
        font-weight: 600;
        margin-top: 2px;
    }

    /* ─── MAIN DUAL-COLUMN GRID ─── */
    .bp-main-grid {
        display: grid;
        grid-template-columns: 42% 58%;
        gap: 22px;
        align-items: start;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) {
        .bp-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ─── SECTION BOX / CARD ─── */
    .bp-card-box {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
    }
    .bp-card-head {
        padding: 16px 20px;
        border-bottom: 1.5px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .bp-card-head h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ─── SUB-NAV FILTER TABEL MENU KIRI ─── */
    .bp-subtabs {
        display: flex;
        gap: 6px;
        background: #e2e8f0;
        padding: 3px;
        border-radius: 8px;
    }
    .bp-subtab-btn {
        background: none;
        border: none;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .bp-subtab-btn.active {
        background: #ffffff;
        color: #134e4a;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* ─── TABEL UMUM ─── */
    .bp-table-divider-head {
        padding: 10px 18px;
        background: #f1f5f9;
        font-size: 0.8rem;
        font-weight: 800;
        color: #334155;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bp-table-container {
        width: 100%;
        overflow-x: auto;
    }
    .bp-table-scroll {
        max-height: 290px;
        overflow-y: auto;
    }
    .bp-pkg-table-scroll {
        max-height: 570px;
        overflow-y: auto;
    }
    .bp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        text-align: left;
    }
    .bp-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 14px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }
    .bp-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .bp-table tr:hover {
        background: #fbfcfe;
    }
    .bp-table-num {
        width: 40px;
        text-align: center;
        color: #94a3b8;
        font-weight: 700;
    }
    .bp-price-cell {
        font-weight: 800;
        color: #0f766e;
        text-align: right;
        white-space: nowrap;
    }
    .bp-pkg-subitem {
        font-size: 0.73rem;
        color: #64748b;
        margin-top: 2px;
    }
    .bp-total-cell {
        text-align: right;
        font-weight: 800;
        font-size: 0.95rem;
        color: #0f766e;
        white-space: nowrap;
    }
    .bp-remaining-cell {
        text-align: right;
        font-weight: 700;
        color: #15803d;
        white-space: nowrap;
    }

    /* ─── BADGES APRIORI ─── */
    .bp-badge-best {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        margin-top: 3px;
    }
    .bp-badge-conf {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .bp-badge-lift {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 6px;
        display: inline-block;
        white-space: nowrap;
    }
    .bp-badge-sup {
        background: #fdf4ff;
        color: #a21caf;
        border: 1px solid #f5d0fe;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 6px;
        display: inline-block;
        white-space: nowrap;
    }
    .bp-badge-alt {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .bp-tr-best {
        background: #f0fdfa !important;
    }

    .bp-btn-action-sm {
        background: #134e4a;
        color: #ffffff;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .bp-btn-action-sm:hover {
        background: #0f766e;
    }
    .bp-btn-action-outline {
        background: #ffffff;
        color: #0f766e;
        border: 1px solid #99f6e4;
    }
    .bp-btn-action-outline:hover {
        background: #f0fdfa;
    }

    /* ─── SECTION 3: ASSIGNMENT PER PORSI ─── */
    .bp-assign-section {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }
    .bp-assign-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 14px;
    }
    .bp-assign-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 14px;
    }
    .bp-assign-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: all 0.15s;
    }
    .bp-assign-card:hover {
        border-color: #99f6e4;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.06);
    }
    .bp-assign-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bp-assign-pkg-badge {
        font-weight: 800;
        font-size: 0.85rem;
        color: #134e4a;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bp-assign-pkg-price {
        font-weight: 800;
        font-size: 0.95rem;
        color: #0f766e;
    }
    .bp-assign-select {
        width: 100%;
        padding: 8px 10px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        background: #ffffff;
        color: #1e293b;
        outline: none;
    }
    .bp-assign-items-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 0.78rem;
        color: #475569;
        background: #ffffff;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #f1f5f9;
    }
    .bp-assign-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ─── SECTION 4: STICKY ORDER SUMMARY & SAVE ─── */
    .bp-order-summary-box {
        background: #134e4a;
        color: #ffffff;
        border-radius: 18px;
        padding: 22px 28px;
        box-shadow: 0 10px 25px rgba(19, 78, 74, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .bp-summary-metrics {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
    }
    .bp-metric-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .bp-metric-item .lbl {
        font-size: 0.75rem;
        color: #99f6e4;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
    }
    .bp-metric-item .val {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
    }
    .bp-btn-save-order {
        background: #10b981;
        color: #ffffff;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
        transition: all 0.2s;
    }
    .bp-btn-save-order:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }
    .bp-btn-save-order:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ─── MODAL SUKSES PESANAN ─── */
    .bp-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(5px);
        z-index: 2500;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .bp-modal-dialog {
        background: #ffffff;
        border-radius: 20px;
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
    }
    .bp-modal-head-success {
        background: #f0fdf4;
        border-bottom: 2px solid #bbf7d0;
        padding: 24px;
        text-align: center;
        position: relative;
    }
    .bp-success-badge-icon {
        width: 60px;
        height: 60px;
        background: #22c55e;
        color: #ffffff;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    .bp-modal-head-success h3 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 800;
        color: #166534;
    }
    .bp-modal-head-success p {
        margin: 6px 0 0;
        font-size: 0.88rem;
        color: #4b5563;
    }
    .bp-modal-body {
        padding: 24px;
    }
    .bp-summary-kv-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    @media (max-width: 640px) {
        .bp-summary-kv-grid {
            grid-template-columns: 1fr;
        }
    }
    .bp-kv-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
    }
    .bp-kv-card .k {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }
    .bp-kv-card .v {
        font-size: 1rem;
        font-weight: 800;
        color: #1e293b;
        margin-top: 3px;
    }

    .bp-empty-box {
        padding: 44px 20px;
        text-align: center;
        color: #64748b;
    }
    .bp-empty-box h4 {
        margin: 12px 0 6px;
        color: #1e293b;
        font-size: 1.05rem;
        font-weight: 800;
    }
    .bp-empty-box p {
        margin: 0 auto;
        max-width: 440px;
        font-size: 0.85rem;
        line-height: 1.5;
    }
</style>

<div class="bp-layout">

    <!-- 1. HEADER & FORM INPUT PEMESANAN PELANGGAN -->
    <div class="bp-header-box">
        <div class="bp-title-wrap">
            <div>
                <h1 class="bp-page-title">
                    <i class="fa-solid fa-chart-line" style="color: #0d9488;"></i>
                    Sistem Rekomendasi Menu Berdasarkan Budget & Algoritma Apriori
                </h1>
                <p class="bp-page-desc">
                    Sistem rekomendasi paket makanan dan minuman dinamis berbasis <strong>Data Mining Algoritma Apriori (Market Basket Analysis)</strong> berdasarkan data transaksi aktual di database MySQL.
                </p>
            </div>
            <div class="bp-badge-apriori">
                <i class="fa-solid fa-network-wired"></i> Algoritma Apriori Dinamis
            </div>
        </div>

        <!-- FORM PEMESANAN UTAMA -->
        <form wire:submit.prevent="recommend">
            <div class="bp-form-grid">

                <!-- 1. Nama Pemesan -->
                <div class="bp-field-box">
                    <label for="customerNameInput">
                        <i class="fa-solid fa-user" style="color: #0f766e;"></i> Nama Pemesan
                    </label>
                    <input type="text" 
                           id="customerNameInput" 
                           wire:model.live.debounce.300ms="customerName" 
                           class="bp-input-control" 
                           placeholder="Contoh: Budi" 
                           required>
                </div>

                <!-- 2. Total Budget -->
                <div class="bp-field-box">
                    <label for="totalBudgetInput">
                        <i class="fa-solid fa-money-bill-wave" style="color: #0f766e;"></i> Total Budget
                    </label>
                    <div class="bp-input-prefix-box">
                        <span>Rp</span>
                        <input type="number" 
                               id="totalBudgetInput" 
                               wire:model.live.debounce.400ms="totalBudget" 
                               class="bp-input-control bp-input-prefix" 
                               placeholder="150000" 
                               min="1000" 
                               step="500" 
                               required>
                    </div>
                </div>

                <!-- 3. Jumlah Orang / Porsi -->
                <div class="bp-field-box">
                    <label for="packageQtyInput">
                        <i class="fa-solid fa-boxes-stacked" style="color: #0f766e;"></i> Jumlah Paket / Porsi
                    </label>
                    <input type="number" 
                           id="packageQtyInput" 
                           wire:model.live.debounce.400ms="packageQuantity" 
                           class="bp-input-control" 
                           placeholder="5" 
                           min="1" 
                           step="1" 
                           required>
                </div>

                <!-- 4. Menu Makanan yang Dipilih Pengguna (Opsional) -->
                <div class="bp-field-box">
                    <label for="selectedMenuInput">
                        <i class="fa-solid fa-bowl-food" style="color: #0f766e;"></i> Menu Utama Dipilih
                    </label>
                    <select id="selectedMenuInput" wire:model.live="selectedMenuId" class="bp-input-control" style="font-weight: 700;">
                        <option value="">-- Semua Menu (Pola Apriori Terpopuler) --</option>
                        @foreach($availableFoodMenus as $fMenu)
                            <option value="{{ $fMenu['id'] }}">
                                {{ $fMenu['name'] }} (Rp {{ number_format($fMenu['price'], 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 5. Tipe Paket -->
                <div class="bp-field-box">
                    <label for="packageTypeSelect">
                        <i class="fa-solid fa-layer-group" style="color: #0f766e;"></i> Tipe Paket
                    </label>
                    <select id="packageTypeSelect" wire:model.live="packageType" class="bp-input-control" style="font-weight: 700;">
                        <option value="complete">Paket Lengkap (+ Snack)</option>
                        <option value="food_drink">Makanan + Minuman Saja</option>
                    </select>
                </div>

                <!-- 6. Rata-rata Budget per Porsi (Auto-Calculate) -->
                <div class="bp-field-box">
                    <label for="avgBudgetDisplay" title="Dihitung otomatis: Total Budget ÷ Jumlah Porsi">
                        <i class="fa-solid fa-divide" style="color: #0f766e;"></i> Budget / Porsi
                    </label>
                    <div class="bp-input-prefix-box">
                        <span>Rp</span>
                        <input type="text" 
                               id="avgBudgetDisplay" 
                               value="{{ number_format($averageBudget, 0, ',', '.') }}" 
                               class="bp-input-control bp-input-prefix bp-input-readonly" 
                               readonly 
                               title="Rata-rata Budget per Porsi = Total Budget ÷ Jumlah Porsi">
                    </div>
                </div>

                <!-- 7. Tombol Cari Rekomendasi -->
                <div>
                    <button type="submit" class="bp-btn-search" wire:loading.attr="disabled">
                        <span wire:loading wire:target="recommend">
                            <i class="fa-solid fa-spinner fa-spin"></i> Menghitung...
                        </span>
                        <span wire:loading.remove wire:target="recommend">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari Rekomendasi
                        </span>
                    </button>
                </div>

            </div>

            <!-- Pesan Error Validasi Form -->
            @error('customerName')
                <div style="color: #dc2626; font-size: 0.82rem; font-weight: 700; margin-top: 8px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </div>
            @enderror
            @error('totalBudget')
                <div style="color: #dc2626; font-size: 0.82rem; font-weight: 700; margin-top: 8px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </div>
            @enderror
            @error('packageQuantity')
                <div style="color: #dc2626; font-size: 0.82rem; font-weight: 700; margin-top: 8px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </div>
            @enderror

            <!-- QUICK PILLS WRAPPER -->
            <div class="bp-quick-wrap">
                <!-- Pilihan Cepat Total Budget -->
                <div class="bp-quick-pills">
                    <span class="txt"><i class="fa-solid fa-coins"></i> Cepat Budget:</span>
                    @foreach([50000, 100000, 150000, 250000, 500000] as $qVal)
                        <button type="button" 
                                wire:click="setQuickBudget({{ $qVal }})" 
                                class="bp-quick-btn {{ $totalBudget == $qVal ? 'active' : '' }}">
                            Rp {{ number_format($qVal, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>

                <!-- Pilihan Cepat Menu Makanan Utama -->
                <div class="bp-quick-pills">
                    <span class="txt"><i class="fa-solid fa-utensils"></i> Pilih Menu Makanan:</span>
                    <button type="button" 
                            wire:click="selectFoodMenu(null)" 
                            class="bp-quick-btn {{ empty($selectedMenuId) ? 'active' : '' }}">
                        🌟 Semua Menu (Populer)
                    </button>
                    @foreach(array_slice($availableFoodMenus, 0, 5) as $qFood)
                        <button type="button" 
                                wire:click="selectFoodMenu({{ $qFood['id'] }})" 
                                class="bp-quick-btn {{ $selectedMenuId == $qFood['id'] ? 'active' : '' }}">
                            🍚 {{ $qFood['name'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- KARTU PENJELASAN ALGORITMA APRIORI & FORMULA DATA MINING -->
            <div class="bp-apriori-card">
                <div class="bp-apriori-header">
                    <span>
                        <i class="fa-solid fa-circle-nodes"></i> Metode Data Mining Apriori (Market Basket Analysis) — Metrik Asosiasi:
                    </span>
                    <span style="font-size: 0.75rem; background: #ffffff; padding: 2px 8px; border-radius: 999px; border: 1px solid #99f6e4;">
                        Ambang Batas: Min. Support <strong>{{ round(($result['min_support'] ?? 0.01) * 100, 1) }}%</strong> • Min. Confidence <strong>{{ round(($result['min_confidence'] ?? 0.05) * 100, 1) }}%</strong>
                    </span>
                </div>
                <div class="bp-apriori-grid">
                    <div class="bp-apriori-metric">
                        <div class="m-name">1. Support (Frekuensi Pola)</div>
                        <div class="m-meta">Support(X) = Transaksi X ÷ Total Transaksi × 100%</div>
                    </div>
                    <div class="bp-apriori-metric">
                        <div class="m-name">2. Confidence (Tingkat Kepastian)</div>
                        <div class="m-meta">Confidence(X &rarr; Y) = Transaksi (X &cap; Y) ÷ Transaksi X × 100%</div>
                    </div>
                    <div class="bp-apriori-metric">
                        <div class="m-name">3. Lift (Kekuatan Asosiasi)</div>
                        <div class="m-meta">Lift(X &rarr; Y) = Confidence(X &rarr; Y) ÷ Support(Y) [Lift &gt; 1: Korelasi Positif]</div>
                    </div>
                    <div class="bp-apriori-metric">
                        <div class="m-name">4. Urutan Prioritas Paket</div>
                        <div class="m-meta">Relevansi Menu &rarr; Confidence Tertinggi &rarr; Lift &rarr; Support</div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <!-- 2. LAYOUT UTAMA DUA KOLOM: KIRI (TABEL MENU DATABASE) & KANAN (REKOMENDASI PAKET APRIORI) -->
    <div class="bp-main-grid">

        <!-- ============================================================== -->
        <!-- KOLOM KIRI: TIGA TABEL MENU TERPISAH (MAKANAN, MINUMAN, SNACK) -->
        <!-- ============================================================== -->
        <div class="bp-card-box">
            <div class="bp-card-head">
                <h3>
                    <i class="fa-solid fa-book-open" style="color: #0f766e;"></i>
                    Daftar Menu di Database
                </h3>
                <div class="bp-subtabs">
                    <button type="button" wire:click="setMenuTab('all')" class="bp-subtab-btn {{ $activeMenuTab === 'all' ? 'active' : '' }}">Semua</button>
                    <button type="button" wire:click="setMenuTab('makanan')" class="bp-subtab-btn {{ $activeMenuTab === 'makanan' ? 'active' : '' }}">Makanan</button>
                    <button type="button" wire:click="setMenuTab('minuman')" class="bp-subtab-btn {{ $activeMenuTab === 'minuman' ? 'active' : '' }}">Minuman</button>
                    <button type="button" wire:click="setMenuTab('snack')" class="bp-subtab-btn {{ $activeMenuTab === 'snack' ? 'active' : '' }}">Snack</button>
                </div>
            </div>

            <!-- TABEL A: MAKANAN -->
            @if($activeMenuTab === 'all' || $activeMenuTab === 'makanan')
                <div class="bp-table-divider-head">
                    <span>🍚 Tabel Makanan</span>
                    <span style="color: #64748b; font-size: 0.75rem;">{{ count($allMenus['makanan']) }} Menu</span>
                </div>
                <div class="bp-table-container bp-table-scroll">
                    <table class="bp-table">
                        <thead>
                            <tr>
                                <th class="bp-table-num">No.</th>
                                <th>Nama Menu Makanan</th>
                                <th style="text-align: right;">Harga Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allMenus['makanan'] as $idx => $m)
                                <tr>
                                    <td class="bp-table-num">{{ $idx + 1 }}</td>
                                    <td>
                                        <strong>{{ $m['name'] }}</strong>
                                        @if(!empty($m['is_favorite']))
                                            <span style="color: #d97706; font-size: 0.72rem; margin-left: 4px;"><i class="fa-solid fa-star"></i> Favorit</span>
                                        @endif
                                        @if(!empty($m['description']))
                                            <div style="font-size: 0.72rem; color: #64748b;">{{ Str::limit($m['description'], 45) }}</div>
                                        @endif
                                    </td>
                                    <td class="bp-price-cell">Rp {{ number_format($m['price'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center; color:#94a3b8; padding: 18px;">Belum ada menu makanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- TABEL B: MINUMAN -->
            @if($activeMenuTab === 'all' || $activeMenuTab === 'minuman')
                <div class="bp-table-divider-head">
                    <span>🥤 Tabel Minuman</span>
                    <span style="color: #64748b; font-size: 0.75rem;">{{ count($allMenus['minuman']) }} Menu</span>
                </div>
                <div class="bp-table-container bp-table-scroll">
                    <table class="bp-table">
                        <thead>
                            <tr>
                                <th class="bp-table-num">No.</th>
                                <th>Nama Menu Minuman</th>
                                <th style="text-align: right;">Harga Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allMenus['minuman'] as $idx => $d)
                                <tr>
                                    <td class="bp-table-num">{{ $idx + 1 }}</td>
                                    <td>
                                        <strong>{{ $d['name'] }}</strong>
                                    </td>
                                    <td class="bp-price-cell">Rp {{ number_format($d['price'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center; color:#94a3b8; padding: 18px;">Belum ada menu minuman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- TABEL C: SNACK -->
            @if($activeMenuTab === 'all' || $activeMenuTab === 'snack')
                <div class="bp-table-divider-head">
                    <span>🍪 Tabel Snack</span>
                    <span style="color: #64748b; font-size: 0.75rem;">{{ count($allMenus['snack']) }} Menu</span>
                </div>
                <div class="bp-table-container bp-table-scroll">
                    <table class="bp-table">
                        <thead>
                            <tr>
                                <th class="bp-table-num">No.</th>
                                <th>Nama Menu Snack</th>
                                <th style="text-align: right;">Harga Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allMenus['snack'] as $idx => $s)
                                <tr>
                                    <td class="bp-table-num">{{ $idx + 1 }}</td>
                                    <td>
                                        <strong>{{ $s['name'] }}</strong>
                                    </td>
                                    <td class="bp-price-cell">Rp {{ number_format($s['price'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center; color:#94a3b8; padding: 18px;">Belum ada menu snack.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <div style="padding: 12px 18px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 0.75rem; color: #64748b; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fa-solid fa-database"></i> Seluruh data menu dan harga diambil langsung dari database MySQL.</span>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.menus') }}" style="color: #0f766e; font-weight: 700; text-decoration: none;">Kelola di Admin &rarr;</a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- KOLOM KANAN: TABEL REKOMENDASI PAKET BERDASARKAN ALGORITMA APRIORI -->
        <!-- ============================================================== -->
        <div class="bp-card-box">
            <div class="bp-card-head" style="background: #f0fdfa; border-bottom: 2px solid #99f6e4;">
                <div>
                    <h3 style="color: #134e4a;">
                        <i class="fa-solid fa-ranking-star" style="color: #0d9488;"></i>
                        Tabel Rekomendasi Paket (Algoritma Apriori)
                    </h3>
                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                        Kombinasi dinamis berdasarkan pola transaksi historis • Alokasi Budget/Porsi: <strong>Rp {{ number_format($averageBudget, 0, ',', '.') }}</strong>
                    </div>
                </div>

                @if(!empty($result['packages']))
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @if(!empty($result['selected_menu']))
                            <span style="background: #ffffff; border: 1px solid #99f6e4; color: #0f766e; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                                Menu Dipilih: <strong>{{ $result['selected_menu']->name }}</strong>
                            </span>
                        @endif
                        <span style="background: #ffffff; border: 1px solid #99f6e4; color: #0f766e; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                            Ditemukan: <strong>{{ count($result['packages']) }} Paket</strong>
                        </span>
                    </div>
                @endif
            </div>

            <!-- KONTEN TABEL REKOMENDASI PAKET -->
            @if(!empty($result) && !empty($result['packages']))

                <div class="bp-table-container bp-pkg-table-scroll">
                    <table class="bp-table">
                        <thead>
                            <tr>
                                <th style="width: 75px; text-align: center;">Paket</th>
                                <th style="text-align: center;">Metrik Apriori</th>
                                <th>Menu Makanan (1x)</th>
                                <th>Menu Minuman (1x)</th>
                                @if(!empty($result['include_snack']))
                                    <th>Menu Snack (1x)</th>
                                @endif
                                <th style="text-align: right;">Harga / Porsi</th>
                                <th style="text-align: right;">Total {{ $packageQuantity }} Porsi</th>
                                <th style="text-align: right;">Sisa Budget</th>
                                <th style="text-align: center; width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['packages'] as $idx => $pkg)
                                @php
                                    $isBest = $pkg['is_best'] ?? ($idx === 0);
                                    $m = $pkg['makanan'];
                                    $d = $pkg['minuman'];
                                    $s = $pkg['snack'] ?? null;
                                    $paxMultiplier = $packageQuantity > 0 ? $packageQuantity : 1;
                                    $pricePerPax = (float) $pkg['price_per_pax'];
                                    $totalForPax = (float) ($pkg['total_package_price'] ?? ($pricePerPax * $paxMultiplier));
                                    $curBudget = (float) ($result['budget'] ?? $cleanBudget ?? $totalBudget);
                                    $remainingForTotal = max(0.0, $curBudget - $totalForPax);
                                    $remainingPerPax = max(0.0, $averageBudget - $pricePerPax);
                                @endphp
                                <tr class="{{ $isBest ? 'bp-tr-best' : '' }}">
                                    <!-- Nomor / Peringkat Paket -->
                                    <td style="text-align: center; font-weight: 800;">
                                        <div>#{{ $pkg['package_number'] ?? ($idx + 1) }}</div>
                                        @if($isBest)
                                            <span class="bp-badge-best">
                                                <i class="fa-solid fa-crown"></i> Rekomendasi Utama (Terbaik)
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Metrik Apriori (Confidence, Lift, Support) -->
                                    <td style="text-align: center;">
                                        @if(!empty($pkg['is_apriori_match']) && !empty($pkg['confidence']))
                                            <div style="display: flex; flex-direction: column; gap: 3px; align-items: center;">
                                                <span class="bp-badge-conf" title="Confidence: Seberapa sering {{ $d->name }} dibeli ketika membeli {{ $m->name }}">
                                                    <i class="fa-solid fa-bolt"></i> Conf: {{ $pkg['confidence'] }}%
                                                </span>
                                                <div style="display: flex; gap: 4px; justify-content: center;">
                                                    @if(!empty($pkg['lift']))
                                                        <span class="bp-badge-lift" title="Lift: Nilai kekuatan korelasi (> 1.0 korelasi positif)">
                                                            Lift: {{ $pkg['lift'] }}
                                                        </span>
                                                    @endif
                                                    @if(!empty($pkg['support']))
                                                        <span class="bp-badge-sup" title="Support: Frekuensi transaksi keseluruhan">
                                                            Sup: {{ $pkg['support'] }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="bp-badge-alt" title="{{ $pkg['rule_description'] ?? 'Alternatif menu sesuai anggaran' }}">
                                                <i class="fa-solid fa-circle-info"></i> Alternatif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Makanan (Nama & Harga Satuan) -->
                                    <td>
                                        <div style="font-weight: 700; color: #1e293b;">{{ $m->name }}</div>
                                        <div class="bp-pkg-subitem">1 porsi &bull; Rp {{ number_format($m->price, 0, ',', '.') }}</div>
                                    </td>

                                    <!-- Minuman (Nama & Harga Satuan) -->
                                    <td>
                                        <div style="font-weight: 700; color: #1e293b;">{{ $d->name }}</div>
                                        <div class="bp-pkg-subitem">1 porsi &bull; Rp {{ number_format($d->price, 0, ',', '.') }}</div>
                                    </td>

                                    <!-- Snack (Nama & Harga Satuan, jika aktif) -->
                                    @if(!empty($result['include_snack']))
                                        <td>
                                            @if($s)
                                                <div style="font-weight: 700; color: #1e293b;">{{ $s->name }}</div>
                                                <div class="bp-pkg-subitem">1 porsi &bull; Rp {{ number_format($s->price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                    @endif

                                    <!-- Harga Aktual per Porsi -->
                                    <td class="bp-total-cell">
                                        Rp {{ number_format($pricePerPax, 0, ',', '.') }}
                                        <div style="font-size: 0.72rem; color: #15803d; font-weight: 600;">
                                            Sisa: Rp {{ number_format($remainingPerPax, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    <!-- Total Harga untuk Seluruh Porsi -->
                                    <td style="text-align: right; font-weight: 800; color: #1e293b; white-space: nowrap;">
                                        Rp {{ number_format($totalForPax, 0, ',', '.') }}
                                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 600;">
                                            ({{ $packageQuantity }} &times; Rp {{ number_format($pricePerPax, 0, ',', '.') }})
                                        </div>
                                    </td>

                                    <!-- Sisa Budget Total -->
                                    <td class="bp-remaining-cell">
                                        Rp {{ number_format($remainingForTotal, 0, ',', '.') }}
                                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 600;">
                                            {{ $pkg['budget_usage_percent'] ?? round(($totalForPax / max(1, $curBudget)) * 100, 1) }}% terpakai
                                        </div>
                                    </td>

                                    <!-- Aksi: Terapkan ke Semua Paket atau Tambah ke Keranjang -->
                                    <td style="text-align: center;">
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: center;">
                                            <button type="button" 
                                                    wire:click="applyComboToAllPackages({{ $idx }})" 
                                                    class="bp-btn-action-sm" 
                                                    title="Terapkan kombinasi ini ke seluruh {{ $packageQuantity }} porsi pesanan">
                                                <i class="fa-solid fa-check"></i> Pakai Semua
                                            </button>
                                            <button type="button" 
                                                    wire:click="addPackageToCart({{ $idx }})" 
                                                    class="bp-btn-action-sm bp-btn-action-outline" 
                                                    title="Beli paket ke keranjang umum">
                                                <i class="fa-solid fa-cart-plus"></i> Beli
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding: 12px 18px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 0.78rem; color: #64748b; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <span><i class="fa-solid fa-check-double" style="color: #10b981;"></i> Urutan prioritas rekomendasi dihitung berdasarkan relevansi menu & nilai confidence, lift, dan support Apriori tertinggi.</span>
                    <span>Menampilkan {{ count($result['packages']) }} alternatif paket berbeda.</span>
                </div>

            @elseif(!empty($result) && empty($result['packages']))
                <div class="bp-empty-box">
                    <div style="font-size: 2.6rem; color: #d97706;">⚠️</div>
                    <h4>Belum Ada Paket yang Sesuai Budget per Porsi</h4>
                    <p>
                        Alokasi budget per porsi (<strong>Rp {{ number_format($averageBudget, 0, ',', '.') }}</strong>) belum mencukupi untuk paket minimal (Rp {{ number_format($result['min_package_price'] ?? 6000, 0, ',', '.') }}).
                    </p>
                    <div style="margin-top: 14px;">
                        <span style="font-size: 0.8rem; color: #475569;">
                            💡 Solusi: Naikkan <strong>Total Budget</strong> atau sesuaikan <strong>Jumlah Porsi</strong> agar alokasi budget per porsi mencukupi.
                        </span>
                    </div>
                </div>
            @else
                <div class="bp-empty-box">
                    <div style="font-size: 2.8rem; color: #0d9488;">🧮</div>
                    <h4>Menunggu Input Pemesanan</h4>
                    <p>
                        Silakan masukkan Nama Pemesan, Total Budget, Jumlah Porsi, serta Menu Makanan yang Dipilih (opsional), lalu klik <strong>"Cari Rekomendasi"</strong>.
                    </p>
                </div>
            @endif

        </div>

    </div>

    <!-- 3. BAGIAN RINCIAN PILIHAN PAKET UNTUK SETIAP PESANAN (PAKET 1 s.d. PAKET N) -->
    @if(!empty($result['packages']) && !empty($assignedPackages))
        <div class="bp-assign-section">
            <div class="bp-assign-header">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #134e4a; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-box-open" style="color: #0d9488;"></i>
                        Pilihan Paket untuk Setiap Pesanan (Porsi 1 s.d. {{ $packageQuantity }})
                    </h3>
                    <p style="margin: 4px 0 0; font-size: 0.83rem; color: #64748b;">
                        Setiap porsi paket otomatis didistribusikan dari alternatif rekomendasi Apriori yang berbeda sehingga memiliki <strong>isi makanan, minuman, dan snack yang bervariasi</strong>. Anda juga dapat mengubah pilihan paket masing-masing secara bebas.
                    </p>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="button" 
                            wire:click="syncAssignedPackages(true)" 
                            class="bp-btn-action-sm bp-btn-action-outline" 
                            style="padding: 8px 14px; font-size: 0.78rem;"
                            title="Distribusi ulang paket berbeda untuk setiap nomor paket">
                        <i class="fa-solid fa-shuffle"></i> Variasikan Semua Paket
                    </button>
                    <button type="button" 
                            wire:click="applyComboToAllPackages(0)" 
                            class="bp-btn-action-sm" 
                            style="padding: 8px 14px; font-size: 0.78rem;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Samakan ke Paket Utama
                    </button>
                </div>
            </div>

            <!-- GRID KARTU SETIAP PAKET -->
            <div class="bp-assign-grid">
                @for($pkgNum = 1; $pkgNum <= $packageQuantity; $pkgNum++)
                    @php
                        $curAssigned = $assignedPackages[$pkgNum] ?? null;
                        $selectedComboIdx = $curAssigned['package_index'] ?? 0;
                    @endphp
                    <div class="bp-assign-card">
                        <div class="bp-assign-card-head">
                            <span class="bp-assign-pkg-badge">
                                <i class="fa-solid fa-box"></i> Porsi / Paket #{{ $pkgNum }}
                                @if(!empty($curAssigned['confidence']))
                                    <span style="font-size: 0.7rem; color: #047857; font-weight: 700;">
                                        (Conf: {{ $curAssigned['confidence'] }}%)
                                    </span>
                                @endif
                            </span>
                            <span class="bp-assign-pkg-price">
                                Rp {{ number_format($curAssigned['total_price'] ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- DROPDOWN PEMILIHAN KOMBINASI -->
                        <div>
                            <select wire:change="selectComboForPackage({{ $pkgNum }}, $event.target.value)" class="bp-assign-select">
                                @foreach($result['packages'] as $cIdx => $cPkg)
                                    <option value="{{ $cIdx }}" {{ $cIdx == $selectedComboIdx ? 'selected' : '' }}>
                                        Kombinasi #{{ $cIdx + 1 }}: {{ $cPkg['makanan']->name }} + {{ $cPkg['minuman']->name }}{{ !empty($cPkg['snack']) ? ' + ' . $cPkg['snack']->name : '' }} (Rp {{ number_format($cPkg['total_price'], 0, ',', '.') }})
                                        {{ ($cIdx === 0) ? '👑 Rekomendasi Utama' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- RINCIAN MENU & HARGA SATUAN DALAM PAKET INI -->
                        @if($curAssigned)
                            <div class="bp-assign-items-list">
                                <div class="bp-assign-item-row">
                                    <span>🍚 {{ $curAssigned['food_name'] ?? '-' }}</span>
                                    <strong>Rp {{ number_format($curAssigned['food_price'] ?? 0, 0, ',', '.') }}</strong>
                                </div>
                                <div class="bp-assign-item-row">
                                    <span>🥤 {{ $curAssigned['drink_name'] ?? '-' }}</span>
                                    <strong>Rp {{ number_format($curAssigned['drink_price'] ?? 0, 0, ',', '.') }}</strong>
                                </div>
                                @if(!empty($curAssigned['snack_name']) && $curAssigned['snack_name'] !== '-')
                                    <div class="bp-assign-item-row">
                                        <span>🍪 {{ $curAssigned['snack_name'] }}</span>
                                        <strong>Rp {{ number_format($curAssigned['snack_price'] ?? 0, 0, ',', '.') }}</strong>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($curAssigned['rule_description']))
                                <div style="font-size: 0.7rem; color: #0f766e; background: #f0fdfa; padding: 6px 8px; border-radius: 6px; border: 1px solid #ccfbf1;">
                                    <i class="fa-solid fa-tag"></i> {{ $curAssigned['rule_description'] }}
                                </div>
                            @endif
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    @endif

    <!-- 4. RINGKASAN PEMESANAN & TOMBOL SIMPAN PEMESANAN -->
    @if(!empty($assignedPackages))
        <div class="bp-order-summary-box">
            <div class="bp-summary-metrics">
                <div class="bp-metric-item">
                    <span class="lbl">Nama Pemesan</span>
                    <span class="val" style="font-size: 1.1rem;">{{ $customerName ?: '(Belum diisi)' }}</span>
                </div>
                <div class="bp-metric-item">
                    <span class="lbl">Total Budget</span>
                    <span class="val">Rp {{ number_format($effectiveBudget, 0, ',', '.') }}</span>
                </div>
                <div class="bp-metric-item">
                    <span class="lbl">Jumlah Porsi</span>
                    <span class="val">{{ $packageQuantity }} Porsi</span>
                </div>
                <div class="bp-metric-item">
                    <span class="lbl">Total Harga Pesanan</span>
                    <span class="val" style="color: #6ee7b7;">Rp {{ number_format($totalOrderPrice, 0, ',', '.') }}</span>
                </div>
                <div class="bp-metric-item">
                    <span class="lbl">Sisa Total Budget</span>
                    <span class="val" style="color: {{ $totalOrderPrice <= $effectiveBudget ? '#93c5fd' : '#f87171' }};">
                        Rp {{ number_format($remainingTotalBudget, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div>
                <button type="button" 
                        wire:click="saveOrder" 
                        class="bp-btn-save-order"
                        wire:loading.attr="disabled"
                        {{ $totalOrderPrice > $effectiveBudget ? 'disabled' : '' }}>
                    <span wire:loading wire:target="saveOrder">
                        <i class="fa-solid fa-spinner fa-spin"></i> Memproses...
                    </span>
                    <span wire:loading.remove wire:target="saveOrder">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Pemesanan
                    </span>
                </button>
            </div>
        </div>

        @if($totalOrderPrice > $effectiveBudget)
            <div style="background: #fee2e2; border: 1.5px solid #f87171; color: #991b1b; padding: 12px 18px; border-radius: 12px; margin-top: 12px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Total harga pesanan (Rp {{ number_format($totalOrderPrice, 0, ',', '.') }}) melebihi total budget pelanggan (Rp {{ number_format($effectiveBudget, 0, ',', '.') }}). Silakan sesuaikan pilihan paket Anda agar memenuhi anggaran.
            </div>
        @endif

        @error('order')
            <div style="background: #fee2e2; border: 1.5px solid #f87171; color: #991b1b; padding: 12px 18px; border-radius: 12px; margin-top: 12px; font-size: 0.85rem; font-weight: 700;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </div>
        @enderror
    @endif

    <!-- 5. MODAL KONFIRMASI / RINGKASAN PESANAN BERHASIL DISIMPAN -->
    @if($showSuccessModal && $savedOrderSummary)
        <div class="bp-modal-backdrop" @click.self="$wire.closeSuccessModal()">
            <div class="bp-modal-dialog">
                
                <div class="bp-modal-head-success">
                    <div class="bp-success-badge-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3>Pemesanan Berhasil Disimpan!</h3>
                    <p>
                        Nomor Pesanan: <strong>{{ $savedOrderSummary->order_number }}</strong> • Tersimpan di database MySQL & riwayat admin.
                    </p>
                </div>

                <div class="bp-modal-body">
                    
                    <!-- KARTU RINGKASAN PESANAN -->
                    <div class="bp-summary-kv-grid">
                        <div class="bp-kv-card">
                            <div class="k">Nama Pemesan</div>
                            <div class="v">{{ $savedOrderSummary->customer_name }}</div>
                        </div>
                        <div class="bp-kv-card">
                            <div class="k">Jumlah Porsi</div>
                            <div class="v">{{ $savedOrderSummary->package_quantity }} Porsi</div>
                        </div>
                        <div class="bp-kv-card">
                            <div class="k">Total Budget</div>
                            <div class="v">Rp {{ number_format($savedOrderSummary->total_budget, 0, ',', '.') }}</div>
                        </div>
                        <div class="bp-kv-card">
                            <div class="k">Budget per Porsi</div>
                            <div class="v">Rp {{ number_format($savedOrderSummary->average_budget_per_package, 0, ',', '.') }}</div>
                        </div>
                        <div class="bp-kv-card">
                            <div class="k">Total Harga Pesanan</div>
                            <div class="v" style="color: #0f766e;">Rp {{ number_format($savedOrderSummary->total_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="bp-kv-card">
                            <div class="k">Sisa Budget</div>
                            <div class="v" style="color: #16a34a;">Rp {{ number_format($savedOrderSummary->remaining_budget, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if(!empty($savedOrderSummary->notes))
                        <div style="background: #f0fdfa; border: 1px solid #99f6e4; color: #0f766e; padding: 10px 14px; border-radius: 8px; font-size: 0.8rem; margin-bottom: 18px; font-weight: 600;">
                            <i class="fa-solid fa-circle-info"></i> {{ $savedOrderSummary->notes }}
                        </div>
                    @endif

                    <!-- TABEL RINCIAN TIAP PAKET DAN MENU YANG DIPILIH -->
                    <h4 style="margin: 0 0 10px; font-size: 0.95rem; font-weight: 800; color: #1e293b;">
                        <i class="fa-solid fa-list-check" style="color: #0d9488;"></i> Rincian Paket & Menu Terpilih:
                    </h4>

                    @php
                        $groupedItems = $savedOrderSummary->items->groupBy('package_number');
                    @endphp

                    <div style="border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 20px;">
                        <table class="bp-table" style="font-size: 0.82rem;">
                            <thead>
                                <tr>
                                    <th style="width: 75px; text-align: center;">Paket</th>
                                    <th>Makanan</th>
                                    <th>Minuman</th>
                                    <th>Snack</th>
                                    <th style="text-align: right;">Total Paket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedItems as $pNum => $pItems)
                                    @php
                                        $f = $pItems->firstWhere('menu_category', 'Makanan');
                                        $d = $pItems->firstWhere('menu_category', 'Minuman');
                                        $s = $pItems->firstWhere('menu_category', 'Snack');
                                        $subtotal = $pItems->sum('menu_price');
                                    @endphp
                                    <tr>
                                        <td style="text-align: center; font-weight: 800; background: #f8fafc;">
                                            Paket #{{ $pNum }}
                                        </td>
                                        <td>
                                            @if($f)
                                                <div style="font-weight: 700;">{{ $f->menu_name }}</div>
                                                <div style="font-size: 0.72rem; color: #64748b;">Rp {{ number_format($f->menu_price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($d)
                                                <div style="font-weight: 700;">{{ $d->menu_name }}</div>
                                                <div style="font-size: 0.72rem; color: #64748b;">Rp {{ number_format($d->menu_price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($s)
                                                <div style="font-weight: 700;">{{ $s->menu_name }}</div>
                                                <div style="font-size: 0.72rem; color: #64748b;">Rp {{ number_format($s->menu_price, 0, ',', '.') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">-</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; font-weight: 800; color: #0f766e;">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                        <button type="button" 
                                wire:click="createNewOrder" 
                                class="bp-btn-action-sm bp-btn-action-outline" 
                                style="padding: 10px 18px; font-size: 0.85rem;">
                            <i class="fa-solid fa-plus"></i> Buat Pemesanan Baru
                        </button>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.budget-orders') }}" 
                                   class="bp-btn-action-sm" 
                                   style="padding: 10px 18px; font-size: 0.85rem; text-decoration: none;">
                                    <i class="fa-solid fa-clipboard-list"></i> Lihat di Riwayat Admin &rarr;
                                </a>
                            @endif
                        @endauth
                        <button type="button" 
                                wire:click="closeSuccessModal" 
                                class="bp-btn-action-sm" 
                                style="padding: 10px 18px; font-size: 0.85rem;">
                            Tutup
                        </button>
                    </div>

                </div>

            </div>
        </div>
    @endif

</div>
</div>
