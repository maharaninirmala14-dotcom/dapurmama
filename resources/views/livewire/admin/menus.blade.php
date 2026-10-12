<div>
    <style>
        .page-container {
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        .breadcrumb {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .breadcrumb .text-green {
            color: #0f4c3a;
            font-weight: 600;
        }
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
            padding: 24px;
            width: 100%;
            box-sizing: border-box;
        }
        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .card-top-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px 0;
        }
        .card-top-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }
        .btn-add {
            background-color: #0f4c3a;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-add:hover { background-color: #0b3d2e; }
        .filter-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
        }
        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .search-input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85rem;
            outline: none;
            box-sizing: border-box;
        }
        
        /* PERBAIKAN UTAMA TABEL: Mengubah hidden menjadi auto agar bisa di-scroll horizontal dengan rapi di HP */
        .table-responsive { 
            width: 100%; 
            overflow-x: auto; 
            -webkit-overflow-scrolling: touch;
            margin-bottom: 15px;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 650px; /* Memastikan tabel punya batas aman minimal agar tidak terlipat paksa */
        }
        .custom-table thead th {
            background-color: #f3f7f6;
            color: #0f4c3a;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 12px 14px;
            white-space: nowrap;
        }
        .custom-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }
        .custom-table tbody tr:hover { background-color: #f8fafc; }
        .custom-table td {
            padding: 12px 14px;
            font-size: 0.82rem;
            vertical-align: middle;
        }
        .menu-img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            background: #e2e8f0;
        }
        .badge-cat {
            background: #fef3c7;
            color: #d97706;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }
        .action-group { display: flex; gap: 6px; }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            background: #fff;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-lihat { border: 1px solid #cbd5e1; color: #64748b; }
        .btn-edit { border: 1px solid #10b981; color: #10b981; }
        .btn-hapus { border: 1px solid #ef4444; color: #ef4444; }
        .pagination-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Styling Form / Detail Menu */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .content-card { padding: 15px; } /* Menyesuaikan padding card di HP agar lebih lega */
        }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .form-label span { color: #ef4444; }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.85rem;
            outline: none;
            box-sizing: border-box;
            background: #fff;
        }
        .form-input:focus { border-color: #0f4c3a; }
        .dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #fafafa;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .dropzone:hover { border-color: #0f4c3a; background: #f8fafc; }
        .price-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .price-prefix {
            position: absolute;
            left: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
        }
        .price-input { padding-left: 36px !important; }
    </style>

    <div class="page-container">
        
        <!-- Notifikasi Sukses -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
                 style="background-color: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border: 1px solid #bbf7d0; font-size: 0.85rem;">
                <i class="fa-solid fa-check-circle" style="margin-right: 5px;"></i> {{ session('message') }}
            </div>
        @endif

        @if(!$isForm)
            <!-- ================= TAMPILAN TABEL UTAMA ================= -->
            <h2 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Menu</h2>
            <div class="breadcrumb">
                <span class="text-green">Dashboard</span> 
                <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem;"></i> 
                <span>Menu</span>
            </div>

            <div class="content-card">
                <div class="card-top">
                    <div>
                        <h2 class="card-top-title">Daftar Menu</h2>
                        <p class="card-top-subtitle">Kelola semua menu catering Anda.</p>
                    </div>
                    <button wire:click="openCreate" class="btn-add">
                        <i class="fa-solid fa-plus"></i> Tambah Menu
                    </button>
                </div>

                <div class="filter-wrapper">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" class="search-input" placeholder="Cari menu...">
                    </div>
                </div>

                <!-- Pembungkus Tabel dengan Scroll Horizontal yang Aman -->
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 70px;">Foto</th>
                                <th style="min-width: 180px;">Nama Menu</th>
                                <th style="min-width: 120px;">Kategori</th>
                                <th style="min-width: 120px;">Harga</th>
                                <th style="width: 160px; text-align: right; padding-right: 20px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $index => $menu)
                            <tr>
                                <td style="color: #64748b; font-weight: 600;">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://via.placeholder.com/40' }}" class="menu-img" alt="Foto">
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b;">{{ $menu->name }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $menu->description }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-cat">{{ $menu->category->name ?? 'Umum' }}</span>
                                </td>
                                <td style="font-weight: 600; color: #1e293b; white-space: nowrap;">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td style="text-align: right; padding-right: 20px;">
                                    <div class="action-group" style="justify-content: flex-end;">
                                        <button wire:click="openView({{ $menu->id }})" class="btn-action btn-lihat" title="Detail">
                                            <i class="fa-regular fa-eye"></i> Detail
                                        </button>
                                        <button wire:click="openEdit({{ $menu->id }})" class="btn-action btn-edit" title="Edit">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $menu->id }})" class="btn-action btn-hapus" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada data menu ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-area">
                    <span>Menampilkan {{ $menus->count() }} menu</span>
                </div>
            </div>

        @else
            <!-- ================= TAMPILAN DETAIL / TAMBAH / EDIT MENU ================= -->
            <h2 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">
                {{ $isView ? 'Detail Menu' : ($isEditing ? 'Edit Menu' : 'Tambah Menu') }}
            </h2>
            <div class="breadcrumb">
                <span style="color: #64748b; cursor: pointer;" wire:click="cancelForm">Menu</span> 
                <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem;"></i> 
                <span class="text-green">{{ $isView ? 'Detail Menu' : ($isEditing ? 'Edit Menu' : 'Tambah Menu') }}</span>
            </div>

            <div class="content-card">
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">Informasi Menu</h3>
                    <p style="font-size: 0.83rem; color: #64748b; margin: 0;">
                        {{ $isView ? 'Berikut adalah detail informasi menu.' : ($isEditing ? 'Perbarui informasi menu ke dalam sistem.' : 'Tambah menu baru ke dalam sistem.') }}
                    </p>
                </div>

                <div class="form-grid">
                    <!-- Kolom Kiri: Informasi Teks -->
                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <div>
                            <label class="form-label">Nama Menu @if(!$isView)<span>*</span>@endif</label>
                            @if($isView)
                                <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600; color: #1e293b;">{{ $name }}</div>
                            @else
                                <input type="text" wire:model="name" class="form-input" placeholder="Masukkan nama menu">
                                @error('name') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        <div>
                            <label class="form-label">Kategori @if(!$isView)<span>*</span>@endif</label>
                            @if($isView)
                                <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600; color: #1e293b;">{{ $categoryName }}</div>
                            @else
                                <select wire:model="category_id" class="form-input">
                                    <option value="">Pilih kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        <div>
                            <label class="form-label">Harga @if(!$isView)<span>*</span>@endif</label>
                            @if($isView)
                                <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600; color: #1e293b;">Rp {{ number_format($price, 0, ',', '.') }}</div>
                            @else
                                <div class="price-wrapper">
                                    <span class="price-prefix">Rp</span>
                                    <input type="number" wire:model="price" class="form-input price-input" placeholder="Masukkan harga">
                                </div>
                                @error('price') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        <div>
                            <label class="form-label">Deskripsi @if(!$isView)<span>*</span>@endif</label>
                            @if($isView)
                                <div style="padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #334155; min-height: 90px; line-height: 1.5;">{{ $description }}</div>
                            @else
                                <textarea wire:model="description" rows="4" class="form-input" placeholder="Masukkan deskripsi menu" style="resize: vertical;"></textarea>
                                @error('description') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Foto Menu -->
                    <div>
                        <label class="form-label">Foto Menu @if(!$isView)<span>*</span>@endif</label>
                        @if($isView)
                            <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; background: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center; height: calc(100% - 25px);">
                                @if($oldImage)
                                    <img src="{{ asset('storage/' . $oldImage) }}" style="max-width: 100%; max-height: 240px; border-radius: 8px; object-fit: cover; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Tidak ada foto</span>
                                @endif
                            </div>
                        @else
                            <div class="dropzone" onclick="document.getElementById('fileUpload').click()">
                                <input type="file" id="fileUpload" wire:model="image" style="display: none;">
                                
                                @if (!empty($image))
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                        <img src="{{ $image->temporaryUrl() }}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                                        <span style="font-size: 0.8rem; color: #0f4c3a; font-weight: 600;">Foto baru dipilih (Klik untuk ganti)</span>
                                    </div>
                                @elseif(!empty($oldImage))
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                        <img src="{{ asset('storage/' . $oldImage) }}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Foto saat ini (Klik untuk ganti)</span>
                                    </div>
                                @else
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #0f4c3a; margin-bottom: 4px;"></i>
                                        <div style="font-weight: 600; color: #1e293b; font-size: 0.88rem;">Upload foto menu</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">PNG, JPG atau JPEG (Maks. 2MB)</div>
                                    </div>
                                @endif
                            </div>
                            @error('image') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>

                <!-- Tombol Aksi Bawah -->
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                    @if($isView)
                        <button wire:click="cancelForm" style="padding: 10px 24px; border-radius: 8px; border: none; background: #0f4c3a; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.85rem;">Kembali</button>
                    @else
                        <button wire:click="cancelForm" style="padding: 10px 22px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.85rem;">Batal</button>
                        <button wire:click="save" style="padding: 10px 24px; border-radius: 8px; border: none; background: #0f4c3a; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.85rem;">Simpan</button>
                    @endif
                </div>
            </div>
        @endif

        <!-- MODAL KONFIRMASI HAPUS -->
        @if($showDeleteModal)
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
            <div style="background: #fff; width: 100%; max-width: 440px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 30px 24px; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px auto;">
                    <i class="fa-regular fa-trash-can"></i>
                </div>
                <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 8px;">Hapus Menu</div>
                <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">Menu "{{ $deleteName }}" akan dihapus secara permanen dari sistem.</div>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button wire:click="closeDeleteModal" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                    <button wire:click="delete" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #ef4444; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Hapus</button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>