<div>
<style>
    /* ─── DASAR HALAMAN ─── */
    .bp-page { width: 100%; max-width: 100%; box-sizing: border-box; background: #f8fafc; min-height: 100vh; overflow-x: hidden; }
    .bp-inner { padding: 30px 24px 60px; max-width: 1280px; margin: 0 auto; box-sizing: border-box; width: 100%; }

    /* ─── HEADER ─── */
    .bp-hero { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; }
    .bp-hero-badge {
        width: 48px; height: 48px; flex-shrink: 0; border-radius: 14px;
        background: #f0fdfa; border: 1.5px solid #99f6e4; color: #134e4a;
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
    }
    .bp-hero h1 { font-size: 1.45rem; font-weight: 800; color: #1e2a2d; margin: 0 0 4px; }
    .bp-hero p { color: #64748b; font-size: 0.85rem; margin: 0; line-height: 1.5; }

    /* ─── FORM BUDGET ─── */
    .bp-form-box {
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 14px;
        padding: 22px 26px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        display: flex; align-items: flex-end; justify-content: space-between; gap: 20px;
        flex-wrap: wrap; box-sizing: border-box; width: 100%;
    }
    .bp-field { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .bp-field label { font-size: 0.78rem; font-weight: 700; color: #475569; }
    .bp-field input {
        width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 9px;
        font-size: 0.9rem; outline: none; background: #fff; box-sizing: border-box; color: #1e2a2d;
    }
    .bp-field input:focus { border-color: #134e4a; box-shadow: 0 0 0 3px rgba(19,78,74,0.1); }
    .bp-btn-search {
        height: 42px; padding: 0 24px; border: none; border-radius: 10px; background: #134e4a;
        color: #fff; font-size: 0.88rem; font-weight: 700; cursor: pointer; white-space: nowrap;
        display: inline-flex; align-items: center; gap: 8px; transition: background .15s ease;
    }
    .bp-btn-search:hover { background: #0b3d39; }
    .bp-error {
        background: #fef2f2; border: 1.5px solid #fecaca; color: #b91c1c; border-radius: 10px;
        padding: 10px 16px; font-size: 0.83rem; margin: -8px 0 20px; display: flex; align-items: center; gap: 8px;
    }

    /* ─── RINGKASAN ─── */
    .bp-summary {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 28px; width: 100%;
    }
    .bp-summary-card {
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 16px 18px;
        box-sizing: border-box;
    }
    .bp-summary-card.is-highlight { background: #ecfdf5; border-color: #a7f3d0; }
    .bp-summary-card .lbl { font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em; }
    .bp-summary-card .val { font-size: 1.15rem; font-weight: 800; color: #1e2a2d; margin-top: 4px; }
    .bp-summary-card.is-highlight .val { color: #0f4c3a; }

    /* ─── GRID 3 KOLOM ─── */
    .bp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; width: 100%; box-sizing: border-box; }
    .bp-group-card {
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 20px;
        display: flex; flex-direction: column; box-sizing: border-box; width: 100%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .bp-group-head { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
    .bp-group-emoji {
        width: 44px; height: 44px; flex-shrink: 0; border-radius: 12px; background: #f0fdfa;
        border: 1.5px solid #99f6e4; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
    }
    .bp-group-head h3 { margin: 0; font-size: 1.02rem; font-weight: 800; color: #1e2a2d; }
    .bp-group-head p { margin: 2px 0 0; font-size: 0.74rem; color: #94a3b8; }
    .bp-group-alloc {
        display: inline-flex; align-items: center; gap: 6px; align-self: flex-start;
        background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af;
        font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 8px; margin-bottom: 14px;
    }

    .bp-primary {
        border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; margin-bottom: 12px;
        display: flex; flex-direction: column;
    }
    .bp-primary-img {
        height: 130px; background: #f1f5f9; display: flex; align-items: center; justify-content: center;
        overflow: hidden; font-size: 2.4rem;
    }
    .bp-primary-img img { width: 100%; height: 100%; object-fit: cover; }
    .bp-primary-body { padding: 14px; display: flex; flex-direction: column; gap: 8px; }
    .bp-cat-pill {
        align-self: flex-start; background: #f0fdfa; color: #0f766e; font-size: 0.68rem;
        font-weight: 700; padding: 2px 10px; border-radius: 999px;
    }
    .bp-primary-body h4 { margin: 0; font-size: 0.95rem; font-weight: 800; color: #1e2a2d; line-height: 1.35; }
    .bp-price { font-size: 1rem; font-weight: 800; color: #134e4a; }
    .bp-reason { font-size: 0.75rem; color: #64748b; line-height: 1.5; margin: 0; }
    .bp-add {
        margin-top: 4px; width: 100%; background: #134e4a; color: #fff; border: none; border-radius: 9px;
        padding: 9px 12px; font-size: 0.82rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 7px; transition: background .15s ease;
    }
    .bp-add:hover { background: #0b3d39; }

    .bp-alt-title { font-size: 0.72rem; font-weight: 700; color: #64748b; margin: 6px 0 8px; text-transform: uppercase; letter-spacing: 0.03em; }
    .bp-alt-row {
        display: flex; align-items: center; gap: 10px; padding: 8px 0; border-top: 1px dashed #e2e8f0;
    }
    .bp-alt-thumb {
        width: 38px; height: 38px; border-radius: 9px; background: #f1f5f9; overflow: hidden;
        flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .bp-alt-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .bp-alt-info { flex: 1; min-width: 0; }
    .bp-alt-info .nm { font-size: 0.82rem; font-weight: 700; color: #1e2a2d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bp-alt-info .pr { font-size: 0.78rem; font-weight: 700; color: #134e4a; }
    .bp-alt-add {
        flex-shrink: 0; background: #f0fdfa; color: #0f766e; border: 1.5px solid #99f6e4;
        border-radius: 8px; width: 34px; height: 34px; cursor: pointer; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center;
    }
    .bp-alt-add:hover { background: #ccfbf1; }

    .bp-empty-group {
        border: 1.5px dashed #e2e8f0; border-radius: 12px; padding: 26px 16px; text-align: center;
        color: #94a3b8; font-size: 0.82rem;
    }

    /* ─── AKSI BAWAH & CATATAN ─── */
    .bp-actions { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 26px; }
    .bp-note {
        background: #fff7ed; border: 1.5px solid #fed7aa; color: #9a3412; border-radius: 12px;
        padding: 12px 18px; font-size: 0.82rem; margin-top: 20px; line-height: 1.5;
    }
    .bp-info {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px;
        display: flex; gap: 12px; align-items: flex-start; margin-top: 22px; box-sizing: border-box;
    }
    .bp-info p { margin: 0; font-size: 0.82rem; color: #475569; line-height: 1.55; }

    .bp-empty-state {
        text-align: center; padding: 50px 20px; background: #fff; border-radius: 14px;
        border: 1.5px solid #e2e8f0; box-sizing: border-box;
    }

    /* ─── RESPONSIF ─── */
    @media (max-width: 1024px) {
        .bp-grid { grid-template-columns: repeat(2, 1fr); }
        .bp-summary { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .bp-inner { padding: 16px 12px 40px; }
        .bp-grid { grid-template-columns: 1fr; }
        .bp-summary { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .bp-form-box { flex-direction: column; align-items: stretch; padding: 16px; }
        .bp-btn-search { width: 100%; justify-content: center; }
        .bp-hero { flex-direction: column; }
    }
</style>

<div class="bp-page">
    <div class="bp-inner">

        <!-- HEADER -->
        <div class="bp-hero">
            <div class="bp-hero-badge"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <h1>Rencana Menu Sesuai Budget <span style="color:#eab308;">💰</span></h1>
                <p>Masukkan budget Anda. Sistem (SPK) mengelompokkan rekomendasi menjadi
                    <strong>Makanan</strong>, <strong>Minuman</strong>, dan <strong>Snack</strong>,
                    lalu memilih menu terbaik di tiap kelompok agar totalnya tetap sesuai budget.</p>
            </div>
        </div>

        <!-- FORM BUDGET -->
        <form wire:submit.prevent="recommend" class="bp-form-box">
            <div class="bp-field">
                <label>Budget (Rp)</label>
                <input type="number" min="1" step="1000" wire:model="budget" placeholder="Contoh: 100000" inputmode="numeric">
            </div>
            <div class="bp-field" style="max-width: 220px;">
                <label>Jumlah Pax <span style="color:#94a3b8; font-weight:500;">(opsional)</span></label>
                <input type="number" min="1" wire:model="pax" placeholder="Contoh: 20" inputmode="numeric">
            </div>
            <div>
                <button type="submit" class="bp-btn-search">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari Rekomendasi
                </button>
            </div>
        </form>

        @error('budget')
            <div class="bp-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
        @enderror

        @if(!empty($result) && !empty($result['has_result']))
            @php
                $pax   = $result['pax'];
                $hasil = $result;
            @endphp

            <!-- RINGKASAN -->
            <div class="bp-summary">
                <div class="bp-summary-card">
                    <div class="lbl">Budget</div>
                    <div class="val">Rp {{ number_format($hasil['budget'], 0, ',', '.') }}</div>
                </div>
                <div class="bp-summary-card">
                    <div class="lbl">{{ $pax ? 'Budget / Pax' : 'Budget Set' }}</div>
                    <div class="val">Rp {{ number_format($hasil['per_set_budget'], 0, ',', '.') }}</div>
                </div>
                <div class="bp-summary-card is-highlight">
                    <div class="lbl">Total Rekomendasi</div>
                    <div class="val">Rp {{ number_format($hasil['total_per_set'], 0, ',', '.') }}{{ $pax ? ' /pax' : '' }}</div>
                </div>
                <div class="bp-summary-card">
                    <div class="lbl">Sisa Budget</div>
                    <div class="val">Rp {{ number_format($hasil['remaining_all'], 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- GRID 3 KELOMPOK -->
            <div class="bp-grid">
                @foreach($hasil['groups'] as $group)
                    <div class="bp-group-card">
                        <div class="bp-group-head">
                            <div class="bp-group-emoji">{{ $group['emoji'] }}</div>
                            <div>
                                <h3>{{ $group['label'] }}</h3>
                                <p>{{ $group['description'] }}</p>
                            </div>
                        </div>
                        <span class="bp-group-alloc">
                            {{ rtrim(rtrim(number_format($group['allocation_percent'], 0), '0'), ',') }}%
                            &middot; Rp {{ number_format($group['allocation'], 0, ',', '.') }}
                        </span>

                        @if($group['primary'])
                            @php $primary = $group['primary']; @endphp
                            <div class="bp-primary">
                                <div class="bp-primary-img">
                                    @if(!empty($primary->image))
                                        <img src="{{ asset('storage/' . $primary->image) }}" alt="{{ $primary->name }}">
                                    @else
                                        {{ $group['emoji'] }}
                                    @endif
                                </div>
                                <div class="bp-primary-body">
                                    <span class="bp-cat-pill">{{ $primary->category->name ?? 'Menu' }}</span>
                                    <h4>{{ $primary->name }}</h4>
                                    <div class="bp-price">Rp {{ number_format($primary->price, 0, ',', '.') }}</div>
                                    @if($group['reason'])
                                        <p class="bp-reason">{{ $group['reason'] }}</p>
                                    @endif
                                    <button type="button" wire:click="addMenuToCart({{ $primary->id }})" class="bp-add" wire:loading.attr="disabled">
                                        <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>

                            @if($group['alternatives']->count() > 1)
                                <div class="bp-alt-title">Alternatif {{ $group['label'] }}</div>
                                @foreach($group['alternatives']->skip(1)->take(3) as $alt)
                                    <div class="bp-alt-row">
                                        <div class="bp-alt-thumb">
                                            @if(!empty($alt->image))
                                                <img src="{{ asset('storage/' . $alt->image) }}" alt="{{ $alt->name }}">
                                            @else
                                                {{ $group['emoji'] }}
                                            @endif
                                        </div>
                                        <div class="bp-alt-info">
                                            <div class="nm">{{ $alt->name }}</div>
                                            <div class="pr">Rp {{ number_format($alt->price, 0, ',', '.') }}</div>
                                        </div>
                                        <button type="button" wire:click="addMenuToCart({{ $alt->id }})" class="bp-alt-add" title="Tambah ke keranjang">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        @else
                            <div class="bp-empty-group">
                                Belum ada menu untuk kelompok ini.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if(!empty($hasil['notes']))
                <div class="bp-note">
                    <strong><i class="fa-solid fa-circle-info"></i> Catatan:</strong>
                    <ul style="margin: 6px 0 0 18px; padding: 0;">
                        @foreach($hasil['notes'] as $note)
                            <li>{{ $note }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- AKSI -->
            <div class="bp-actions">
                <button type="button" wire:click="addSetToCart" class="bp-btn-search" wire:loading.attr="disabled">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Tambah Semua Set ({{ $pax ?: 1 }} porsi)
                </button>
                <a href="{{ route('catalog') }}" style="display:inline-flex; align-items:center; gap:8px; background:#fff; color:#134e4a; border:1.5px solid #99f6e4; text-decoration:none; padding:10px 18px; border-radius:10px; font-size:0.85rem; font-weight:700;">
                    <i class="fa-solid fa-utensils"></i> Atur Sendiri di Katalog
                </a>
            </div>

            <div class="bp-info">
                <div style="color:#047857; font-size:1.05rem; flex-shrink:0;">ℹ️</div>
                <p>
                    Harga yang ditampilkan adalah harga per porsi. Sistem membagi budget ke tiap kelompok
                    (Makanan {{ (int) ($hasil['allocation']['makanan'] * 100) }}%,
                    Minuman {{ (int) ($hasil['allocation']['minuman'] * 100) }}%,
                    Snack {{ (int) ($hasil['allocation']['snack'] * 100) }}%),
                    lalu memilih menu termahal yang masih masuk alokasi tiap kelompok
                    supaya total tidak melebihi budget.
                    @if($pax) Perhitungan ini untuk {{ number_format($pax, 0, ',', '.') }} pax
                        (total ± Rp {{ number_format($hasil['total_all'], 0, ',', '.') }}).@endif
                </p>
            </div>

        @elseif(!empty($result) && empty($result['has_result']))
            <div class="bp-empty-state">
                <div style="font-size:2rem; margin-bottom:8px;">📭</div>
                <h3 style="color:#1e2a2d; font-size:1.1rem; font-weight:700; margin:0;">Belum Ada Menu yang Cocok</h3>
                <p style="color:#64748b; font-size:0.85rem; margin-top:6px;">
                    Coba naikkan budget, atau pastikan data menu sudah tersedia di setiap kategori.
                </p>
            </div>
        @else
            <div class="bp-empty-state">
                <div style="font-size:2rem; margin-bottom:8px;">🧮</div>
                <h3 style="color:#1e2a2d; font-size:1.1rem; font-weight:700; margin:0;">Mulai Susun Rencana Menu</h3>
                <p style="color:#64748b; font-size:0.85rem; margin-top:6px;">
                    Masukkan budget di atas lalu tekan <strong>Cari Rekomendasi</strong>.
                    Sistem akan menampilkan kelompok Makanan, Minuman, dan Snack sesuai budget Anda.
                </p>
            </div>
        @endif

    </div>
</div>
</div>
