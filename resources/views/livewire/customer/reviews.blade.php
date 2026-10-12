@extends('layouts.customer') {{-- Sesuaikan dengan nama layout utama Anda jika berbeda --}}

@section('content')
<style>
    @media (max-width: 768px) {
        .reviews-stats-box {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 16px !important;
        }
        .reviews-stats-box > div {
            padding: 0 !important;
            border-right: none !important;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 14px !important;
        }
        .reviews-stats-box > div:last-child {
            border-bottom: none;
            padding-bottom: 0 !important;
        }
        .review-card {
            flex-direction: column !important;
        }
        .review-card-top-actions {
            flex-direction: row !important;
            justify-content: space-between !important;
            width: 100%;
        }
    }
</style>
<div style="max-width: 1200px; margin: 0 auto;">
    
    {{-- Header Judul Halaman --}}
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e2a2d; margin: 0 0 4px 0;">
            <i class="fa-regular fa-star" style="color: #134e4a; margin-right: 8px;"></i> Ulasan Saya
        </h1>
        <p style="font-size: 0.9rem; color: #64748b; margin: 0;">
            Lihat ulasan yang sudah Anda berikan untuk setiap pesanan.
        </p>
    </div>

    {{-- Kotak Statistik Ulasan & Rating --}}
    <div class="reviews-stats-box" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02); margin-bottom: 24px;">
        
        {{-- Total Ulasan --}}
        <div style="text-align: center; padding-right: 40px; border-right: 1px solid #f1f5f9; flex: 1;">
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">Total Ulasan</span>
            <div style="font-size: 1.8rem; font-weight: 800; color: #1e2a2d; margin: 4px 0;">5</div>
            <span style="font-size: 0.8rem; color: #64748b;">ulasan</span>
        </div>

        {{-- Rata-rata Rating --}}
        <div style="text-align: center; padding: 0 40px; border-right: 1px solid #f1f5f9; flex: 1;">
            <span style="font-size: 0.85rem; color: #64748b; font-weight: 600;">Rata-rata Rating</span>
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin: 4px 0;">
                <span style="font-size: 1.8rem; font-weight: 800; color: #1e2a2d;">4.6</span>
                <span style="font-size: 0.9rem; color: #94a3b8;">/ 5</span>
            </div>
            <div style="color: #f59e0b; font-size: 0.9rem;">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-stroke"></i>
            </div>
        </div>

        {{-- Bar Detail Distribusi Bintang --}}
        <div style="flex: 2; padding-left: 20px;">
            @foreach([['5', '3'], ['4', '2'], ['3', '0'], ['2', '0'], ['1', '0']] as $star)
            <div style="display: flex; align-items: center; gap: 10px; font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">
                <span style="width: 12px;">{{ $star[0] }}</span>
                <i class="fa-solid fa-star" style="color: #f59e0b; font-size: 10px;"></i>
                <div style="flex: 1; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                    <div style="width: {{ $star[1] > 0 ? ($star[1] / 3) * 100 : 0 }}%; height: 100%; background: #134e4a; border-radius: 4px;"></div>
                </div>
                <span style="width: 12px; text-align: right;">{{ $star[1] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Daftar Kartu Ulasan --}}
    <div style="display: flex; flex-direction: column; gap: 16px;">
        
        {{-- Contoh Item Kartu Ulasan 1 --}}
        <div class="review-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; display: flex; gap: 20px; align-items: flex-start; box-shadow: 0 1px 3px rgba(0,0,0,0.02); position: relative;">
            {{-- Foto Produk / Thumbnail --}}
            <div style="width: 90px; height: 90px; border-radius: 12px; overflow: hidden; background: #f8fafc; flex-shrink: 0; border: 1px solid #f1f5f9;">
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200" alt="Menu" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            {{-- Detail Ulasan --}}
            <div style="flex: 1;">
                <h3 style="margin: 0 0 2px 0; font-size: 1rem; font-weight: 700; color: #1e2a2d;">Paket Nasi Box Ayam</h3>
                <span style="font-size: 0.8rem; color: #64748b;">Pesanan #ORD-PRZVBFGB • 19 Agustus 2026</span>
                
                <div style="display: flex; align-items: center; gap: 6px; margin: 8px 0 6px 0;">
                    <div style="color: #f59e0b; font-size: 0.75rem;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <span style="background: #f0fdfa; color: #134e4a; font-size: 0.75rem; font-weight: 700; padding: 1px 6px; border-radius: 4px;">5.0</span>
                </div>

                <p style="margin: 0; font-size: 0.88px; color: #334155; line-height: 1.5;">
                    Makanannya enak banget, ayamnya gurih dan sambalnya mantap!
                </p>
            </div>

            {{-- Status Terverifikasi & Tombol Aksi Kanan Atas --}}
            <div class="review-card-top-actions" style="display: flex; align-items: center; gap: 12px;">
                <div style="text-align: right;">
                    <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                        <i class="fa-solid fa-circle-check" style="font-size: 10px;"></i> Terverifikasi
                    </span>
                    <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 4px;">19 Agustus 2026</div>
                </div>
                <button style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>
        </div>

        {{-- Anda bisa melalukan looping data controller untuk item ulasan selanjutnya di sini --}}

    </div>

    {{-- Pagination Sederhana --}}
    <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 30px;">
        <button style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #94a3b8; cursor: pointer;"><i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i></button>
        <button style="width: 32px; height: 32px; border-radius: 8px; border: none; background: #134e4a; color: #fff; font-weight: 600; font-size: 0.85rem; cursor: pointer;">1</button>
        <button style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; cursor: pointer;"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i></button>
    </div>

</div>
@endsection