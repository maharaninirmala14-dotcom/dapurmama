@section('title', 'Kelola Pelanggan (Customer)')
<div>
    <!-- STAT CARDS -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
        <div class="stat-card" style="display:flex; align-items:center; gap:16px; background:white; padding:20px; border-radius:var(--radius-md); border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
            <div class="stat-icon" style="width:48px; height:48px; border-radius:var(--radius-sm); background:rgba(61,111,112,0.1); color:var(--primary); display:grid; place-items:center; font-size:1.3rem;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; font-weight:600;">Total Pelanggan</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--primary-dark);">{{ $totalCustomers }}</div>
            </div>
        </div>

        <div class="stat-card" style="display:flex; align-items:center; gap:16px; background:white; padding:20px; border-radius:var(--radius-md); border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
            <div class="stat-icon" style="width:48px; height:48px; border-radius:var(--radius-sm); background:rgba(56,161,105,0.1); color:var(--success); display:grid; place-items:center; font-size:1.3rem;">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <div style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; font-weight:600;">Pelanggan Aktif</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--primary-dark);">{{ $activeCustomers }}</div>
            </div>
        </div>

        <div class="stat-card" style="display:flex; align-items:center; gap:16px; background:white; padding:20px; border-radius:var(--radius-md); border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
            <div class="stat-icon" style="width:48px; height:48px; border-radius:var(--radius-sm); background:rgba(119,216,216,0.25); color:var(--primary-dark); display:grid; place-items:center; font-size:1.3rem;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div style="font-size:0.78rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; font-weight:600;">Total Pesanan Customer</div>
                <div style="font-size:1.5rem; font-weight:800; color:var(--primary-dark);">{{ $totalCustomerOrders }}</div>
            </div>
        </div>
    </div>

    <!-- Pesan Sukses Flash -->
    @if (session()->has('message'))
        <div style="background: #dcfce7; color: #15803d; padding: 12px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; margin-bottom: 20px;">
            {{ session('message') }}
        </div>
    @endif

    <!-- TABLE PANEL -->
    <div class="table-panel" style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div class="table-header-bar" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="flex:1; min-width:240px; max-width:400px; position: relative;">
                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, email, atau no telp..." class="form-control table-search-input" style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; outline: none; box-sizing: border-box;">
            </div>
            <button wire:click="openCreate" class="btn-primary" style="background: #0f766e; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; display:inline-flex; align-items:center; gap:8px; cursor: pointer;">
                <i class="fa-solid fa-user-plus"></i> Tambah Pelanggan
            </button>
        </div>

        <div style="width: 100%; overflow-x: auto;">
            <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; table-layout: fixed;">
                <thead>
                    <tr style="background-color: #f8fafc; color: #64748b; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 14px; font-weight: 700; width: 50px;">#</th>
                        <th style="padding: 12px 14px; font-weight: 700; width: 18%; min-width: 150px;">Nama Pelanggan</th>
                        <th style="padding: 12px 14px; font-weight: 700; width: 22%; min-width: 170px;">Kontak</th>
                        <th style="padding: 12px 14px; font-weight: 700; width: 22%; min-width: 160px;">Alamat</th>
                        <th style="padding: 12px 14px; font-weight: 700; width: 15%; min-width: 135px;">Total Pesanan</th>
                        <th style="padding: 12px 14px; font-weight: 700; width: 11%; min-width: 100px;">Tgl Bergabung</th>
                        <th style="padding: 12px 14px; font-weight: 700; text-align: center; width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $cust)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <td style="padding: 14px; vertical-align: middle;">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                        <td style="padding: 14px; vertical-align: middle; word-break: break-word;">
                            <strong style="color: #0f172a; display: block; line-height: 1.3;">{{ $cust->name }}</strong>
                            <div style="font-size:0.75rem; color:#64748b; margin-top: 2px;">ID: #CUST-{{ str_pad($cust->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td style="padding: 14px; vertical-align: middle; color:#475569; word-break: break-all;">
                            <div style="margin-bottom: 2px;"><i class="fa-solid fa-envelope" style="font-size:0.7rem; color:#94a3b8;"></i> {{ $cust->email }}</div>
                            <div style="font-size:0.8rem; color:#64748b;"><i class="fa-solid fa-phone" style="font-size:0.7rem;"></i> {{ $cust->phone ?? '-' }}</div>
                        </td>
                        <td style="padding: 14px; vertical-align: middle; color:#475569; word-break: break-word; line-height: 1.4;">
                            {{ $cust->address ?? 'Belum ada alamat' }}
                        </td>
                        <td style="padding: 14px; vertical-align: middle;">
                            @if(isset($cust->orders_count) && $cust->orders_count > 0)
                                <span class="status-badge selesai" style="background: #dcfce7; color: #15803d; padding: 5px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                    <i class="fa-solid fa-bag-shopping"></i> {{ $cust->orders_count }} pesanan
                                </span>
                            @else
                                <span style="font-size:0.8rem; color:#94a3b8; font-style:italic; white-space: nowrap;">Belum ada pesanan</span>
                            @endif
                        </td>
                        <td style="padding: 14px; vertical-align: middle; color:#64748b; font-size:0.85rem; white-space: nowrap;">{{ $cust->created_at ? $cust->created_at->format('d M Y') : '-' }}</td>
                        <td style="padding: 14px; vertical-align: middle; text-align: center;">
                            <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: nowrap;">
                                <button wire:click="openDetail({{ $cust->id }})" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer;" title="Detail & Riwayat Pesanan">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </button>
                                <button wire:click="openEdit({{ $cust->id }})" style="background: #fef3c7; color: #b45309; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer;" title="Edit Data">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <button wire:click="confirmDelete({{ $cust->id }})" style="background: #fee2e2; color: #b91c1c; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer;" title="Hapus Pelanggan">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#94a3b8; padding:36px;">
                            <i class="fa-solid fa-users-slash" style="font-size:2.2rem; margin-bottom:10px; color:#cbd5e1; display:block;"></i>
                            Tidak ada data pelanggan ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION MANUAL KOTAK RAPI DI TENGAH -->
        <div style="padding: 20px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; align-items: center; background: #ffffff;">
            @if ($customers->hasPages())
                <div style="display: flex; align-items: center; gap: 6px;">
                    
                    {{-- Tombol Previous (<) --}}
                    @if ($customers->onFirstPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #f8fafc; color: #cbd5e1; border: 1px solid #f1f5f9; border-radius: 8px; font-size: 0.85rem; cursor: not-allowed;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 12px;"></i>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage" wire:loading.attr="disabled" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #ffffff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 12px;"></i>
                        </button>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                        @if ($page == $customers->currentPage())
                            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #2b5352; color: #ffffff; border: 1px solid #2b5352; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }})" style="display: inline-flex; align-items: center; justify-content: center; min-width: 38px; height: 38px; padding: 0 10px; background: #ffffff; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach

                    {{-- Tombol Next (>) --}}
                    @if ($customers->hasMorePages())
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

    <!-- MODAL CREATE / EDIT -->
    @if($showModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 520px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                <h3 style="font-size: 1rem; font-weight: bold; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid {{ $isEditing ? 'fa-user-pen' : 'fa-user-plus' }}" style="color: #0f766e;"></i>
                    {{ $isEditing ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru (Offline / WhatsApp)' }}
                </h3>
                <button wire:click="$set('showModal', false)" style="background: transparent; border: none; font-size: 1.1rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            <div style="padding: 20px; max-height: 75vh; overflow-y: auto;">
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 5px;">Nama Lengkap *</label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap pelanggan" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box;">
                    @error('name') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 5px;">Alamat Email *</label>
                    <input type="email" wire:model="email" placeholder="contoh@domain.com" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box;">
                    @error('email') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 5px;">Nomor Telepon / WhatsApp</label>
                    <input type="text" wire:model="phone" placeholder="Contoh: 081234567890" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box;">
                    @error('phone') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 5px;">Alamat Lengkap</label>
                    <textarea wire:model="address" rows="3" placeholder="Alamat rumah / pengiriman pelanggan" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box;"></textarea>
                    @error('address') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 5px;">Password {{ $isEditing ? '(Opsional)' : '*' }}</label>
                    <input type="text" wire:model="password" placeholder="{{ $isEditing ? 'Kosongkan jika tidak mengubah password' : 'Minimal 6 karakter' }}" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box;">
                    <span style="font-size: 0.72rem; color: #64748b; display: block; margin-top: 3px;">Password default otomatis terisi, dapat diinformasikan ke pelanggan.</span>
                    @error('password') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div style="padding: 14px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc;">
                <button wire:click="$set('showModal', false)" style="background: #e2e8f0; color: #334155; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Batal</button>
                <button wire:click="save" style="background: #0f766e; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                    <i class="fa-solid fa-floppy-disk"></i> {{ $isEditing ? 'Perbarui' : 'Simpan' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL DETAIL & RIWAYAT PESANAN -->
    @if($showDetailModal && $selectedCustomer)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 720px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-height: 90vh; overflow-y: auto;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; position: sticky; top: 0; z-index: 10;">
                <h3 style="margin:0; font-size: 1rem; font-weight: bold; color: #0f172a; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-id-card" style="color: #0f766e;"></i>
                    Detail Pelanggan: {{ $selectedCustomer->name }}
                </h3>
                <button wire:click="$set('showDetailModal', false)" style="background: transparent; border: none; font-size: 1.1rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            <div style="padding: 20px;">
                <!-- INFORMASI PROFIL -->
                <div style="background:#f8fbfb; border:1px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:20px; display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px;">
                    <div>
                        <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Nama Lengkap</div>
                        <div style="font-weight:700; color:#0f172a;">{{ $selectedCustomer->name }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Email</div>
                        <div style="font-weight:600; color:#334155;">{{ $selectedCustomer->email }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Nomor Telepon</div>
                        <div style="font-weight:600; color:#334155;">{{ $selectedCustomer->phone ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Tanggal Bergabung</div>
                        <div style="font-weight:600; color:#334155;">{{ $selectedCustomer->created_at ? $selectedCustomer->created_at->format('d M Y, H:i') : '-' }}</div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <div style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600;">Alamat Lengkap</div>
                        <div style="font-weight:500; color:#334155;">{{ $selectedCustomer->address ?? 'Belum ada alamat tercatat.' }}</div>
                    </div>
                </div>

                <!-- RIWAYAT PESANAN -->
                <h4 style="margin-bottom:12px; font-size:1rem; font-weight: bold; color:#0f172a; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-receipt"></i> Riwayat Pesanan ({{ $selectedCustomer->orders->count() }})
                </h4>

                @if($selectedCustomer->orders->isEmpty())
                    <div style="text-align:center; padding:30px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0; color:#64748b; font-size: 0.88rem;">
                        Pelanggan ini belum pernah melakukan pemesanan.
                    </div>
                @else
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        @foreach($selectedCustomer->orders as $ord)
                        <div style="border:1px solid #e2e8f0; border-radius:8px; padding:14px; background:white;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; flex-wrap:wrap; gap:8px;">
                                <div>
                                    <strong style="color:#0f172a; font-size:0.95rem;">{{ $ord->order_code }}</strong>
                                    <span style="font-size:0.8rem; color:#64748b; margin-left:8px;">
                                        <i class="fa-solid fa-calendar"></i> {{ $ord->created_at ? $ord->created_at->format('d M Y H:i') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span style="padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; background: #e2e8f0; color: #334155;">
                                        {{ $ord->status }}
                                    </span>
                                </div>
                            </div>

                            <div style="font-size:0.85rem; color:#334155; margin-bottom:8px;">
                                <strong>Items:</strong>
                                @if(isset($ord->orderDetails) && $ord->orderDetails->count() > 0)
                                    @foreach($ord->orderDetails as $det)
                                        <span style="display:inline-block; background:#f0f7f7; padding:2px 8px; border-radius:12px; font-size:0.78rem; margin:2px;">
                                            {{ $det->qty ?? $det->quantity }}x {{ $det->menu ? $det->menu->name : ($det->package ? $det->package->name : 'Item') }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: #64748b; font-style: italic;">Detail item tidak tersedia</span>
                                @endif
                            </div>

                            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px dashed #e2e8f0; padding-top:8px; font-size:0.9rem;">
                                <span style="color:#64748b;">Total Pembayaran:</span>
                                <strong style="color:#0f172a;">Rp {{ number_format($ord->total_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div style="padding: 14px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; background: #f8fafc;">
                <button wire:click="$set('showDetailModal', false)" style="background: #334155; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Tutup</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL KONFIRMASI HAPUS -->
    @if($showDeleteModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 440px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 30px 24px; text-align: center;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px auto;">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 8px;">Hapus Pelanggan</div>
            <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">Data pelanggan "{{ $deleteName }}" akan dihapus secara permanen beserta riwayat pesanannya.</div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button wire:click="closeDeleteModal" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                <button wire:click="delete" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #ef4444; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>