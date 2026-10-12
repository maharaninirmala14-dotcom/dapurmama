@section('title', 'Kelola Paket Menu')
<div>
    <div class="table-panel">
        <div class="table-header-bar">
            <h3 style="margin:0;">Daftar Paket Menu Catering</h3>
            <button wire:click="openCreate" class="btn-primary" style="padding:10px 20px; font-size:0.9rem;"><i class="fa-solid fa-plus"></i> Tambah Paket</button>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr><th>#</th><th>Nama Paket</th><th>Menu</th><th>Harga</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($packages as $pkg)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $pkg->name }}</strong>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ Str::limit($pkg->description, 50) }}</div>
                        </td>
                        <td>
                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                @foreach($pkg->menus as $m)
                                    <span style="font-size:0.72rem; background:#f0f7f7; padding:2px 8px; border-radius:var(--radius-full); color:var(--primary-dark);">{{ $m->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
                        <td>
                            <div class="action-btns">
                                <button wire:click="openEdit({{ $pkg->id }})" class="action-btn edit"><i class="fa-solid fa-pen"></i> Edit</button>
                                <button wire:click="confirmDelete({{ $pkg->id }})" class="action-btn delete"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; color:var(--text-muted); padding:30px;">Tidak ada paket menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showModal)
    <div class="modal-backdrop" wire:click.self="$set('showModal', false)">
        <div class="modal-content" style="max-width:600px; max-height:90vh; overflow-y:auto;">
            <div class="modal-header">
                <h3 style="margin:0;">{{ $isEditing ? 'Edit Paket' : 'Tambah Paket Menu' }}</h3>
                <button class="modal-close" wire:click="$set('showModal', false)">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Paket *</label>
                    <input type="text" wire:model="name" class="form-control">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea wire:model="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Paket (Rp) *</label>
                    <input type="number" wire:model="price" class="form-control" min="0" step="500">
                    @error('price') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Pilih Menu dalam Paket * (bisa lebih dari satu)</label>
                    @error('selectedMenus') <div class="form-error">{{ $message }}</div> @enderror
                    <div style="max-height:200px; overflow-y:auto; border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:12px; display:flex; flex-direction:column; gap:8px; background:#FAFCFC;">
                        @foreach($allMenus as $menu)
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:0.9rem;">
                            <input type="checkbox" wire:model="selectedMenus" value="{{ $menu->id }}" style="width:16px; height:16px;">
                            <span><strong>{{ $menu->name }}</strong> <span style="color:var(--text-muted); font-size:0.8rem;">— Rp {{ number_format($menu->price, 0, ',', '.') }}</span></span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$set('showModal', false)" class="action-btn" style="background:#EDF2F2;">Batal</button>
                <button wire:click="save" class="btn-primary" style="padding:10px 24px;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </div>
    </div>
    @endif

    @if($showDeleteModal)
    <div class="modal-backdrop" wire:click.self="closeDeleteModal">
        <div class="modal-content" style="max-width:440px; text-align:center; padding:30px 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px auto;">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 8px;">Hapus Paket</div>
            <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 24px; padding: 0 10px; line-height: 1.4;">Paket "{{ $deleteName }}" akan dihapus secara permanen dari sistem.</div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button wire:click="closeDeleteModal" style="flex: 1; padding: 10px 0; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Batal</button>
                <button wire:click="delete" style="flex: 1; padding: 10px 0; border-radius: 8px; border: none; background: #ef4444; color: #fff; font-weight: 600; cursor: pointer; font-size: 0.88rem;">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
