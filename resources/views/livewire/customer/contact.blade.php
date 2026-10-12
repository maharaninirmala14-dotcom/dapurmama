@extends('layouts.customer')

@section('title', 'Pesan Catering - Dapur Mama Delisha')

@section('content')
<style>
    @media (max-width: 900px) {
        .contact-two-col {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 640px) {
        .contact-form-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
<div style="background: #f8fafc; min-height: 80vh; padding-bottom: 60px;">
    
    <!-- HEADER HERO SECTION DENGAN GAMBAR KOTAK NASI DI KANAN -->
    <div style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 40px 20px; margin-bottom: 40px;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 30px;">
            
            <!-- Teks Kiri -->
            <div style="flex: 1; min-width: 300px;">
                <h1 style="font-size: 2.2rem; font-weight: 800; color: #1e2a2d; margin-bottom: 12px; letter-spacing: -0.5px;">
                    Pesan Catering
                </h1>
                <p style="font-size: 1.05rem; font-weight: 600; color: #0f4c3a; margin-bottom: 8px;">
                    Hubungi kami untuk melakukan pemesanan catering
                </p>
                <p style="font-size: 0.95rem; color: #64748b; line-height: 1.5;">
                    Isi formulir di samping atau hubungi kami langsung melalui kontak yang tersedia.
                </p>
            </div>

            <!-- Gambar / Ilustrasi Makanan Kanan -->
            <div style="flex: 1; min-width: 300px; text-align: right;">
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80" alt="Catering Dapur Mama Delisha" style="width: 100%; max-width: 500px; height: 220px; object-fit: cover; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            </div>

        </div>
    </div>

    <!-- KONTEN UTAMA: 2 KOLOM (KONTAK & FORM) -->
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="contact-two-col" style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 30px; align-items: start;">
            
            <!-- KOLOM KIRI: INFORMASI KONTAK -->
            <div style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e2a2d; margin-bottom: 8px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
                    Informasi Kontak
                </h3>
                <p style="font-size: 0.88rem; color: #64748b; margin-bottom: 24px; line-height: 1.5;">
                    Anda dapat menghubungi kami melalui informasi di bawah ini untuk pemesanan atau konsultasi.
                </p>
                
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 20px; font-size: 0.9rem; color: #475569;">
                    <li style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 36px; height: 36px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <strong style="color: #1e2a2d; display: block; margin-bottom: 2px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Alamat</strong>
                            Jl. Mawar Indah Blok B/4, Tangerang, Banten 15132
                        </div>
                    </li>

                    <li style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 36px; height: 36px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <strong style="color: #1e2a2d; display: block; margin-bottom: 2px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Email</strong>
                            dapurmamadelisha@gmail.com
                        </div>
                    </li>

                    <li style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 36px; height: 36px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <strong style="color: #1e2a2d; display: block; margin-bottom: 2px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">No. Telepon / WhatsApp</strong>
                            0812 9988 7766
                        </div>
                    </li>

                    <li style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 36px; height: 36px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <strong style="color: #1e2a2d; display: block; margin-bottom: 2px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Jam Operasional</strong>
                            Senin - Sabtu : 08.00 - 17.00 WIB<br>
                            Minggu : 08.00 - 14.00 WIB
                        </div>
                    </li>
                </ul>

                <!-- Kotak Bantuan WhatsApp Cepat -->
                <div style="margin-top: 30px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 12px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 15px;">
                    <div>
                        <strong style="color: #166534; font-size: 0.9rem; display: block; margin-bottom: 3px;">
                            <i class="fa-brands fa-whatsapp" style="color: #22c55e;"></i> Lebih cepat via WhatsApp!
                        </strong>
                        <span style="font-size: 0.8rem; color: #15803d;">Klik tombol di samping untuk terhubung langsung dengan kami.</span>
                    </div>
                    <a href="https://wa.me/6281299887766?text=Halo%20Dapur%20Mama%20Delisha,%20saya%20ingin%20berkonsultasi%20pesanan%20catering." target="_blank" style="background: #0f4c3a; color: #ffffff; padding: 10px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; text-decoration: none; white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        Chat WhatsApp
                    </a>
                </div>
            </div>

            <!-- KOLOM KANAN: FORM KIRIM PESAN -->
            <div style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e2a2d; margin-bottom: 8px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
                    Kirim Pesan
                </h3>
                <p style="font-size: 0.88rem; color: #64748b; margin-bottom: 24px;">
                    Isi formulir di bawah ini, tim kami akan segera merespon permintaan Anda.
                </p>

                <form action="#" method="POST">
                    @csrf
                    
                    <div class="contact-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e2a2d; margin-bottom: 6px;">
                                <i class="fa-solid fa-user" style="color: #0f4c3a;"></i> Nama Lengkap
                            </label>
                            <input type="text" name="name" placeholder="Contoh: Ani Wijaya" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e2a2d; margin-bottom: 6px;">
                                <i class="fa-solid fa-phone" style="color: #0f4c3a;"></i> No. Telepon / WhatsApp
                            </label>
                            <input type="text" name="phone" placeholder="Contoh: 081299887766" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;" required>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e2a2d; margin-bottom: 6px;">
                            <i class="fa-solid fa-envelope" style="color: #0f4c3a;"></i> Email
                        </label>
                        <input type="email" name="email" placeholder="Contoh: email@gmail.com" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;" required>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e2a2d; margin-bottom: 6px;">
                            <i class="fa-solid fa-utensils" style="color: #0f4c3a;"></i> Jenis Pesanan
                        </label>
                        <select name="category" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; background: #fff; box-sizing: border-box;" required>
                            <option value="">Pilih jenis catering</option>
                            <option value="Nasi Box">Nasi Box</option>
                            <option value="Catering Harian">Catering Harian</option>
                            <option value="Prasmanan Acara">Prasmanan Acara</option>
                            <option value="Snack Box">Snack Box</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e2a2d; margin-bottom: 6px;">
                            <i class="fa-solid fa-calendar-days" style="color: #0f4c3a;"></i> Tanggal Acara
                        </label>
                        <input type="date" name="event_date" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; background: #fff; box-sizing: border-box;" required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e2a2d; margin-bottom: 6px;">
                            <i class="fa-solid fa-comment-dots" style="color: #0f4c3a;"></i> Pesan / Kebutuhan
                        </label>
                        <textarea name="message" rows="4" placeholder="Tuliskan detail kebutuhan Anda (jumlah porsi, menu yang diinginkan, catatan khusus, dll.)" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box; resize: vertical;" required></textarea>
                    </div>

                    <button type="submit" style="width: 100%; background: #0f4c3a; color: #ffffff; border: none; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 6px rgba(15,76,58,0.2);">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pesan
                    </button>
                    
                    <p style="text-align: center; font-size: 0.78rem; color: #94a3b8; margin-top: 12px;">
                        <i class="fa-solid fa-lock"></i> Data Anda aman dan hanya digunakan untuk keperluan pemesanan.
                    </p>
                </form>
            </div>

        </div>

        <!-- FITUR KEUNGGULAN DI BAWAH (PERSIS REFERENSI) -->
        <div style="margin-top: 50px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; text-align: center;">
                
                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div style="width: 50px; height: 50px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin: 0;">Bahan Berkualitas</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">Kami menggunakan bahan segar dan berkualitas setiap hari.</p>
                </div>

                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div style="width: 50px; height: 50px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin: 0;">Higienis & Bersih</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">Proses pengolahan makanan higienis dan terjaga kebersihannya.</p>
                </div>

                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div style="width: 50px; height: 50px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin: 0;">Rasa Terjamin</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">Resep khas rumahan dengan cita rasa lezat dan konsisten.</p>
                </div>

                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div style="width: 50px; height: 50px; background: #e6f4f1; color: #0f4c3a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e2a2d; margin: 0;">Pengiriman Tepat Waktu</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">Pesanan diantar sesuai jadwal acara Anda.</p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection