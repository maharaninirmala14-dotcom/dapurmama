<div class="dashboard-page" style="padding: 10px;">
    
    <!-- Panel Utama -->
    <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <!-- Header & Breadcrumb -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">Ulasan Pelanggan</h3>
                <p style="color: #64748b; font-size: 0.88rem; margin: 0;">Kelola ulasan dan tanggapan dari pelanggan</p>
            </div>
            <div style="font-size: 0.82rem; color: #64748b; font-weight: 500;">
                Dashboard &gt; <span style="color: #0f172a; font-weight: 600;">Ulasan Pelanggan</span>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session()->has('success'))
            <div style="background: #ccfbf1; color: #0f766e; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
            <div style="position: relative; flex: 1; min-width: 240px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" wire:model.debounce.300ms="search" placeholder="Cari nama pelanggan / pesanan..." style="width: 100%; padding: 10px 14px 10px 38px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box;">
            </div>
            <div>
                <select wire:model="statusFilter" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; outline: none; background: #fff; color: #334155; cursor: pointer;">
                    <option value="">Semua Status</option>
                    <option value="belum">Belum Dibalas</option>
                    <option value="sudah">Sudah Dibalas</option>
                </select>
            </div>
        </div>

        <!-- Tabel Ulasan Pelanggan -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.88rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em;">
                        <th style="padding: 12px 10px; width: 50px; vertical-align: middle;">No.</th>
                        <th style="padding: 12px 10px; width: 180px; vertical-align: middle;">Customer</th>
                        <th style="padding: 12px 10px; width: 160px; vertical-align: middle;">Pesanan</th>
                        <th style="padding: 12px 10px; width: 130px; vertical-align: middle;">Rating</th>
                        <th style="padding: 12px 10px; vertical-align: middle;">Ulasan</th>
                        <th style="padding: 12px 10px; width: 110px; vertical-align: middle;">Tanggal</th>
                        <th style="padding: 12px 10px; width: 130px; vertical-align: middle;">Status</th>
                        <th style="padding: 12px 10px; width: 120px; text-align: center; vertical-align: middle;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $index => $review)
                    @php
                        $name = optional($review->user)->name ?? 'Pelanggan';
                        $words = explode(' ', trim($name));
                        $initials = strtoupper(count($words) > 1 ? substr($words[0],0,1) . substr($words[1],0,1) : substr($name, 0, 2));
                        $isReplied = !empty($review->admin_reply);
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9; color: #334155;">
                        <!-- No -->
                        <td style="padding: 14px 10px; font-weight: 500; color: #64748b; vertical-align: middle;">
                            {{ $reviews->firstItem() + $index }}
                        </td>
                        
                        <!-- Customer -->
                        <td style="padding: 14px 10px; vertical-align: middle;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #334155; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    {{ $initials }}
                                </div>
                                <span style="font-weight: 700; color: #0f172a; white-space: nowrap;">{{ $name }}</span>
                            </div>
                        </td>

                        <!-- Pesanan (Ditambahkan white-space: nowrap agar tidak terpotong ke bawah) -->
                        <td style="padding: 14px 10px; font-weight: 600; color: #0f766e; white-space: nowrap; vertical-align: middle;">
                            {{ optional($review->order)->order_code ?? '-' }}
                        </td>

                        <!-- Rating Bintang -->
                        <td style="padding: 14px 10px; color: #f59e0b; white-space: nowrap; vertical-align: middle;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star" style="font-size: 0.85rem;"></i>
                            @endfor
                        </td>

                        <!-- Ulasan -->
                        <td style="padding: 14px 10px; color: #475569; line-height: 1.4; vertical-align: middle;">
                            {{ $review->comment }}
                        </td>

                        <!-- Tanggal -->
                        <td style="padding: 14px 10px; color: #64748b; font-size: 0.82rem; white-space: nowrap; vertical-align: middle;">
                            {{ $review->created_at->format('d M Y') }}<br>
                            <span style="font-size: 0.75rem; color: #94a3b8;">{{ $review->created_at->format('H:i') }}</span>
                        </td>

                        <!-- Status Badge -->
                        <td style="padding: 14px 10px; white-space: nowrap; vertical-align: middle;">
                            @if($isReplied)
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                    Sudah Dibalas
                                </span>
                            @else
                                <span style="background: #fff7ed; color: #c2410c; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                    Belum Dibalas
                                </span>
                            @endif
                        </td>

                        <!-- Aksi Tombol -->
                        <td style="padding: 14px 10px; text-align: center; white-space: nowrap; vertical-align: middle;">
                            @if($isReplied)
                                <button wire:click="openReplyModal({{ $review->id }})" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 6px 14px; border-radius: 6px; font-size: 0.78rem; cursor: pointer; font-weight: 600;">
                                    Sudah Dibalas
                                </button>
                            @else
                                <button wire:click="openReplyModal({{ $review->id }})" style="background: #0f766e; color: #fff; border: none; padding: 6px 14px; border-radius: 6px; font-size: 0.78rem; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-reply"></i> Balas
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fa-regular fa-comment-dots" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                            Belum ada ulasan dari pelanggan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info & Pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 15px; font-size: 0.85rem; color: #64748b;">
            <div>
                Menampilkan {{ $reviews->firstItem() ?? 0 }} - {{ $reviews->lastItem() ?? 0 }} dari {{ $reviews->total() }} ulasan
            </div>
            <div>
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    <!-- ================================================= -->
    <!-- MODAL POPUP BALAS ULASAN -->
    <!-- ================================================= -->
    @if($replyModal)
    @php
        $selectedReview = \App\Models\Review::with(['user', 'order'])->find($reviewId);
        $selName = optional($selectedReview->user)->name ?? 'Pelanggan';
        $selWords = explode(' ', trim($selName));
        $selInitials = strtoupper(count($selWords) > 1 ? substr($selWords[0],0,1) . substr($selWords[1],0,1) : substr($name, 0, 2));
    @endphp

    <div style="position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 1050; padding: 20px;">
        <div style="background: #ffffff; border-radius: 12px; width: 100%; max-width: 520px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow: hidden;">
            
            <!-- Modal Header -->
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0;">Balas Ulasan</h3>
                <button wire:click="$set('replyModal', false)" style="background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b;">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="padding: 20px; max-height: 75vh; overflow-y: auto;">
                
                <!-- Info Customer & Rating -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #334155; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ $selInitials }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem; line-height: 1.2;">{{ $selName }}</div>
                        <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                            {{ optional($selectedReview)->created_at ? $selectedReview->created_at->translatedFormat('d F Y') : '' }} &bull; {{ optional($selectedReview->order)->order_code ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- Bintang Rating -->
                <div style="color: #f59e0b; margin-bottom: 14px; font-size: 0.9rem;">
                    @if($selectedReview)
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= $selectedReview->rating ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                    @endif
                </div>

                <!-- Kotak Komentar Pelanggan -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.88rem; color: #334155; line-height: 1.5;">
                    "{{ optional($selectedReview)->comment }}"
                </div>

                <!-- Form Tanggapan Admin -->
                <div style="margin-bottom: 6px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Tanggapan Admin</label>
                    <textarea wire:model="adminReply" rows="4" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; outline: none; box-sizing: border-box; color: #334155; line-height: 1.4;" placeholder="Tulis tanggapan atau balasan..."></textarea>
                    @error('adminReply') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                
                <!-- Penghitung Karakter -->
                <div style="text-align: right; font-size: 0.72rem; color: #94a3b8;">
                    {{ mb_strlen($adminReply) }}/500
                </div>

            </div>

            <!-- Modal Footer -->
            <div style="padding: 14px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; background: #ffffff;">
                <button wire:click="$set('replyModal', false)" style="background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; padding: 8px 18px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Batal</button>
                <button wire:click="saveReply" style="background: #0f766e; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Kirim Balasan</button>
            </div>

        </div>
    </div>
    @endif

</div>