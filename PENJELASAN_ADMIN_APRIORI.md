# Fungsi Halaman Admin "Apriori" (Satu Halaman: Parameter + Analisis + Bukti) + Letak Parameter
**Lembar penjelasan ringkas — persiapan presentasi dengan Pembimbing (Senin)**

---

## 1. Apa itu halaman Admin → "Apriori"?

- Akses: **Login admin → Sidebar "Apriori"** (ikon otak) → URL `/admin/apriori`.
- Fungsinya: **ruang kendali teknis mesin Apriori** — bukan tampilan pelanggan. **Semuanya ada DI SATU halaman/menu**, terbagi 2 bagian:
  1. **Bagian Atas — Parameter & Analisis Global**: statistik, form parameter, itemset, aturan asosiasi.
  2. **Bagian Bawah — Bukti Personalisasi**: membandingkan perhitungan **2 pelanggan berdampingan** (bukti Apriori benar-benar belajar dari riwayat tiap akun).
- Isi bagian atas:
  - Statistik: **Min. Support**, **Min. Confidence**, jumlah **itemset**, jumlah **aturan asosiasi**.
  - Form parameter: **Minimum Support** & **Minimum Confidence** + tombol **"Jalankan Analisis Apriori"**.
  - Daftar **Frequent Itemset** 1 hingga 4 menu lengkap dengan persentase **Support**.
  - Daftar **Aturan Asosiasi** ("Jika X maka Y") lengkap dengan **Support, Confidence, Lift**.
- Isi bagian bawah (Bukti Personalisasi):
  - Pilih **Pelanggan 1** & **Pelanggan 2** (default: Nirmala vs Nirmanto).
  - Perbandingan **support, rules, rekomendasi** dua pelanggan → membuktikan hasil **berbeda sesuai riwayat masing-masing**.

### Beda dengan halaman pelanggan

| | Halaman Admin "Apriori" | Halaman Pelanggan "Rekomendasi Paket Menu" |
|---|---|---|
| Data yang dianalisis | **Semua** transaksi (global) | **Riwayat akun yang login** saja |
| Isi tampilan | Itemset + rules + form parameter + bukti 2 pelanggan | Kartu paket + aturan (hasil akhir yang bersih) |
| Guna | Atur parameter, verifikasi teknis | Rekomendasi untuk pelanggan |

> **Catatan:** halaman "Bukti Apriori" (`/admin/apriori-proof`) **sudah digabung** menjadi bagian bawah halaman "Apriori" — jadi sidebar admin kini hanya punya **satu menu "Apriori"** (tidak ada lagi menu kedua). Rumus, itemset, dan perhitungannya **tidak berubah**.

---

## 2. Di mana parameter ditentukan? (Min Support 20% / Confidence 50%)

**Langkah mengubahnya (runtime):**
1. Login admin → menu sidebar **Apriori**.
2. Isi **Minimum Support = `0.2`** (20%), **Minimum Confidence = `0.5`** (50%).
3. Klik **"Jalankan Analisis Apriori"** → tersimpan & dianalisis ulang seketika.

**Rantai kode (tempat tersimpannya):**

| Lokasi | Peran |
|---|---|
| `database/seeders/DatabaseSeeder.php` baris 378–380 | Nilai awal/kerjaan skripsi: `min_support = 0.2`, `min_confidence = 0.5` |
| Tabel **`settings`** (key `min_support`, `min_confidence`) | Tempat penyimpanan tunggal; `app/Models/Setting.php` baca langsung dari DB (tanpa cache) |
| `app/Http/Livewire/Admin/Apriori.php` baris 13–14, 49–50 | Form admin membaca & menulis ulang ke `settings` (bagian bawah halaman: bukti 2 pelanggan, dihitung `AprioriService`) |
| `app/Services/AprioriService.php` baris 20–25 | Mesin membaca parameter saat dijalankan |
| `AprioriService.php` baris 503, 549, 602 | Ambang dipakai: `support >= minSupport`, `confidence >= minConfidence` (rules) |

**Alur singkat:**
`DatabaseSeeder → tabel settings → AprioriService (constructor) → filter itemset & rules → tampil di admin + pelanggan`

> Karena baca langsung dari DB tanpa cache, **semua perubahan berlaku seketika** — ke halaman admin *dan* halaman pelanggan (chip "Min Support 20% / Min Confidence 50%" ikut).

---

## 3. Kalimat siap presentasi

> "Halaman **Apriori** di admin adalah ruang kendali mesinnya — **satu menu** berisi dua bagian: bagian atas menampilkan itemset dan aturan asosiasi dari semua transaksi sekaligus form parameternya; bagian bawah menampilkan bukti personalisasi, membandingkan perhitungan dua pelanggan (misalnya Nirmala vs Nirmanto) yang hasilnya berbeda sesuai riwayat transaksi masing-masing. Parameternya disimpan di tabel settings dan dibaca langsung oleh mesin Apriori. Sekarang nilainya **Support 20% dan Confidence 50%** sesuai ketentuan skripsi. Kalau diubah, seluruh analisis langsung dihitung ulang — termasuk rekomendasi yang dilihat pelanggan."

---

## 4. Catatan demo

- **Jangan ubah parameter saat presentasi** — biarkan 0.2 / 0.5 sesuai skripsi.
- **Tampilan admin sudah "satu menu"** — sidebar cuma punya "Apriori"; bukti 2 pelanggan ada di bagian bawah halaman yang sama. Saat demo cukup scroll bawah untuk menunjukkan bukti personalisasi.
- Bukti konsistensi: stat card admin menampilkan **20%** dan **50%**; chip halaman pelanggan juga menampilkan **Min Support 20% / Min Confidence 50%**.
- (Opsional, hanya jika diminta) demonstrasi efek parameter: turunkan support → kombinasi lebih banyak; **jangan lupa kembalikan ke 0.2/0.5**.
- Status pengujian: `php artisan test` → **12/12 PASS**; checkout pelanggan sudah terbukti bekerja.