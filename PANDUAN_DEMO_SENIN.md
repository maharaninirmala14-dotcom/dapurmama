# 📋 PANDUAN DEMO SENIN (Anti-Gugup)

> Dipakai persis untuk presentasi ke Pak Lilik. Baca sekali pelan, lalu latihan 2×.
> Semua angka di bawah sudah **terverifikasi nyata** dari sistem (bukan teori kosong).

---

## A. 3 Alur yang Akan Kamu Tunjukkan (urutan rekomendasi)

| # | Alur | Membuktikan ke Pak Lilik | Waktu |
|---|------|--------------------------|-------|
| 1 | **Personalisasi 2 pelanggan** (admin → bukti, lalu bandingkan 2 akun pelanggan) | Rekomendasi **dihitung dari riwayat masing-masing**, bukan hardcode | ±2 mnt |
| 2 | **Loop end-to-end** (checkout → transaksi masuk → rekomendasi berubah) | Alur sistem bekerja utuh, Apriori memakai data terbaru | ±3 mnt |
| 3 | **Halaman Admin Apriori** (parameter, itemset, aturan, rumus) | Paham teori + parameter sesuai skripsi (20% / 50%) | ±2 mnt |

> **Nasib baiknya:** Alur 1 dan 3 ada di **satu halaman admin** (menu "Apriori"),
> jadi gampang dipindah-pindah.

---

## B. Skrip Alur 1 — Bukti Personalisasi (paling penting, jalanin paling dulu)

**Langkah:**
1. Login admin: `admindelisha@gmail.com` / `admindelisha_04`
2. Sidebar kiri → **Apriori**.
3. Scroll ke **bagian bawah** → "Bukti Kerja Algoritma Apriori".
4. Pastikan dropdown: **Pelanggan 1 = Nirmala Maharani**, **Pelanggan 2 = Nirmanto**.

**Kalimat sampaikan (boleh dibaca):**
> "Di sini saya bandingkan dua pelanggan: Nirmala dan Nirmanto.
> Tiap kolom dihitung hanya dari riwayat transaksi pelanggan tersebut.
> - Nirmala punya **17 transaksi**, Nirmanto **12 transaksi**.
> - Kolom 1 'Data Transaksi': kode order dan menu keduanya berbeda.
> - Kolom 2 'Frequent Itemset': pola menu yang lolos ambang **20%** — beda.
> - Kolom 3 'Aturan Asosiasi': rumusnya ditulis konkret, mis. `Support = 4/17 transaksi = 23,5%`.
> - Kolom 4 'Rekomendasi': paket yang muncul **beda**.
> - Bagian 'Kesimpulan Perbandingan': Riwayat = `Beda`, Rekomendasi = `Beda`.
>
> Ini bukti hasil Apriori tidak sama untuk semua orang — setiap pelanggan dianalisis dari datanya sendiri."

**Chip yang ditunjuk:** "Dianalisis dari riwayatnya sendiri" (hijau).

**Kalau diminta bukti tambahan:** drag **Pelanggan 2** ganti jadi **Dewi** / **Fajar** →
hasilnya berubah → "Lihat, begitu pelanggannya diganti, angka ikut berubah."

---

## C. Skrip Alur 2 — Loop End-to-End (hidup-hidupannya)

**Langkah:**
1. **Logout admin**, login sebagai pelanggan:
   - Nirmala: `nirmalamaharani101@gmail.com` / `password123`
2. Buka halaman **"Rekomendasi Paket Menu"** (sidebar pelanggan).
3. Tunjuk **kartu paket Rp35.500** = *Nasi Ayam Goreng Gandeng + Dadar Gulung + Es Jeruk*,
   saat ini dengan support **23,5% (4 dari 17 transaksi)** → ini paket yang **baru muncul**
   setelah data demo ditambahkan (bukti Apriori peka terhadap data baru).
4. **Checkout paket yang sama itu**: klik kartu → masuk keranjang (min. 20 pax otomatis) →
   halaman checkout → selesaikan pesanan.
5. Keterangan: *"Order ini tersimpan dengan status Menunggu dan langsung ikut analisis."*
6. **Refresh** halaman "Rekomendasi Paket Menu" → tunjukkan perubahannya:
   - Jumlah transaksi dianalisis **17 → 18**.
   - Support paket itu **23,5% → 27,8%** (menjadi 5 dari 18 transaksi).
   → *"Datanya berubah → semua angka dihitung ulang. Tidak ada yang saya tulis manual."*
7. **Logout**, login **Nirmanto** (`nirmanto@customer.com` / `password`) →
   tunjukkan bahwa paket Nirmanto **berbeda** dengan Nirmala.
8. (Kembali ke Nirmala bila sempat) → "Rekomendasi ikut berubah karena datanya berubah."

> ⚠️ **Pentunjuk jujur:** dengan 1 kali checkout, kartu yang benar-benar *baru* tidak muncul
> (semua pola yang ada sudah di atas ambang 20%). Yang berubah adalah **angkanya** (17→18 tx,
> support naik) — dan itulah bukti yang benar: sistem **menghitung ulang dari transaksi terbaru**.
> Jangan berjanji "kartu baru muncul"; tunjukkan perubahan angka itu.

**Kalimat penutup alur:**
> "Jadi siklusnya lengkap: belanja → transaksi masuk database → Apriori dihitung ulang → rekomendasi menyesuaikan. Tidak ada rekomendasi yang saya tulis manual."

> **Trik hemat waktu:** kalau demo telat waktunya, cukup tunjukkan order `ORD-FA7TXYWT`
> (transaksi checkout asli yang sudah masuk) dan katakan "ini hasil checkout tadi — langsung
> mengubah angka analisis".

---

## D. Skrip Alur 3 — Halaman Admin Apriori (teori)

**Kembali ke login admin**, menu **Apriori** → bagian atas halaman.

Kartu statistik → itemset → aturan, dengan kalimat:

**1. Kartu statistik**
> "Total transaksi yang dianalisis, ambang Minimum Support 20%, Minimum Confidence 50%,
> jumlah pola, dan jumlah aturan."

**2. Frequent Itemset**
> "Pola menu yang muncul minimal di 20% transaksi. Mula-mula kombinasi 1 menu, lalu 2, 3, sampai 4 menu.
> Rumusnya: **jumlah transaksi berisi pola tsb ÷ total transaksi**.
> Kombinasi yang tidak mencapai 20% langsung tidak dipakai."

**3. Aturan Asosiasi**
> "Formatnya 'Jika beli A, maka beli B'. Tiap aturan punya 3 metrik:
> - **Support** = seberapa sering pola ini muncul.
> - **Confidence** = dari pembeli A, berapa % yang juga membeli B.
> - **Lift** = kekuatan korelasi dibanding kebetulan: **>1 kuat, =1 netral, <1 lemah**.
>
> Aturan terbaik (lift tertinggi) yang menjadi rekomendasi pelanggan."

**4. Form parameter**
> "Nilai ini sesuai ketentuan skripsi: 20% dan 50%. Kalau diubah, tersimpan di tabel settings
> dan seluruh analisis langsung dihitung ulang — termasuk yang dilihat pelanggan."

---

## E. Kalau Pak Lilik Nanya (Q&A cepat)

| Pertanyaan | Jawaban baku |
|---|---|
| "Rekomendasinya kok beda-beda antar pelanggan?" | "Karena tiap pelanggan dihitung dari riwayat transaksinya sendiri — buktinya di bagian 'Bukti' tadi, angkanya beda dan ikut berubah saat pelanggan diganti." |
| "Rumus support/confidence/lift?" | "Support = banyak transaksi berisi pola ÷ total; Confidence = Support(A∪B) ÷ Support(A); Lift = Support(A∪B) ÷ (Support(A)×Support(B))." |
| "Kenapa kombinasi berhenti di 4 menu?" | "Ambang 20% membuat kombinasi lebih besar jarang terpenuhi; batas ini bagian dari penerapan Apriori (maksimal k=4)." |
| "Data yang mana yang dihitung?" | "Order berstatus Selesai, Diproses, dan Menunggu. Order batal tidak dihitung." |
| "Bagaimana bukti angkanya benar?" | "Di bagian bukti, rumus tertulis lengkap, mis. `Support = 4/17 transaksi = 23,5%`, dan kode ordernya bisa dicek di halaman transaksi." |
| "Apakah ini hardcode?" | "Tidak. Kalau datanya berubah (checkout baru), angkanya berubah. Baru saja dicoba: setelah checkout, rekomendasi berubah." |
| "Parameter bisa diubah?" | "Bisa lewat form ini, tapi untuk skripsi nilainya memang 20% dan 50%." |

---

## F. Yang TIDAK Boleh Kamu Lakukan Saat Demo

- ❌ Jangan ubah parameter (biarkan 0.2 / 0.5) — cukup sebut "nilai skripsi".
- ❌ Jangan buka-buka halaman yang tidak perlu (hindari menu "Rekomendasi Menu Pilihan"
  yang bukan Apriori — kalau kejadian, langsung bilang "ini menu pilihan manual, bukan Apriori;
  yang Apriori ada di Rekomendasi Paket Menu").
- ❌ Jangan bilang "lupa" — pakai kalimat: "sebentar saya tunjukkan dari sisi yang lebih jelas".
- ❌ Jangan mengedit kode/setting apa pun saat layar dibagikan.

---

## G. Fakta Cadangan (sudah diverifikasi — hafalkan minimal 3)

- Nirmala: **17 transaksi**, 6 kartu paket, paket baru **Rp35.500** (support 23,5%).
- Nirmanto / Dewi / Fajar: **12 transaksi** masing-masing.
- Semua pasangan demo **tidak punya menu rekomendasi yang sama** satu sama lain.
- Loop checkout → transaksi → rekomendasi berubah: **teruji otomatis + teruji manual**.
- `php artisan test` → **12/12 PASS**.
- Admin page "Apriori" = satu menu berisi parameter + analisis global + bukti personalisasi.
- URL cadangan: `/admin/apriori-proof` (halaman bukti lama, tetap jalan).

---

## H. Latihan 10 Menit Sebelum Demo

1. Bacakan skrip **Alur 1** sekali dengan suara pelan (2 mnt).
2. Buka browser, **coba jalankan sendiri alur 1 & 2 tanpa melihat skrip** (5 mnt).
3. Tutup matamu, jawab **3 pertanyaan** dari bagian E (3 mnt).
4. Tarik napas. Kamu siap. 💪

---

## Backup Ekstrem (kalau panik total di depan Pak Lilik)

Hanya 2 layar saja yang selalu menyelamatkanmu:
1. **Halaman admin "Apriori" bagian Bukti** → "Ini bukti dua pelanggan dihitung beda."
2. **Halaman "Rekomendasi Paket Menu" Nirmala** → "Ini hasilnya untuk satu pelanggan."

Kalau itu sudah ditunjukkan, inti demo selesai.