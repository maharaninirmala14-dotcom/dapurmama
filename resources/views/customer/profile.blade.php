@extends('layouts.customer')

@section('content')
<div style="width: 100%; overflow-x: hidden !important; box-sizing: border-box;">
    <div style="padding: 30px 24px 60px 24px; max-width: 1280px; margin: 0 auto; box-sizing: border-box;">
        
        <!-- HEADER HALAMAN -->
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 1.7rem; font-weight: 800; color: #1e2a2d; margin-bottom: 4px;">Profil Saya</h1>
            <p style="color: #64748b; font-size: 0.9rem;">Kelola informasi akun dan alamat pengiriman Anda</p>
        </div>

        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 0.88rem; font-weight: 600;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('customer.profile.update') }}" method="POST" style="height: 100%;">
            @csrf

            <!-- LAYOUT UTAMA DUA KOLOM (Diberi align-items: stretch agar tinggi kedua kolom sama persis) -->
            <div style="display: grid !important; grid-template-columns: 1fr 420px !important; gap: 24px !important; align-items: stretch !important;">
                
                <!-- KOLOM KIRI: INFORMASI AKUN & FORM EDIT -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                    
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <i class="fa-solid fa-user-pen" style="color: #1e5d5c; font-size: 1.2rem;"></i>
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e2a2d; margin: 0;">Informasi Akun</h3>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <div>
                                <label for="name" style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Nama Lengkap</label>
                                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', Auth::user()->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; outline: none;">
                                @error('name') <div style="color: #dc2626; font-size: 0.78rem; margin-top: 4px;">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="email" style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Email</label>
                                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; outline: none;">
                                @error('email') <div style="color: #dc2626; font-size: 0.78rem; margin-top: 4px;">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="phone" style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Nomor Telepon / WhatsApp</label>
                                <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="Contoh: 081234567890" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; outline: none;">
                                @error('phone') <div style="color: #dc2626; font-size: 0.78rem; margin-top: 4px;">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="address" style="display: block; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Alamat Pengiriman Utama</label>
                                <textarea id="address" name="address" class="form-control" rows="4" placeholder="Masukkan alamat lengkap pengiriman catering" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; outline: none; resize: vertical;">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                @error('address') <div style="color: #dc2626; font-size: 0.78rem; margin-top: 4px;">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 24px; display: flex; gap: 12px;">
                        <button type="submit" style="background: #1e5d5c; color: #ffffff; border: none; padding: 11px 20px; border-radius: 10px; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>

                <!-- KOLOM KANAN: INFORMASI KONTAK & RINGKASAN AKTIVITAS -->
                <div style="display: flex; flex-direction: column; gap: 20px; justify-content: space-between;">
                    
                    <!-- Kotak Informasi Kontak -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                            <i class="fa-solid fa-address-book" style="color: #1e5d5c; font-size: 1.1rem;"></i>
                            <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e2a2d; margin: 0;">Informasi Kontak</h3>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.85rem; color: #475569;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b;">Email:</span>
                                <strong style="color: #1e2a2d;">{{ Auth::user()->email }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b;">No. Telepon:</span>
                                <strong style="color: #1e2a2d;">{{ Auth::user()->phone ?? 'Belum diisi' }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #64748b;">Bergabung Sejak:</span>
                                <strong style="color: #1e2a2d;">{{ Auth::user()->created_at->format('d M Y') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Ringkasan Aktivitas Pesanan -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                <i class="fa-solid fa-chart-line" style="color: #1e5d5c; font-size: 1.1rem;"></i>
                                <h3 style="font-size: 1.05rem; font-weight: 700; color: #1e2a2d; margin: 0;">Ringkasan Aktivitas</h3>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; text-align: center;">
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-bottom: 4px;">Total Pesanan</div>
                                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e2a2d;">{{ $totalOrders ?? 0 }}</div>
                                </div>
                                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px;">
                                    <div style="font-size: 0.75rem; color: #166534; font-weight: 600; margin-bottom: 4px;">Selesai</div>
                                    <div style="font-size: 1.25rem; font-weight: 800; color: #166534;">{{ $completedOrders ?? 0 }}</div>
                                </div>
                                <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 12px; padding: 14px;">
                                    <div style="font-size: 0.75rem; color: #0369a1; font-weight: 600; margin-bottom: 4px;">Diproses</div>
                                    <div style="font-size: 1.25rem; font-weight: 800; color: #0369a1;">{{ $processingOrders ?? 0 }}</div>
                                </div>
                                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px;">
                                    <div style="font-size: 0.75rem; color: #b91c1c; font-weight: 600; margin-bottom: 4px;">Dibatalkan</div>
                                    <div style="font-size: 1.25rem; font-weight: 800; color: #b91c1c;">{{ $cancelledOrders ?? 0 }}</div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <a href="{{ route('customer.orders') }}" style="display: block; text-align: center; background: #f1f5f9; color: #1e5d5c; border: 1px solid #cbd5e1; padding: 10px; border-radius: 10px; font-weight: 700; font-size: 0.82rem; text-decoration: none;">
                                <i class="fa-solid fa-receipt"></i> Kelola & Lihat Semua Pesanan
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </form>
    </div>
</div>
@endsection