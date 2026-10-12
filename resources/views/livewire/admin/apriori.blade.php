@section('title', 'Rekomendasi Apriori SPK')
<div>
    <!-- CSS Internal Halaman Apriori Admin -->
    <style>
        .apr-wrap { display: flex; flex-direction: column; gap: 20px; }

        /* HERO / JUDUL HALAMAN */
        .apr-hero {
            background: linear-gradient(135deg, #2b5352 0%, #1e3d3c 100%);
            color: #fff;
            border-radius: var(--radius-md, 12px);
            padding: 22px 26px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 6px 18px rgba(15, 76, 58, 0.18);
        }
        .apr-hero-icon {
            width: 54px; height: 54px; flex-shrink: 0;
            border-radius: 14px; background: rgba(255,255,255,0.14);
            display: flex; align-items: center; justify-content: center; font-size: 1.45rem;
        }
        .apr-hero h1 { margin: 0 0 4px 0; font-size: 1.3rem; font-weight: 800; letter-spacing: 0.2px; }
        .apr-hero p { margin: 0; font-size: 0.85rem; color: #d7e5e2; line-height: 1.5; }

        /* PANEL UMUM */
        .apriori-panel {
            background: #ffffff;
            border-radius: var(--radius-md, 12px);
            border: 1px solid var(--border-color, #e2e8f0);
            padding: 24px;
            box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,0.1));
        }
        .apriori-subtitle {
            margin: 0 0 16px 0; font-size: 1.05rem; font-weight: 700;
            color: var(--primary-dark, #0b3d2e);
            display: flex; align-items: center; gap: 9px;
        }
        .apriori-subtitle .apr-count {
            margin-left: auto; font-size: 0.72rem; font-weight: 700;
            background: #f0f7f7; color: var(--primary, #2b5352);
            border: 1px solid #d7e5e2; padding: 3px 10px; border-radius: 999px;
        }

        /* GRID ITEMSET + RULES */
        .apriori-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start; }
        @media (max-width: 992px) { .apriori-grid { grid-template-columns: 1fr; } }

        /* KARTU STATISTIK */
        .apr-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 16px; }
        .apr-stat {
            background: #fff; border: 1px solid var(--border-color, #e2e8f0);
            border-radius: var(--radius-md, 12px); padding: 16px 18px;
            box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,0.1));
            display: flex; align-items: center; gap: 14px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .apr-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.07); }
        .apr-stat-icon {
            width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
        }
        .apr-stat-label { display: block; font-size: 0.7rem; color: var(--text-muted, #64748b); text-transform: uppercase; font-weight: 700; letter-spacing: 0.03em; }
        .apr-stat-value { display: block; font-size: 1.3rem; font-weight: 800; color: var(--primary-dark, #0b3d2e); margin-top: 2px; }

        /* ITEMSET */
        .apr-itemset-group { margin-bottom: 16px; }
        .apr-itemset-head {
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.78rem; font-weight: 800; color: var(--primary, #2b5352);
            text-transform: uppercase; letter-spacing: 0.04em;
            padding: 8px 0; border-bottom: 1px solid var(--border-color, #e2e8f0);
        }
        .apr-itemset-head .apr-itemset-count { font-size: 0.7rem; font-weight: 600; color: var(--text-muted, #64748b); text-transform: none; }
        .itemset-row {
            background: #fbfdfd; border: 1px solid var(--border-color, #e2e8f0);
            padding: 10px 14px; border-radius: var(--radius-sm, 8px);
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            margin-top: 8px; font-size: 0.88rem; color: var(--text-main, #334155);
        }
        .itemset-name { line-height: 1.4; }

        /* ATURAN ASOSIASI */
        .rule-row {
            background: #fff; border: 1px solid var(--border-color, #e2e8f0);
            padding: 14px; border-radius: var(--radius-sm, 8px);
            display: flex; flex-direction: column; gap: 10px; margin-bottom: 12px;
        }
        .rule-if { font-size: 0.88rem; color: var(--text-main, #334155); line-height: 1.5; }
        .rule-arrow { color: var(--primary, #2b5352); font-weight: bold; padding: 0 4px; }
        .rule-badges { display: flex; flex-wrap: wrap; gap: 6px; font-size: 0.75rem; align-items: center; }
        .rule-badge { padding: 3px 8px; border-radius: 20px; font-weight: 600; white-space: nowrap; }

        /* PEMISAH BAGIAN BUKTI */
        .apr-divider { display: flex; align-items: center; gap: 14px; margin: 8px 0 4px 0; }
        .apr-divider::before, .apr-divider::after { content: ""; height: 1px; background: var(--border-color, #e2e8f0); flex: 1; }
        .apr-divider span {
            font-size: 0.78rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase;
            color: var(--primary, #2b5352); white-space: nowrap;
            display: inline-flex; align-items: center; gap: 8px;
            background: #f0f7f7; border: 1px solid #d7e5e2; padding: 6px 14px; border-radius: 999px;
        }

        /* LOADING STATE */
        .apr-empty {
            text-align: center; padding: 56px 24px; background: #fff;
            border-radius: var(--radius-md, 12px); border: 1px dashed var(--border-color, #e2e8f0);
        }

        @media (max-width: 640px) {
            .apriori-settings-grid { grid-template-columns: 1fr !important; }
            .apr-hero { flex-direction: column; text-align: center; }
        }
    </style>

    <div class="apr-wrap">

        <!-- HERO / JUDUL -->
        <div class="apr-hero">
            <div class="apr-hero-icon"><i class="fa-solid fa-brain"></i></div>
            <div>
                <h1>Analisis Apriori</h1>
                <p>Ruang kendali algoritma Apriori: atur parameter, lihat pola &amp; aturan asosiasi dari seluruh transaksi, serta bukti personalisasi per pelanggan &mdash; semuanya dalam satu halaman.</p>
            </div>
        </div>

        <!-- SETTINGS PANEL -->
        <div class="apriori-panel">
            <h3 class="apriori-subtitle"><i class="fa-solid fa-sliders" style="color:var(--primary, #2b5352);"></i> Parameter Algoritma Apriori</h3>

            @if (session()->has('message'))
                <div style="background:#DEF7EC; color:#03543F; border:1px solid #84E1BC; padding:10px 14px; border-radius:var(--radius-sm, 8px); margin-bottom:16px; font-size:0.9rem; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('message') }}
                </div>
            @endif

            <div class="apriori-settings-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Minimum Support (0.01 - 1.0)</label>
                    <input type="number" wire:model="minSupport" class="form-control" min="0.01" max="1" step="0.01">
                    <small style="color:var(--text-muted, #64748b); font-size:0.78rem; margin-top:4px; display:block;">Contoh: 0.2 = 20% dari total transaksi</small>
                    @error('minSupport') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Minimum Confidence (0.01 - 1.0)</label>
                    <input type="number" wire:model="minConfidence" class="form-control" min="0.01" max="1" step="0.01">
                    <small style="color:var(--text-muted, #64748b); font-size:0.78rem; margin-top:4px; display:block;">Contoh: 0.5 = 50% keyakinan aturan asosiasi</small>
                    @error('minConfidence') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button wire:click="saveSettings" wire:loading.attr="disabled" class="action-btn edit" style="font-size:0.9rem; padding:10px 20px;">
                    <span wire:loading.remove wire:target="saveSettings"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan</span>
                    <span wire:loading wire:target="saveSettings"><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...</span>
                </button>
                <button wire:click="runApriori" wire:loading.attr="disabled" class="btn-primary" style="padding:10px 24px;">
                    <span wire:loading.remove wire:target="runApriori"><i class="fa-solid fa-play"></i> Jalankan Analisis Apriori</span>
                    <span wire:loading wire:target="runApriori"><i class="fa-solid fa-spinner fa-spin"></i> Memproses Analisis...</span>
                </button>
            </div>
        </div>

        @if($results)
        <!-- STATISTIK -->
        <div class="apr-stats">
            <div class="apr-stat">
                <span class="apr-stat-icon" style="background:#e0f2fe; color:#0369a1;"><i class="fa-solid fa-receipt"></i></span>
                <div>
                    <span class="apr-stat-label">Total Transaksi</span>
                    <span class="apr-stat-value">{{ $results['total_transactions'] }}</span>
                </div>
            </div>
            <div class="apr-stat">
                <span class="apr-stat-icon" style="background:#ecfdf5; color:#047857;"><i class="fa-solid fa-percent"></i></span>
                <div>
                    <span class="apr-stat-label">Min. Support</span>
                    <span class="apr-stat-value">{{ round($results['min_support']*100) }}%</span>
                </div>
            </div>
            <div class="apr-stat">
                <span class="apr-stat-icon" style="background:#fefce8; color:#a16207;"><i class="fa-solid fa-bullseye"></i></span>
                <div>
                    <span class="apr-stat-label">Min. Confidence</span>
                    <span class="apr-stat-value">{{ round($results['min_confidence']*100) }}%</span>
                </div>
            </div>
            <div class="apr-stat">
                <span class="apr-stat-icon" style="background:#f5f3ff; color:#6d28d9;"><i class="fa-solid fa-layer-group"></i></span>
                <div>
                    <span class="apr-stat-label">Frequent Itemset</span>
                    <span class="apr-stat-value">{{ count(collect($results['frequent_itemsets'])->flatten(1)->toArray()) }}</span>
                </div>
            </div>
            <div class="apr-stat">
                <span class="apr-stat-icon" style="background:#fdf2f8; color:#be185d;"><i class="fa-solid fa-arrow-right-arrow-left"></i></span>
                <div>
                    <span class="apr-stat-label">Aturan Asosiasi</span>
                    <span class="apr-stat-value">{{ count($results['rules']) }}</span>
                </div>
            </div>
        </div>

        <div class="apriori-grid">
            <!-- FREQUENT ITEMSETS -->
            <div class="apriori-panel">
                <h3 class="apriori-subtitle"><i class="fa-solid fa-layer-group" style="color:var(--primary, #2b5352);"></i> Frequent Itemsets</h3>
                @if(empty($results['frequent_itemsets']))
                    <p style="color:var(--text-muted, #64748b); font-size:0.9rem;">Tidak ada frequent itemset ditemukan dengan parameter ini. Coba turunkan nilai minimum support.</p>
                @else
                    @foreach($results['frequent_itemsets'] as $k => $itemsets)
                        <div class="apr-itemset-group">
                            <div class="apr-itemset-head">
                                <span>{{ $k }}-itemset</span>
                                <span class="apr-itemset-count">{{ count($itemsets) }} itemset</span>
                            </div>
                            @foreach($itemsets as $itemset)
                            @php
                                $menuNames = \App\Models\Menu::whereIn('id', $itemset['items'])->pluck('name')->toArray();
                            @endphp
                            <div class="itemset-row">
                                <span class="itemset-name">{ {{ implode(', ', $menuNames) }} }</span>
                                <span class="status-badge" style="background:#E6FFFA; color:#234E52; font-weight:700;">{{ round($itemset['support']*100, 1) }}%</span>
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- ASSOCIATION RULES -->
            <div class="apriori-panel">
                <h3 class="apriori-subtitle"><i class="fa-solid fa-arrow-right-arrow-left" style="color:var(--primary, #2b5352);"></i> Aturan Asosiasi (Association Rules)</h3>
                @if(empty($results['rules']))
                    <p style="color:var(--text-muted, #64748b); font-size:0.9rem;">Tidak ada aturan asosiasi ditemukan. Coba turunkan nilai minimum confidence.</p>
                @else
                    @foreach($results['rules'] as $rule)
                    @php
                        $antNames = \App\Models\Menu::whereIn('id', $rule['antecedent'])->pluck('name')->toArray();
                        $consNames = \App\Models\Menu::whereIn('id', $rule['consequent'])->pluck('name')->toArray();
                        $liftVal = isset($rule['lift']) ? (float)$rule['lift'] : 0;
                        $formattedLift = number_format($liftVal, 2, '.', '');

                        if ($liftVal > 1.5) {
                            $liftBadgeStyle = 'background:#DEF7EC; color:#03543F; border:1px solid #84E1BC;';
                            $liftLabel = 'Sangat Kuat';
                        } elseif ($liftVal > 1.0) {
                            $liftBadgeStyle = 'background:#EBF8FF; color:#2B6CB0; border:1px solid #BEE3F8;';
                            $liftLabel = 'Kuat / Positif';
                        } elseif ($liftVal == 1.0) {
                            $liftBadgeStyle = 'background:#F3F4F6; color:#4B5563; border:1px solid #D1D5DB;';
                            $liftLabel = 'Netral';
                        } else {
                            $liftBadgeStyle = 'background:#FDE8E8; color:#9B1C1C; border:1px solid #F8B4B4;';
                            $liftLabel = 'Lemah / Negatif';
                        }
                    @endphp
                    <div class="rule-row">
                        <div class="rule-if">
                            <strong>Jika:</strong> { {{ implode(', ', $antNames) }} }
                            <span class="rule-arrow"> ⟹ </span>
                            <strong>Maka:</strong> { {{ implode(', ', $consNames) }} }
                        </div>
                        <div class="rule-badges">
                            <span class="rule-badge" style="background:#f0f7f7; color:var(--primary-dark, #0b3d2e);">
                                Support: <strong>{{ round($rule['support']*100, 1) }}%</strong>
                            </span>
                            <span class="rule-badge" style="background:#FEFCBF; color:#744210; border:1px solid #F6E05E;">
                                Confidence: <strong>{{ round($rule['confidence']*100, 1) }}%</strong>
                            </span>
                            <span class="rule-badge" style="{{ $liftBadgeStyle }}">
                                Lift: <strong>{{ $formattedLift }}</strong> ({{ $liftLabel }})
                            </span>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @else
            <div class="apr-empty">
                <i class="fa-solid fa-brain" style="font-size:3.5rem; color:var(--primary, #2b5352); margin-bottom:16px; opacity:0.6;"></i>
                <h3 style="color:var(--primary-dark, #0b3d2e); margin:0 0 8px 0;">Memuat Analisis Apriori...</h3>
                <p style="color:var(--text-muted, #64748b); max-width:500px; margin:0 auto; font-size:0.9rem;">Sistem sedang menghitung pola transaksi data katering.</p>
            </div>
        @endif

        <!-- PEMISAH KE BAGIAN BUKTI -->
        <div class="apr-divider"><span><i class="fa-solid fa-flask"></i> Bukti Personalisasi per Pelanggan</span></div>

        <!-- BUKTI PERSONALISASI (satu halaman Apriori) -->
        @include('livewire.admin.apriori-proof')
    </div>
</div>
