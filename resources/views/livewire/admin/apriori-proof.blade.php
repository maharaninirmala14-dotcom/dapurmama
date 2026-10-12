<div>
    <style>
        .proof-panel {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            margin-bottom: 22px;
        }
        .proof-title {
            margin: 0 0 6px 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0b3d2e;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .proof-subtitle {
            margin: 0;
            font-size: 0.82rem;
            color: #64748b;
        }
        .proof-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            align-items: start;
        }
        @media (max-width: 992px) {
            .proof-grid { grid-template-columns: 1fr; }
        }
        .proof-section-label {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f4c3a;
            margin: 18px 0 8px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .proof-chip {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 6px;
            white-space: nowrap;
        }
        .proof-tx {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 8px 10px;
            margin-bottom: 6px;
            font-size: 0.8rem;
            color: #334155;
        }
        .proof-itemset {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 8px;
            padding: 7px 10px;
            margin-bottom: 6px;
            font-size: 0.82rem;
            color: #334155;
        }
        .proof-rule {
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }
        .proof-rec {
            border: 1px solid #d7e7e3;
            background: #f0fdfa;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }
        .proof-formula {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 6px;
            line-height: 1.5;
        }
        .proof-select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.88rem;
            background: #fff;
            box-sizing: border-box;
        }
        @media (max-width: 640px) {
            .proof-picker { grid-template-columns: 1fr !important; }
        }
    </style>

    <!-- JUDUL & PENJELASAN -->
    <div class="proof-panel">
        <h3 class="proof-title"><i class="fa-solid fa-flask" style="color:#0f4c3a;"></i> Bukti Kerja Algoritma Apriori</h3>
        <p class="proof-subtitle">
            Halaman ini menjalankan ulang Apriori dari <strong>riwayat transaksi masing-masing pelanggan</strong> (bukan data statis),
            lalu membandingkan frequent itemset, aturan asosiasi (Support / Confidence / Lift), dan rekomendasi yang muncul.
            Data transaksi berbeda &rarr; hasil analisis berbeda.
        </p>
    </div>

    <!-- PEMILIHAN PELANGGAN -->
    <div class="proof-panel">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;" class="proof-picker">
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">
                    <i class="fa-solid fa-user" style="color:#0f4c3a;"></i> Pelanggan 1
                </label>
                <select wire:model.live="userIdA" class="proof-select">
                    @foreach($customers as $c)
                        <option value="{{ $c['id'] }}">{{ $c['name'] }} — {{ $c['orders'] }} transaksi</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">
                    <i class="fa-solid fa-user" style="color:#0f4c3a;"></i> Pelanggan 2
                </label>
                <select wire:model.live="userIdB" class="proof-select">
                    @foreach($customers as $c)
                        <option value="{{ $c['id'] }}">{{ $c['name'] }} — {{ $c['orders'] }} transaksi</option>
                    @endforeach
                </select>
            </div>
        </div>

        @error('userIdA') <div style="color:#b91c1c; font-size:0.78rem; margin-top:6px;">{{ $message }}</div> @enderror
        @error('userIdB') <div style="color:#b91c1c; font-size:0.78rem; margin-top:6px;">{{ $message }}</div> @enderror

        @if($proofA && $proofB && $proofA['user'] && $proofB['user'] && $proofA['user']['id'] == $proofB['user']['id'])
            <div style="margin-top:12px; background:#FEF3C7; border:1px solid #FDE68A; color:#92400E; padding:9px 12px; border-radius:8px; font-size:0.82rem;">
                Pilih dua pelanggan yang berbeda agar perbandingannya berarti.
            </div>
        @endif

        @if($proofA && $proofB && $proofA['user'] && $proofB['user'] && $proofA['user']['id'] != $proofB['user']['id'])
            <div style="margin-top:14px; display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
                <span class="proof-chip" style="background:#f0f7f7; color:#0b3d2e;">Parameter: Min Support {{ rtrim(rtrim(number_format($proofA['min_support'] * 100, 2), '0'), '.') }}%</span>
                <span class="proof-chip" style="background:#FEFCBF; color:#744210;">Min Confidence {{ rtrim(rtrim(number_format($proofA['min_confidence'] * 100, 2), '0'), '.') }}%</span>
                <span class="proof-chip" style="background:#EEF2FF; color:#3730A3;">Sumber data: transaksi pelanggan yang dipilih</span>
            </div>
        @endif
    </div>

    @if($proofA && $proofB && $proofA['user'] && $proofB['user'] && $proofA['user']['id'] != $proofB['user']['id'])
        <div class="proof-grid">
            @foreach([['proof' => $proofA, 'label' => 'Pelanggan 1'], ['proof' => $proofB, 'label' => 'Pelanggan 2']] as $side)
                @php $proof = $side['proof']; @endphp
                <div class="proof-panel" style="margin-bottom:0;">
                    <!-- IDENTITAS -->
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; flex-wrap:wrap;">
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ $side['label'] }}</div>
                            <h3 class="proof-title" style="margin-bottom:2px;">{{ $proof['user']['name'] }}</h3>
                            <div style="font-size:0.78rem; color:#64748b;">{{ $proof['user']['email'] }}</div>
                        </div>
                        <span class="proof-chip" style="{{ $proof['personalized'] ? 'background:#DEF7EC; color:#03543F; border:1px solid #84E1BC;' : 'background:#FEF3C7; color:#92400E; border:1px solid #FDE68A;' }}">
                            {{ $proof['personalized'] ? 'Dianalisis dari riwayatnya sendiri' : 'Fallback ke data seluruh pelanggan' }}
                        </span>
                    </div>

                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:12px;">
                        <span class="proof-chip" style="background:#f0f7f7; color:#0b3d2e;">{{ $proof['transaction_count'] }} transaksi</span>
                        <span class="proof-chip" style="background:#f0f7f7; color:#0b3d2e;">{{ $proof['unique_items'] }} menu unik</span>
                        <span class="proof-chip" style="background:#EEF2FF; color:#3730A3;">{{ $proof['analyzed_transactions'] }} transaksi dianalisis</span>
                        <span class="proof-chip" style="background:#FDF2F8; color:#9D174D;">{{ count($proof['rules']) }} aturan lolos ambang</span>
                    </div>

                    <!-- 1. DATA TRANSAKSI -->
                    <div class="proof-section-label"><i class="fa-solid fa-database" style="color:#0f4c3a;"></i> 1. Data Transaksi (dataset Apriori)</div>
                    @if(empty($proof['transactions']))
                        <div class="proof-tx" style="color:#94a3b8;">Belum ada transaksi dari pelanggan ini.</div>
                    @else
                        @foreach($proof['transactions'] as $tx)
                            <div class="proof-tx">
                                <span style="font-weight:700;">{{ $tx['order_code'] }}</span>
                                <span style="color:#94a3b8; font-size:0.72rem;">{{ $tx['order_date'] }}</span>
                                <div style="margin-top:3px;">{{ implode(', ', $tx['menu_names']) }}</div>
                            </div>
                        @endforeach
                        @if($proof['transaction_count'] > count($proof['transactions']))
                            <div style="font-size:0.72rem; color:#94a3b8;">+ {{ $proof['transaction_count'] - count($proof['transactions']) }} transaksi lainnya ikut dianalisis.</div>
                        @endif
                    @endif

                    <!-- 2. FREQUENT ITEMSET -->
                    <div class="proof-section-label"><i class="fa-solid fa-layer-group" style="color:#0f4c3a;"></i> 2. Frequent Itemset (hasil Apriori)</div>
                    @if(empty($proof['frequent_itemsets']))
                        <div class="proof-tx" style="color:#94a3b8;">Tidak ada itemset yang lolos min support.</div>
                    @else
                        @foreach($proof['frequent_itemsets'] as $level => $itemsets)
                            <div style="font-size:0.75rem; font-weight:800; color:#475569; margin:8px 0 5px 0;">{{ $level }}-itemset</div>
                            @foreach($itemsets as $itemset)
                                <div class="proof-itemset">
                                    <span style="min-width:0;">{ {{ implode(', ', $itemset['menu_names']) }} }</span>
                                    <span class="proof-chip" style="background:#E6FFFA; color:#234E52; border:1px solid #84E1BC;">{{ $itemset['support'] }}%</span>
                                </div>
                            @endforeach
                        @endforeach
                    @endif

                    <!-- 3. ATURAN ASOSIASI -->
                    <div class="proof-section-label"><i class="fa-solid fa-arrow-right-arrow-left" style="color:#0f4c3a;"></i> 3. Aturan Asosiasi &amp; Metriknya</div>
                    @if(empty($proof['rules']))
                        <div class="proof-tx" style="color:#94a3b8;">Belum ada aturan yang lolos ambang minimum support/confidence.</div>
                    @else
                        @foreach($proof['rules'] as $rule)
                            @php
                                $liftValue = (float) $rule['lift'];
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
                            <div class="proof-rule">
                                <div style="font-size:0.85rem; color:#334155;">
                                    <strong style="color:#0f4c3a;">Jika</strong> { {{ implode(', ', $rule['antecedent_names']) }} }
                                    <strong style="color:#0f4c3a;">maka</strong> { {{ implode(', ', $rule['consequent_names']) }} }
                                </div>
                                <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
                                    <span class="proof-chip" style="background:#f0f7f7; color:#0b3d2e; border:1px solid #cfe6e2;">Support: {{ $rule['support'] }}%</span>
                                    <span class="proof-chip" style="background:#FEFCBF; color:#744210; border:1px solid #F6E05E;">Confidence: {{ $rule['confidence'] }}%</span>
                                    <span class="proof-chip" style="{{ $liftStyle }}">Lift: {{ number_format($liftValue, 2) }} ({{ $liftLabel }})</span>
                                </div>
                                <div class="proof-formula">
                                    Support = {{ $rule['support_count'] }}/{{ $rule['total'] }} transaksi = {{ $rule['support'] }}%
                                    &middot; Confidence = {{ $rule['support_count'] }}/{{ $rule['antecedent_count'] }} transaksi pelanggan yang membeli antecedent = {{ $rule['confidence'] }}%
                                    &middot; Lift = Support(A&cup;B) &divide; (Support(A) &times; Support(B)) = {{ number_format($liftValue, 2) }}
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- 4. REKOMENDASI -->
                    <div class="proof-section-label"><i class="fa-solid fa-lightbulb" style="color:#0f4c3a;"></i> 4. Rekomendasi yang Muncul</div>
                    @if(empty($proof['recommendations']))
                        <div class="proof-tx" style="color:#94a3b8;">Tidak ada rekomendasi yang bisa dibentuk dari data pelanggan ini.</div>
                    @else
                        @foreach($proof['recommendations'] as $index => $rec)
                            <div class="proof-rec">
                                <div style="display:flex; justify-content:space-between; gap:8px; align-items:baseline; flex-wrap:wrap;">
                                    <strong style="font-size:0.85rem; color:#0b3d2e;">Paket {{ $index + 1 }}: {{ implode(' + ', $rec['menu_names']) }}</strong>
                                    <span class="proof-chip" style="background:#fff; color:#0b3d2e; border:1px solid #84E1BC;">Support {{ $rec['support'] }}%</span>
                                </div>
                                <div style="font-size:0.78rem; color:#475569; margin-top:5px;">{{ $rec['description'] }}</div>
                                @if(($rec['source'] ?? '') === 'rule')
                                    <div class="proof-formula">
                                        Confidence {{ $rec['confidence'] }}% &middot; Lift {{ number_format($rec['lift'], 2) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

        <!-- KESIMPULAN (dihitung dari hasil kedua pelanggan) -->
        <div class="proof-panel" style="margin-top:22px; border-left:4px solid #0f4c3a;">
            <h3 class="proof-title"><i class="fa-solid fa-circle-check" style="color:#0f4c3a;"></i> Kesimpulan Perbandingan</h3>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin:14px 0;">
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
                    <div style="font-size:0.7rem; color:#64748b; text-transform:uppercase; font-weight:700;">Riwayat</div>
                    <div style="font-size:0.9rem; font-weight:700; color:#0b3d2e; margin-top:3px;">
                        {{ $comparison['same_history'] ? 'Sama' : 'Beda' }}
                    </div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
                    <div style="font-size:0.7rem; color:#64748b; text-transform:uppercase; font-weight:700;">Itemset</div>
                    <div style="font-size:0.9rem; font-weight:700; color:#0b3d2e; margin-top:3px;">
                        {{ $comparison['itemsets_count_a'] }} vs {{ $comparison['itemsets_count_b'] }}
                    </div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
                    <div style="font-size:0.7rem; color:#64748b; text-transform:uppercase; font-weight:700;">Aturan lolos ambang</div>
                    <div style="font-size:0.9rem; font-weight:700; color:#0b3d2e; margin-top:3px;">
                        {{ $comparison['rules_count_a'] }} vs {{ $comparison['rules_count_b'] }}
                    </div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
                    <div style="font-size:0.7rem; color:#64748b; text-transform:uppercase; font-weight:700;">Rekomendasi</div>
                    <div style="font-size:0.9rem; font-weight:700; color:#0b3d2e; margin-top:3px;">
                        {{ $comparison['same_recommendations'] ? 'Sama' : 'Beda' }}
                    </div>
                </div>
            </div>

            <p style="font-size:0.85rem; color:#334155; line-height:1.6; margin:0 0 10px 0;">
                {{ $proofA['user']['name'] }} belanja {{ $proofA['transaction_count'] }} transaksi &rarr; menghasilkan
                {{ $comparison['itemsets_count_a'] }} frequent itemset, {{ $comparison['rules_count_a'] }} aturan,
                dan rekomendasi berisi
                {{ $comparison['only_a_names'] ? implode(', ', $comparison['only_a_names']) : '-' }}{{ $comparison['shared_names'] ? ', serta ' . implode(', ', $comparison['shared_names']) : '' }}.
            </p>
            <p style="font-size:0.85rem; color:#334155; line-height:1.6; margin:0 0 10px 0;">
                {{ $proofB['user']['name'] }} belanja {{ $proofB['transaction_count'] }} transaksi &rarr; menghasilkan
                {{ $comparison['itemsets_count_b'] }} frequent itemset, {{ $comparison['rules_count_b'] }} aturan,
                dan rekomendasi berisi
                {{ $comparison['only_b_names'] ? implode(', ', $comparison['only_b_names']) : '-' }}{{ $comparison['shared_names'] ? ', serta ' . implode(', ', $comparison['shared_names']) : '' }}.
            </p>

            @if($comparison['same_recommendations'])
                <div style="background:#FEF3C7; border:1px solid #FDE68A; color:#92400E; padding:10px 14px; border-radius:8px; font-size:0.82rem;">
                    Kedua pelanggan kebetulan menghasilkan rekomendasi yang sama karena pola transaksinya memang serupa.
                </div>
            @else
                <div style="background:#DEF7EC; border:1px solid #84E1BC; color:#03543F; padding:10px 14px; border-radius:8px; font-size:0.82rem; display:flex; gap:8px; align-items:flex-start;">
                    <i class="fa-solid fa-check-circle" style="margin-top:2px;"></i>
                    <span>
                        Rekomendasi <strong>berbeda</strong> karena dataset transaksi kedua pelanggan berbeda.
                        Sistem menghitung ulang Support, Confidence, dan Lift dari transaksi masing-masing —
                        tidak ada nama pelanggan atau hasil yang ditulis manual di dalam kode.
                    </span>
                </div>
            @endif
        </div>
    @endif
</div>
