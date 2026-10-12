<div class="dashboard-page" style="padding: 10px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;">
    
    <!-- Ilustrasi Makanan di Tengah -->
    <div class="welcome-art-center" style="display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
        @if(file_exists(public_path('images/hero-dish.png')))
            <img src="{{ asset('images/hero-dish.png') }}" alt="Dish" style="height: 75px; width: auto; object-fit: contain;">
        @elseif(file_exists(public_path('images/dish.png')))
            <img src="{{ asset('images/dish.png') }}" alt="Dish" style="height: 75px; width: auto; object-fit: contain;">
        @else
            <div style="font-size: 32px;"></div>
        @endif
    </div>

    <!-- PINTASAN: BUKTI APRIORI (revisi demo) -->
    <a href="{{ route('admin.apriori.proof') }}" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; background: linear-gradient(135deg, #0f4c3a, #2b5352); color: #fff; text-decoration: none; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(15,76,58,0.25);">
        <div style="display: flex; align-items: center; gap: 14px; min-width: 0;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                <i class="fa-solid fa-flask"></i>
            </div>
            <div style="min-width: 0;">
                <div style="font-size: 0.95rem; font-weight: 800;">Bukti Kerja Algoritma Apriori</div>
                <div style="font-size: 0.8rem; opacity: 0.85; margin-top: 2px;">Bandingkan rekomendasi Nirmala vs Nirmanto — dihitung dari riwayat transaksi masing-masing</div>
            </div>
        </div>
        <span style="background: #fff; color: #0f4c3a; font-size: 0.8rem; font-weight: 800; padding: 8px 16px; border-radius: 8px; white-space: nowrap;">
            Buka Halaman <i class="fa-solid fa-arrow-right" style="font-size: 11px; margin-left: 4px;"></i>
        </span>
    </a>

    <!-- Stats Grid -->
    <div class="stats-grid resp-grid-4">
        
        <!-- Card 1 -->
        <div class="stat-card" style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #0f766e; font-size: 20px; flex-shrink: 0;">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Pesanan</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $totalOrders }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">Keseluruhan data</span>
                <a href="{{ route('admin.orders') }}" style="font-size: 0.8rem; color: #0f766e; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Lihat detail <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="stat-card" style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; color: #15803d; font-size: 20px; flex-shrink: 0;">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Pelanggan</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $totalCustomers }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">Aktif terdaftar</span>
                <a href="{{ route('admin.customers') }}" style="font-size: 0.8rem; color: #15803d; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Lihat detail <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="stat-card" style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 20px; flex-shrink: 0;">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Pesanan Terbaru</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $latestOrders->count() }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">Data mutakhir</span>
                <a href="{{ route('admin.orders') }}" style="font-size: 0.8rem; color: #d97706; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Lihat detail <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="stat-card" style="background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #f3e8ff; display: flex; align-items: center; justify-content: center; color: #7e22ce; font-size: 16px; flex-shrink: 0; font-weight: bold;">
                    Rp
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap;">Total Pendapatan</div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Rp {{ number_format($totalRevenue, 0, ',', '.') }}">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div style="margin-top: 16px; border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">{{ \Carbon\Carbon::now()->format('M Y') }}</span>
                <a href="{{ route('admin.reports') }}" style="font-size: 0.8rem; color: #7e22ce; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    Lihat detail <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Tengah (Grafik & Tabel Pesanan Terbaru) -->
    <div class="dashboard-grid resp-grid-2">
        
        <!-- Panel Grafik Professional (Chart.js) -->
        <div class="panel panel-chart" style="background: #ffffff; padding: 20px 15px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box; overflow: hidden;">
            <div class="panel-head" style="margin-bottom: 15px;">
                <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a;">Grafik Jumlah Pesanan <span style="font-size: 0.8rem; font-weight: normal; color: #64748b;">(6 Bulan Terakhir)</span></h3>
            </div>
            <div style="position: relative; height: 260px; width: 100%; box-sizing: border-box;">
                <canvas id="orderChart"></canvas>
            </div>
        </div>

        <!-- Panel Pesanan Terbaru -->
        <div class="panel panel-table" style="background: #ffffff; padding: 20px 15px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box; overflow: hidden;">
            <div class="panel-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a;">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders') }}" class="mini-btn" style="font-size: 0.8rem; padding: 6px 12px; background: #f1f5f9; color: #334155; border-radius: 8px; text-decoration: none; font-weight: 600;">Lihat Semua</a>
            </div>

            <!-- Pembungkus Tabel dengan Scroll Horizontal Mandiri -->
            <div class="table-wrap" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; min-width: 500px;">
                    <thead>
                        <tr style="border-bottom: 1px solid #f1f5f9; color: #64748b;">
                            <th style="padding: 10px 8px; font-weight: 600; white-space: nowrap;">No. Pesanan</th>
                            <th style="padding: 10px 8px; font-weight: 600; white-space: nowrap;">Pelanggan</th>
                            <th style="padding: 10px 8px; font-weight: 600; white-space: nowrap;">Tanggal</th>
                            <th style="padding: 10px 8px; font-weight: 600; white-space: nowrap;">Total</th>
                            <th style="padding: 10px 8px; font-weight: 600; white-space: nowrap;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td style="padding: 12px 8px; white-space: nowrap;"><strong>{{ $order->order_code }}</strong></td>
                                <td style="padding: 12px 8px; white-space: nowrap; max-width: 110px; overflow: hidden; text-overflow: ellipsis;">{{ $order->user->name ?? 'Pelanggan' }}</td>
                                <td style="padding: 12px 8px; white-space: nowrap;">{{ $order->created_at->format('d M Y') }}</td>
                                <td style="padding: 12px 8px; white-space: nowrap;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td style="padding: 12px 8px; white-space: nowrap;">
                                    <span class="status-badge {{ strtolower($order->status) }}" style="padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 24px; text-align: center; color: #94a3b8;">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="bottom-grid resp-grid-2">
        
        <!-- Menu Terlaris -->
        <div class="panel panel-list" style="background: #ffffff; padding: 20px 15px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box; overflow: hidden;">
            <div class="panel-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a;">Menu Terlaris <span style="font-size: 0.8rem; font-weight: normal; color: #64748b;">(Bulan Ini)</span></h3>
                <a href="{{ route('admin.menus') }}" class="mini-btn" style="font-size: 0.8rem; padding: 6px 12px; background: #f1f5f9; color: #334155; border-radius: 8px; text-decoration: none; font-weight: 600;">Lihat Semua</a>
            </div>

            @php $maxOrders = $topMenus->first()?->orders_count ?: 1; @endphp
            <div class="menu-rank-list" style="display: flex; flex-direction: column; gap: 12px; width: 100%; box-sizing: border-box;">
                @forelse($topMenus as $menu)
                    @php $barPct = $menu->orders_count > 0 ? ($menu->orders_count / $maxOrders) * 100 : 0; @endphp
                    <div class="menu-rank-item" style="display: flex; align-items: center; justify-content: space-between; font-size: 0.85rem; width: 100%; box-sizing: border-box;">
                        <div class="menu-rank-name" style="display: flex; align-items: center; gap: 8px; width: 38%; min-width: 0;">
                            <span class="rank-number" style="font-weight: bold; color: #64748b; flex-shrink: 0;">{{ $loop->iteration }}</span>
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $menu->name }}">{{ $menu->name }}</span>
                        </div>
                        <div class="menu-rank-bar-wrap" style="flex-grow: 1; background: #f1f5f9; height: 8px; border-radius: 4px; margin: 0 10px; overflow: hidden;">
                            <div class="menu-rank-bar" style="width: {{ $barPct }}%; background: #0f766e; height: 100%; border-radius: 4px;"></div>
                        </div>
                        <span class="menu-rank-count" style="font-weight: bold; color: #0f172a; flex-shrink: 0; text-align: right;">{{ $menu->orders_count }}</span>
                    </div>
                @empty
                    <p class="empty-state" style="color: #94a3b8; text-align: center; padding: 20px;">Belum ada data menu.</p>
                @endforelse
            </div>
        </div>

        <!-- Ringkasan Pesanan Hari Ini -->
        <div class="panel panel-summary" style="background: #ffffff; padding: 20px 15px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box; overflow: hidden;">
            <div class="panel-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a;">Ringkasan Pesanan Hari Ini</h3>
                <a href="{{ route('admin.orders') }}" class="mini-btn" style="font-size: 0.8rem; padding: 6px 12px; background: #f1f5f9; color: #334155; border-radius: 8px; text-decoration: none; font-weight: 600;">Lihat Semua</a>
            </div>

            <div class="summary-wrap" style="display: flex; align-items: center; justify-content: space-around; padding-top: 10px; flex-wrap: wrap; gap: 20px; width: 100%; box-sizing: border-box;">
                <div class="donut-chart" style="width: 120px; height: 120px; border-radius: 50%; background: conic-gradient(#10b981 0% 33.3%, #f59e0b 33.3% 83.3%, #ef4444 83.3% 100%); display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                    <div class="donut-inner" style="width: 82px; height: 82px; background: #ffffff; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                        <strong style="font-size: 1.1rem; color: #0f172a; line-height: 1;">12</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Total</span>
                    </div>
                </div>

                <div class="summary-legend" style="display: flex; flex-direction: column; gap: 10px; font-size: 0.85rem;">
                    <div class="legend-row" style="display: flex; align-items: center; gap: 10px;">
                        <span class="legend-dot" style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; flex-shrink: 0;"></span>
                        <span class="legend-label" style="color: #64748b; width: 75px;">Selesai</span>
                        <strong style="color: #0f172a; width: 15px;">4</strong>
                        <span class="legend-percent" style="color: #94a3b8; font-size: 0.78rem;">(33.3%)</span>
                    </div>
                    <div class="legend-row" style="display: flex; align-items: center; gap: 10px;">
                        <span class="legend-dot" style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b; flex-shrink: 0;"></span>
                        <span class="legend-label" style="color: #64748b; width: 75px;">Diproses</span>
                        <strong style="color: #0f172a; width: 15px;">6</strong>
                        <span class="legend-percent" style="color: #94a3b8; font-size: 0.78rem;">(50.0%)</span>
                    </div>
                    <div class="legend-row" style="display: flex; align-items: center; gap: 10px;">
                        <span class="legend-dot" style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; flex-shrink: 0;"></span>
                        <span class="legend-label" style="color: #64748b; width: 75px;">Dibatalkan</span>
                        <strong style="color: #0f172a; width: 15px;">2</strong>
                        <span class="legend-percent" style="color: #94a3b8; font-size: 0.78rem;">(16.7%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SCRIPT CHART.JS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }

            const canvas = document.getElementById('orderChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                
                const chartLabels = {!! json_encode($chartLabels) !!};
                const chartData = {!! json_encode($chartData) !!};

                const gradient = ctx.createLinearGradient(0, 0, 0, 240);
                gradient.addColorStop(0, 'rgba(15, 118, 110, 0.3)');
                gradient.addColorStop(1, 'rgba(15, 118, 110, 0.0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Jumlah Pesanan',
                            data: chartData,
                            borderColor: '#0f766e',
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0f766e',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: Math.max(...chartData) + 2, 
                                grid: { color: '#f8fafc' },
                                ticks: { 
                                    stepSize: 1,
                                    font: { size: 11 } 
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: {
                                    boxWidth: 12,
                                    boxHeight: 4,
                                    font: { size: 11 },
                                    color: '#64748b'
                                }
                            },
                            datalabels: {
                                align: 'top',
                                anchor: 'end',
                                color: '#0f172a',
                                font: {
                                    weight: 'bold',
                                    size: 11
                                },
                                formatter: function(value) {
                                    return value > 0 ? value : '';
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</div>