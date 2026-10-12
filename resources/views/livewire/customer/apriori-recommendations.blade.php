<div>
<style>
    /* ─── DASAR HALAMAN ─── */
    .apriori-page-container {
        width: 100%; max-width: 100%; box-sizing: border-box;
        background-color: #f8fafc; min-height: 100vh; overflow-x: hidden !important;
    }
    .apriori-inner { padding: 30px 24px 60px 24px; max-width: 1280px; margin: 0 auto; box-sizing: border-box; width: 100%; }

    /* ─── HEADER ─── */
    .apr-hero-cust { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px; }
    .apr-hero-cust .apr-hero-badge {
        width: 48px; height: 48px; flex-shrink: 0; border-radius: 14px;
        background: #f0fdfa; border: 1.5px solid #99f6e4; color: #134e4a;
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
    }
    .apr-hero-cust h1 { font-size: 1.45rem; font-weight: 800; color: #1e2a2d; margin: 0 0 4px 0; }
    .apr-hero-cust p { color: #64748b; font-size: 0.85rem; margin: 0; line-height: 1.5; }

    /* ─── BANNER SUMBER DATA ─── */
    .apr-source {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
        border: 1.5px solid #a7f3d0; border-radius: 14px; padding: 14px 20px;
        margin-bottom: 24px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        box-sizing: border-box; width: 100%;
    }
    .apr-source .apr-chip-title {
        background: #134e4a; color: #fff; font-size: 0.72rem; font-weight: 800;
        padding: 5px 14px; border-radius: 999px; white-space: nowrap;
    }
    .apr-source p { margin: 0; font-size: 0.85rem; color: #0f4c3a; flex: 1; min-width: 220px; line-height: 1.5; }
    .apr-source .apr-param {
        font-size: 0.72rem; font-weight: 700; background: #fff; padding: 4px 11px;
        border-radius: 8px; white-space: nowrap;
    }

    /* ─── KOTAK BUDGET ─── */
    .apriori-form-box {
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 14px;
        padding: 22px 26px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        display: flex; align-items: center; justify-content: space-between; gap: 24px;
        flex-wrap: wrap; box-sizing: border-box; width: 100%;
    }

    /* ─── GRID KARTU PAKET ─── */
    .apriori-grid-3 {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
        margin-bottom: 34px; width: 100%; box-sizing: border-box;
    }
    .apr-pkg-card {
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 20px;
        display: flex; flex-direction: column; justify-content: space-between;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02); position: relative;
        box-sizing: border-box; width: 100%; transition: transform .15s ease, box-shadow .15s ease;
    }
    .apr-pkg-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
    .apr-pkg-card.is-top { border: 2px solid #047857; box-shadow: 0 8px 22px rgba(4,120,87,0.12); }
    .apr-ribbon {
        position: absolute; top: -11px; right: 16px; background: #047857; color: #fff;
        font-size: 0.66rem; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase;
        padding: 4px 12px; border-radius: 999px; box-shadow: 0 2px 6px rgba(4,120,87,0.3);
    }
    .apr-pkg-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
    .apr-pkg-tags { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .apr-tag-paket { font-size: 0.72rem; font-weight: 800; background: #f0fdf4; color: #166534; padding: 3px 10px; border-radius: 6px; white-space: nowrap; }
    .apr-tag-support { font-size: 0.68rem; font-weight: 700; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 2px 8px; border-radius: 6px; white-space: nowrap; }
    .apr-pkg-price { font-size: 0.95rem; font-weight: 800; color: #1e2a2d; white-space: nowrap; }

    /* ─── SLOT MENU DALAM PAKET ─── */
    .apr-slot { display: flex; align-items: center; gap: 12px; box-sizing: border-box; margin-bottom: 13px; }
    .apr-slot-thumb {
        width: 46px; height: 46px; border-radius: 10px; background: #f1f5f9; overflow: hidden;
        flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;
    }
    .apr-slot-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .apr-slot-body { min-width: 0; flex: 1; }
    .apr-slot-label { font-size: 0.63rem; font-weight: 700; background: #f0fdf4; color: #166534; padding: 1px 6px; border-radius: 4px; }
    .apr-slot-name { font-size: 0.82rem; font-weight: 800; color: #1e2a2d; margin: 3px 0 1px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .apr-slot-hint { font-size: 0.7rem; color: #94a3b8; margin: 0; }
    .apr-cta {
        width: 100%; text-align: center; background: #134e4a; color: #fff; padding: 11px;
        border-radius: 10px; font-size: 0.86rem; font-weight: 700; border: none;
        cursor: pointer; box-sizing: border-box; transition: background .15s ease;
    }
    .apr-cta:hover { background: #0b3d39; }

    /* ─── SECTION HEADER ─── */
    .apr-section-head { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
    .apr-section-head .apr-section-icon { color: #134e4a; font-size: 1.05rem; }
    .apr-section-head h3 { font-size: 1rem; font-weight: 800; color: #1e2a2d; margin: 0; }
    .apr-section-head p { font-size: 0.78rem; color: #64748b; margin: 0; }

    /* ─── ATURAN ASOSIASI ─── */
    .apr-rules-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* ─── RESPONSIF ─── */
    @media (max-width: 1024px) {
        .apriori-grid-3 { grid-template-columns: repeat(2, 1fr); }
        .apr-rules-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .apriori-inner { padding: 16px 12px 40px 12px !important; }
        .apriori-grid-3 { grid-template-columns: 1fr !important; gap: 16px !important; }
        .apriori-form-box { flex-direction: column !important; align-items: stretch !important; padding: 16px !important; }
        .apriori-form-box input { width: 100% !important; box-sizing: border-box !important; }
        .apriori-form-box button { width: 100% !important; justify-content: center !important; }
        .apr-hero-cust { flex-direction: column; }
    }
</style>

<div class="apriori-page-container">
    <div class="apriori-inner">

        <!-- HEADER -->
        <div class="apr-hero-cust">
            <div class="apr-hero-badge"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
            <div>
                <h1>Rekomendasi untuk Anda <span style="color:#eab308;">⭐</span></h1>
                <p>Masukkan total budget dan jumlah pax acara Anda. Sistem menghitung <strong>budget per paket</strong> lalu menyusun paket menu yang harganya paling mendekati &mdash; diperkuat pola transaksi Apriori.</p>
            </div>
        </div>

        <!-- BANNER: SUMBER REKOMENDASI -->
        <div class="apr-source">
            <span class="apr-chip-title">Sumber Data</span>
            <p>
                <strong>{{ $personalized ? 'Dianalisis dari riwayat transaksi Anda' : 'Dianalisis dari seluruh transaksi pelanggan' }}</strong>
                ({{ $totalTransactions }} transaksi) &mdash; setiap pelanggan mendapat hasil berbeda sesuai riwayat belanjanya.
            </p>
            <span class="apr-param" style="color:#047857; border:1px solid #a7f3d0;">Min Support {{ rtrim(rtrim(number_format($minSupport * 100, 2), '0'), '.') }}%</span>
            <span class="apr-param" style="color:#1e40af; border:1px solid #bfdbfe;">Min Confidence {{ rtrim(rtrim(number_format($minConfidence * 100, 2), '0'), '.') }}%</span>
        </div>

        <!-- KOTAK FORM INPUT KEBUTUHAN (Total Budget + Jumlah Pax) -->
        <div class="apriori-form-box">
            <div style="flex: 1; min-width: 220px; box-sizing: border-box;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin: 0 0 4px 0;">Kebutuhan Acara Anda</h4>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Sistem memilih paket yang harganya paling mendekati budget per orang.</p>
            </div>
            <div style="display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap; width: 100%; max-width: 100%; box-sizing: border-box;">
                <div style="flex: 1; min-width: 165px;">
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 6px;">Total Budget (Rp)</label>
                    <input type="number" wire:model.live.debounce.300ms="totalBudget" placeholder="1500000" style="width: 100%; padding: 9px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; background: #fff; box-sizing: border-box;">
                </div>
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 6px;">Jumlah Pax</label>
                    <input type="number" wire:model.live.debounce.300ms="jumlahPax" placeholder="30" style="width: 100%; padding: 9px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; background: #fff; box-sizing: border-box;">
                </div>
                <div style="padding-bottom: 1px;">
                    <button type="button" wire:click="search" class="apr-cta" style="width:auto; display:inline-flex; align-items:center; gap:8px; height:41px; padding:0 20px;">
                        🔍 Cari Rekomendasi
                    </button>
                </div>
            </div>
        </div>

        @if($targetPerPaket)
        <div style="background:#ecfdf5; border:1.5px solid #a7f3d0; border-radius:12px; padding:14px 20px; margin:-14px 0 26px 0; display:flex; align-items:center; gap:12px; flex-wrap:wrap; box-sizing:border-box;">
            <span style="font-size:1.2rem;">💰</span>
            <p style="margin:0; font-size:0.88rem; color:#0f4c3a;">
                Budget per paket Anda: <strong style="font-size:1.05rem;">Rp {{ number_format($targetPerPaket, 0, ',', '.') }}</strong>
                <span style="color:#64748b;">(Total Rp {{ number_format($totalBudget, 0, ',', '.') }} ÷ {{ number_format($jumlahPax, 0, ',', '.') }} pax)</span>
            </p>
        </div>
        @endif

        @if($budgetNote)
        <div style="background:#fff7ed; border:1.5px solid #fed7aa; border-radius:12px; padding:12px 18px; margin:-14px 0 26px 0; display:flex; align-items:center; gap:10px; box-sizing:border-box;">
            <span style="font-size:1rem;">⚠️</span>
            <p style="margin:0; font-size:0.83rem; color:#9a3412; line-height:1.5;">{{ $budgetNote }}</p>
        </div>
        @endif

        <!-- SUB-HEADER KONTEN -->
        <div class="apr-section-head">
            <div class="apr-section-icon">📦</div>
            <div>
                <h3>Rekomendasi Paket Menu</h3>
                <p>
                    @if($targetPerPaket)
                        Paket paling dekat dengan budget per paket <strong>Rp {{ number_format($targetPerPaket, 0, ',', '.') }}</strong>
                    @else
                        Masukkan Total Budget &amp; Jumlah Pax untuk rekomendasi yang paling sesuai
                    @endif
                    &middot; {{ $personalized ? 'Dianalisis dari riwayat transaksi Anda' : 'Dianalisis dari seluruh transaksi pelanggan' }}
                    ({{ $totalTransactions }} transaksi)
                </p>
            </div>
        </div>

        @if(!$hasData)
            <div style="text-align:center; padding:50px 20px; background:#fff; border-radius:14px; border:1.5px solid #e2e8f0; box-sizing: border-box;">
                <div style="font-size:2rem; margin-bottom:8px;">📭</div>
                <h3 style="color:#1e2a2d; font-size:1.1rem; font-weight:700; margin:0;">Belum Cukup Data Analisis</h3>
                <p style="color:#64748b; font-size:0.85rem; margin-top:6px;">Sistem memerlukan data transaksi untuk memproses rekomendasi paket cerdas.</p>
            </div>
        @else

            @php
                $slotMeta = [
                    'nasi'   => ['label' => 'Nasi Box',    'emoji' => '🍚', 'hint' => 'Nasi putih, lauk pilihan, sambal'],
                    'basah'  => ['label' => 'Snack Basah',  'emoji' => '🥮', 'hint' => 'Kue tradisional lezat'],
                    'kering' => ['label' => 'Snack Kering', 'emoji' => '🍪', 'hint' => 'Camilan gurih renyah'],
                    'minum'  => ['label' => 'Minuman',      'emoji' => '🥤', 'hint' => 'Segar dan pas dinikmati'],
                ];
                // Paket pertama = paling mendekati budget per paket (urutan ditentukan server)
                $topIndex = count($recommendations) > 0 ? 0 : null;
            @endphp

            <!-- KARTU PAKET (Responsif 3/2/1 kolom) -->
            <div class="apriori-grid-3">
                @forelse($recommendations as $index => $rec)
                <div class="apr-pkg-card {{ $index === $topIndex ? 'is-top' : '' }}">
                    @if($index === $topIndex)
                        <span class="apr-ribbon">Paling Sesuai</span>
                    @endif

                    <div>
                        <!-- HEADER PAKET -->
                        <div class="apr-pkg-head">
                            <span class="apr-pkg-tags">
                                <span class="apr-tag-paket">Paket {{ $index + 1 }}</span>
                                @if($rec['support'] !== null)
                                    <span class="apr-tag-support" title="Support: seberapa sering kombinasi menu ini muncul di riwayat transaksi">Support {{ rtrim(rtrim(number_format((float) $rec['support'], 1, ',', '.'), '0'), ',') }}%</span>
                                @else
                                    <span class="apr-tag-support" style="background:#f8fafc; color:#64748b; border-color:#e2e8f0;">Komposisi baru</span>
                                @endif
                            </span>
                            <span class="apr-pkg-price">Rp {{ number_format($rec['price'], 0, ',', '.') }} / pax</span>
                        </div>
                        <p style="font-size: 0.78rem; color: #64748b; margin: 0 0 14px 0; line-height: 1.4;">
                            {{ $rec['description'] }}
                        </p>

                        <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 14px 0;">

                        <!-- ISI PAKET -->
                        <div style="margin-bottom: 18px;">
                            @foreach($slotMeta as $key => $meta)
                                @php $menu = $rec['structured'][$key] ?? null; @endphp
                                @if($menu)
                                <div class="apr-slot">
                                    <div class="apr-slot-thumb">
                                        @if(!empty($menu->image))
                                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                                        @else
                                            {{ $meta['emoji'] }}
                                        @endif
                                    </div>
                                    <div class="apr-slot-body">
                                        <span class="apr-slot-label">{{ $meta['label'] }}</span>
                                        <h5 class="apr-slot-name">{{ $menu->name }}</h5>
                                        <p class="apr-slot-hint">{{ $meta['hint'] }}</p>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div style="width: 100%; box-sizing: border-box;">
                        <button type="button" class="apr-cta" wire:click="selectPackage([
                            {{ $rec['structured']['nasi']?->id ?? 0 }},
                            {{ $rec['structured']['basah']?->id ?? 0 }},
                            {{ $rec['structured']['kering']?->id ?? 0 }},
                            {{ $rec['structured']['minum']?->id ?? 0 }}
                        ], {{ (int) ($jumlahPax ?: 20) }})">
                            Pilih Paket Ini ({{ (int) ($jumlahPax ?: 20) }} Pax)
                        </button>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.9rem; box-sizing: border-box;">
                    Belum ada paket yang bisa disusun. Pastikan menu untuk setiap kategori (Nasi, Snack Basah, Snack Kering, Minuman) sudah tersedia.
                </div>
                @endforelse
            </div>

            <!-- PILIHAN KUSTOM -->
            <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; justify-content: space-between; flex-wrap: wrap; box-sizing: border-box; width: 100%; margin-bottom: 18px;">
                <p style="font-size: 0.85rem; color: #92400e; margin: 0;">
                    <strong>Belum cocok dengan paket di atas?</strong> Anda tetap bebas menyusun menu sendiri.
                </p>
                <a href="{{ route('catalog') }}" style="display:inline-flex; align-items:center; gap:8px; background:#134e4a; color:#fff; text-decoration:none; padding:10px 18px; border-radius:9px; font-size:0.85rem; font-weight:700; white-space:nowrap;">
                    <i class="fa-solid fa-utensils"></i> Pilih Menu Sendiri (Kustom)
                </a>
            </div>

            <!-- INFO BAWAH -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.01); box-sizing: border-box; width: 100%;">
                <div style="color: #047857; font-size: 1.1rem; flex-shrink: 0;">ℹ️</div>
                <p style="font-size: 0.82rem; color: #475569; margin: 0;">
                    Harga tiap paket dihitung dari daftar menu. Sistem memilih paket yang paling mendekati
                    {{ $targetPerPaket ? 'budget per paket Anda (Rp ' . number_format($targetPerPaket, 0, ',', '.') . ')' : 'kebutuhan Anda' }},
                    dan diperkuat hasil analisis Apriori (Support, Confidence, Lift) dari {{ $totalTransactions }} transaksi
                    {{ $personalized ? 'Anda sendiri' : 'seluruh pelanggan' }}.
                </p>
            </div>

            <!-- ATURAN ASOSIASI -->
            @if(count($rules) > 0)
            <div style="margin-top: 28px;">
                <div class="apr-section-head">
                    <div class="apr-section-icon">🧮</div>
                    <div>
                        <h3>Aturan Asosiasi (Hasil Apriori)</h3>
                        <p>Metrik Support, Confidence, dan Lift dari {{ $totalTransactions }} transaksi {{ $personalized ? 'Anda' : 'seluruh pelanggan' }}</p>
                    </div>
                </div>

                <div class="apr-rules-grid">
                    @forelse(array_slice($rules, 0, 10) as $rule)
                        @php
                            $liftValue = (float) ($rule['lift'] ?? 0);
                            if ($liftValue > 1.5) {
                                $liftLabel = 'Sangat Kuat'; $liftStyle = 'background:#DEF7EC; color:#03543F; border:1px solid #84E1BC;';
                            } elseif ($liftValue > 1.0) {
                                $liftLabel = 'Kuat'; $liftStyle = 'background:#EBF8FF; color:#2B6CB0; border:1px solid #BEE3F8;';
                            } elseif ($liftValue == 1.0) {
                                $liftLabel = 'Netral'; $liftStyle = 'background:#F3F4F6; color:#4B5563; border:1px solid #D1D5DB;';
                            } else {
                                $liftLabel = 'Lemah'; $liftStyle = 'background:#FDE8E8; color:#9B1C1C; border:1px solid #FCA5A5;';
                            }
                        @endphp
                        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; box-sizing: border-box;">
                            <p style="font-size: 0.87rem; color: #1e2a2d; margin: 0 0 10px 0; line-height: 1.5;">
                                <strong style="color:#0f4c3a;">Jika</strong> membeli <strong>{{ implode(', ', $rule['antecedent']) }}</strong>,
                                <strong style="color:#0f4c3a;">maka</strong> cenderung menambah <strong>{{ implode(', ', $rule['consequent']) }}</strong>
                            </p>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                                <span style="font-size: 0.72rem; font-weight: 700; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 3px 9px; border-radius: 6px;">Support: {{ $rule['support'] }}%</span>
                                <span style="font-size: 0.72rem; font-weight: 700; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 3px 9px; border-radius: 6px;">Confidence: {{ $rule['confidence'] }}%</span>
                                <span style="font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 6px; {{ $liftStyle }}">Lift: {{ number_format($liftValue, 2) }} ({{ $liftLabel }})</span>
                                @if(!empty($rule['total_price']))
                                <span style="font-size: 0.72rem; font-weight: 700; background: #fffbeb; color: #92400e; border: 1px solid #fde68a; padding: 3px 9px; border-radius: 6px;">Total: Rp {{ number_format($rule['total_price'], 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; background: #ffffff; border: 1px dashed #e2e8f0; border-radius: 12px; padding: 18px; text-align: center; font-size: 0.85rem; color: #64748b;">
                            Belum ada aturan asosiasi yang lolos ambang minimum support/confidence.
                        </div>
                    @endforelse
                </div>
            </div>
            @endif

        @endif

    </div>
</div>
</div>
