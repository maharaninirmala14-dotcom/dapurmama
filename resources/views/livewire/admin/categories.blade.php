@section('title', 'Kelola Kategori Menu')
<div class="page-container" style="font-family: 'Inter', sans-serif; color: #334155;">
    
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">Kelola Kategori</h2>
        <div style="font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
            <span style="color: #1f3a38; font-weight: 600;">Dashboard</span> 
            <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem;"></i> 
            <span>Kategori</span>
        </div>
    </div>

    <!-- Notifikasi Flash -->
    @if (session()->has('message'))
        <div style="background-color: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; border: 1px solid #bbf7d0; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-check-circle"></i> {{ session('message') }}
        </div>
    @endif

    <!-- Card Panel Utama -->
    <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; padding: 24px;">
        
        <!-- Header Kartu & Tombol Tambah -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">Daftar Kategori</h2>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Kelola kategori menu catering (Nasi Box, Tumpeng, Snack Basah, Snack Kering).</p>
            </div>
            <button wire:click="openCreate" style="background-color: #1f3a38; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <!-- Kolom Pencarian -->
        <div style="position: relative; width: 300px; max-width: 100%; margin-bottom: 20px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem;"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kategori..." style="width: 100%; padding: 10px 14px 10px 38px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; color: #334155; outline: none; box-sizing: border-box;">
        </div>

        <!-- Tabel Kategori -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f3f7f6; color: #1f3a38;">
                        <th style="font-size: 0.85rem; font-weight: 700; padding: 14px 20px; border-top-left-radius: 8px; border-bottom-left-radius: 8px; width: 50px;">No</th>
                        <th style="font-size: 0.85rem; font-weight: 700; padding: 14px 20px;">Nama Kategori</th>
                        <th style="font-size: 0.85rem; font-weight: 700; padding: 14px 20px;">Deskripsi Penawaran</th>
                        <th style="font-size: 0.85rem; font-weight: 700; padding: 14px 20px; text-align: center;">Jumlah Menu</th>
                        <th style="font-size: 0.85rem; font-weight: 700; padding: 14px 20px; border-top-right-radius: 8px; border-bottom-right-radius: 8px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $cat)
                    @php
                        // Pemetaan ikon & warna eksklusif untuk 4 kategori utama
                        $st = ['bg' => '#e6f4ea', 'color' => '#137333', 'icon' => 'fa-box'];
                        if(stripos($cat->name, 'tumpeng') !== false) {
                            $st = ['bg' => '#fef7e0', 'color' => '#b06000', 'icon' => 'fa-mountain'];
                        } elseif(stripos($cat->name, 'snack basah') !== false) {
                            $st = ['bg' => '#fce8e6', 'color' => '#c5221f', 'icon' => 'fa-cookie-bite'];
                        } elseif(stripos($cat->name, 'snack kering') !== false) {
                            $st = ['bg' => '#f3e8ff', 'color' => '#7e22ce', 'icon' => 'fa-cookie'];
                        } elseif(stripos($cat->name, 'nasi box') !== false) {
                            $st = ['bg' => '#e6f4ea', 'color' => '#137333', 'icon' => 'fa-box'];
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                        <td style="padding: 16px 20px; font-size: 0.88rem; color: #64748b; font-weight: 600;">{{ $loop->iteration }}</td>
                        <td style="padding: 16px 20px; font-size: 0.88rem;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $st['bg'] }}; color: {{ $st['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
                                    <i class="fa-solid {{ $st['icon'] }}"></i>
                                </div>
                                <div>
                                    <span style="font-weight: 700; color: #1e293b; display: block;">{{ $cat->name }}</span>
                                    <span style="font-size: 0.75rem; color: #64748b;">Slug: {{ $cat->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.88rem; color: #64748b; max-width: 320px;">
                            {{ $cat->description ?: 'Belum ada deskripsi penawaran.' }}
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.88rem; text-align: center; font-weight: 600; color: #1e293b;">
                            <span style="background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">
                                {{ $cat->menus_count ?? 0 }} Menu
                            </span>
                        </td>
                        <td style="padding: 16px 20px; font-size: 0.88rem;">
                            <div style="display: flex; gap: 8px;">
                                <button wire:click="openView({{ $cat->id }})" style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer; background: transparent; border: 1px solid #cbd5e1; color: #64748b;" title="Lihat Detail">
                                    <i class="fa-regular fa-eye"></i> Lihat
                                </button>
                                <button wire:click="openEdit({{ $cat->id }})" style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer; background: transparent; border: 1px solid #10b981; color: #10b981;" title="Edit Kategori">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <button wire:click="confirmDelete({{ $cat->id }})" style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer; background: transparent; border: 1px solid #ef4444; color: #ef4444;" title="Hapus Kategori">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">Tidak ada data kategori ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination Info -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; font-size: 0.85rem; color: #64748b;">
            <span>Menampilkan total <strong>{{ count($categories) }}</strong> kategori</span>
        </div>
    </div>

    <!-- MODAL FORM TAMBAH / EDIT KATEGORI -->
    @if($showModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div style="padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 1.15rem; color: #1e293b; font-weight: 700;">{{ $isEditing ? 'Edit Kategori Menu' : 'Tambah Kategori Menu Baru' }}</h3>
                <button wire:click="$set('showModal', false)" style="background: transparent; border: none; font-size: 1.3rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            
            <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Nama Kategori <span style="color: #ef4444;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Pilih: Nasi Box, Tumpeng, Snack Basah, atau Snack Kering" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                    @error('name') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Deskripsi Penawaran / Kegunaan <span style="color: #ef4444;">*</span></label>
                    <textarea wire:model="description" rows="3" placeholder="Jelaskan peruntukan acara, porsi, atau keunggulan kategori ini..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; outline: none; box-sizing: border-box; resize: vertical;"></textarea>
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 4px; display: block;">Contoh: Paket praktis dan higienis untuk rapat, seminar, atau acara kantor.</span>
                    @error('description') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                @if($isEditing)
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Jumlah Menu Terdaftar</label>
                    <input type="text" value="{{ $currentMenusCount }} Menu" disabled style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; background-color: #f1f5f9; color: #64748b; cursor: not-allowed; box-sizing: border-box;">
                </div>
                @endif
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc;">
                <button wire:click="$set('showModal', false)" style="padding: 9px 18px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.85rem;">Batal</button>
                <button wire:click="save" style="padding: 9px 22px; border-radius: 8px; border: none; background: #1f3a38; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.85rem;">{{ $isEditing ? 'Update' : 'Simpan' }}</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL DETAIL KATEGORI (LIHAT) -->
    @if($showViewModal && $selectedCategory)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
            <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.15rem; color: #1e293b; font-weight: 700;">Detail Kategori</h3>
                <button wire:click="$set('showViewModal', false)" style="background: transparent; border: none; font-size: 1.3rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            
            <div style="padding: 24px; display: flex; flex-direction: column; gap: 14px; text-align: left;">
                <div>
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 2px;">Nama Kategori</span>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">{{ $selectedCategory->name }}</span>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 2px;">Slug URL</span>
                    <span style="font-size: 0.85rem; color: #475569; font-family: monospace;">{{ $selectedCategory->slug }}</span>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 2px;">Jumlah Menu Terdaftar</span>
                    <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">{{ $selectedCategory->menus_count }} Menu</span>
                </div>
                <div>
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; display: block; margin-bottom: 4px;">Deskripsi Penawaran</span>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; color: #475569; font-size: 0.88rem; line-height: 1.5;">
                        {{ $selectedCategory->description ?: 'Tidak ada deskripsi penawaran.' }}
                    </div>
                </div>
            </div>

            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                <button wire:click="$set('showViewModal', false)" style="padding: 8px 20px; border-radius: 8px; border: none; background: #1f3a38; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.85rem;">Tutup</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL KONFIRMASI HAPUS -->
    @if($showDeleteModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px;">
        <div style="background: #fff; width: 100%; max-width: 440px; border-radius: 14px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 30px 24px; text-align: center;">
            <h3 style="margin: 0 0 20px 0; font-size: 1.15rem; color: #1e293b; font-weight: 700;">Hapus Kategori</h3>
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px auto;">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 8px;">Yakin ingin menghapus kategori ini?</div>
            <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">Kategori "{{ $deleteName }}" akan dihapus secara permanen dari sistem.</div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button wire:click="$set('showDeleteModal', false)" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                <button wire:click="delete" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #ef4444; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Hapus</button>
            </div>
        </div>
    </div>
    @endif

</div>