# Dapur Mama Delisha — Sistem Catering dengan Rekomendasi Menu Apriori

Aplikasi web **catering** berbasis **Laravel 12 + Livewire 3 + TailwindCSS (Vite)** untuk **Dapur Mama Delisha**.
Selain fungsi e-commerce catering biasa (katalog, paket, keranjang, checkout, pesanan), aplikasi ini
mengimplementasikan **Data Mining algoritma Apriori (Market Basket Analysis)** untuk memberikan
**rekomendasi paket menu yang dipersonalisasi per pelanggan**, serta sebuah **SPK (Sistem Pendukung
Keputusan) perencana menu berdasarkan budget**.

> Proyek ini merupakan implementasi skripsi dengan studi kasus UMKM catering. Parameter analisis
> menggunakan **Minimum Support 20%** dan **Minimum Confidence 50%** (dapat diubah dari panel admin).

---

## ✨ Fitur Utama

### Sisi Publik
- **Landing page** dengan menu unggulan hasil rekomendasi Apriori (fallback ke menu populer bila data kurang).
- **Katalog menu** (`/catalog`) — daftar menu per kategori (Nasi Box, Tumpeng, Snack Basah/Kering, Minuman).
- **Paket menu** (`/packages`) — paket siap pesan.
- **Rekomendasi Menu (publik)** (`/rekomendasi-menu`) — hasil Apriori untuk pengunjung.
- **Budget Planner / SPK** (`/budget-planner`) — pelanggan memasukkan **budget**, sistem mengelompokkan
  rekomendasi menjadi **🍚 Makanan, 🥤 Minuman, 🍪 Snack**, dengan total harga **tidak melebihi budget**.
- **Halaman Kontak & Pesan** (`/contact-pesan`).
- **Autentikasi**: Login, Register, dan Lupa Password.

### Sisi Customer (login)
- **Dashboard customer**, **Keranjang** (`/customer/cart`), **Checkout** (`/checkout`).
- **Riwayat Pesanan** (`/customer/orders`) + profil & notifikasi.
- **Rekomendasi Paket Menu personal** (`/customer/recommendations`) — dihitung dari **riwayat transaksi akun
  yang login** (mesin Apriori dijalankan dengan `user_id`).
- **Review** pesanan.

### Sisi Admin (login, role: `admin`)
- **Dashboard**, manajemen **Kategori**, **Menu**, **Paket**, **Pelanggan**.
- **Pesanan (Orders)** — fokus operasional dapur/pengiriman.
- **Riwayat Budget Orders** — pemesanan yang berasal dari Budget Planner.
- **Transaksi** — fokus keuangan & rekap pembayaran.
- **Reports** & **Pengaturan Admin**.
- **Halaman Apriori (satu menu, dua bagian):**
  1. **Parameter & Analisis Global** — statistik, form *Minimum Support* & *Confidence*, daftar
     **Frequent Itemset (1–4 menu)** dengan support, serta daftar **Aturan Asosiasi** (Support, Confidence, Lift).
  2. **Bukti Personalisasi** — membandingkan perhitungan **2 pelanggan berdampingan** untuk membuktikan
     rekomendasi benar-benar belajar dari riwayat masing-masing.

---

## 🧠 Algoritma Apriori (inti sistem)

Perhitungan terpusat di **`app/Services/AprioriService.php`**. Halaman admin & pelanggan hanya *menampilkan* hasilnya.

**Rumus yang digunakan:**

```
Support(X)          = jumlah transaksi mengandung X / total transaksi
Confidence(A → B)   = Support(A ∪ B) / Support(A)
Lift(A → B)         = Support(A ∪ B) / (Support(A) × Support(B))
```

| Metrik | Ambang / Arti |
|---|---|
| **Support** | Lolos jika ≥ Min Support (default **20%**) |
| **Confidence** | Lolos jika ≥ Min Confidence (default **50%**) |
| **Lift** | `>1` korelasi positif (rekomendasi), `=1` netral, `<1` negatif. Hasil diurutkan dari Lift tertinggi. |

**Alur:** transaksi valid (status *Menunggu, Diproses, Selesai*; maks. 1000 terbaru) → itemset **L1–L4**
→ aturan asosiasi → rekomendasi terurut.

### Algoritma Apriori Berbasis Budget
`app/Services/BudgetRecommendationService.php` menggabungkan hasil Apriori dengan **alokasi anggaran**:
- Mengelompokkan kategori ke **Makanan / Minuman / Snack** (berdasarkan keyword slug/nama).
- **Constraint ketat**: total harga paket ≤ total budget, dan harga per porsi ≤ budget rata-rata per porsi.
- **Skoring gabungan**: 50% kekuatan asosiasi Apriori (Confidence/Lift/Support) + 50% kesesuaian anggaran.
- **Diversifikasi paket**: Paket 1, 2, 3, … memiliki kombinasi menu yang bervariasi.
- Bila budget naik → paket tier lebih tinggi dipertimbangkan; bila porsi naik/budget turun → kombinasi lebih ekonomis.

### Catatan penting
- Halaman admin **"Apriori"** adalah **satu menu**; menu "Bukti Apriori" telah digabung ke bagian bawahnya
  (URL lama `/admin/apriori-proof` tetap dapat diakses).
- Parameter disimpan di tabel **`settings`** (`min_support`, `min_confidence`) dan dibaca langsung tanpa cache,
  sehingga setiap perubahan langsung berlaku untuk halaman admin **dan** pelanggan.

📄 Dokumen penjelasan tambahan:
- `PENJELASAN_ADMIN_APRIORI.md` — penjelasan halaman admin Apriori & letak parameter.
- `LOKASI_PERHITUNGAN_APRIORI.md` — rumus + lokasi baris kode perhitungan.
- `PANDUAN_DEMO_SENIN.md` — panduan demo presentasi.

---

## 🛠️ Teknologi

| Komponen | Versi / Keterangan |
|---|---|
| PHP | ^8.2 |
| Laravel Framework | ^12.0 |
| Livewire | ^3 (komponen full-stack) |
| TailwindCSS | ^4 (via `@tailwindcss/vite`) |
| Vite | ^7 |
| Database | SQLite (default) — dapat diganti MySQL |
| Queue / Session / Cache | driver `database` |

Struktur aplikasi:
- **Livewire**: `app/Http/Livewire/Customer/*` & `app/Http/Livewire/Admin/*`
- **Service**: `app/Services/AprioriService.php`, `app/Services/BudgetRecommendationService.php`
- **Model**: `Menu`, `Category`, `Package`, `PackageDetail`, `Cart`, `Order`, `OrderDetail`,
  `BudgetOrder`, `BudgetOrderItem`, `Review`, `Setting`, `Notification`, `User`
- **Middleware role**: `app/Http/Middleware/RoleMiddleware.php` (`role:admin`, `role:customer`)
- **Layout view**: `layouts.app` (publik), `layouts.customer`, `layouts.admin`

---

## 🚀 Instalasi

**Prasyarat:** PHP ≥ 8.2, Composer, Node.js & npm.

```bash
# 1. Install dependensi PHP
composer install

# 2. Siapkan environment
copy .env.example .env      # Windows
php artisan key:generate

# 3. Migrasi database + data demo (menu, paket, transaksi, akun demo)
php artisan migrate --seed

# 4. Install & build asset frontend
npm install
npm run build
```

### Menjalankan (development)

```bash
# Jalankan server + queue + log + vite sekaligus
composer run dev
```

Atau terpisah:

```bash
php artisan serve
npm run dev
```

Buka `http://127.0.0.1:8000`.

> Proyek ini juga dapat dijalankan langsung di **Laragon** (`C:\laragon\www\malaa`).

### Menjalankan test

```bash
php artisan test
```

---

## 🔑 Akun Demo

Data demo dibuat oleh `DatabaseSeeder` beserta seeder pendukung (`DemoAprioriProofSeeder`,
`MassiveAprioriSeeder`, dll).

| Peran | Email | Password |
|---|---|---|
| **Admin** | `admindelisha@gmail.com` | `admindelisha_04` |
| Customer (contoh demo Apriori) | `nirmalamaharani101@gmail.com` | `password123` |
| Customer | `nirmanto@customer.com` | `password` |

---

## 🌐 Ringkasan Route Penting

| URL | Nama | Akses |
|---|---|---|
| `/` | `home` | Publik |
| `/catalog` | `catalog` | Publik |
| `/packages` | `packages` | Publik |
| `/rekomendasi-menu` | `customer.recommendations.public` | Publik |
| `/budget-planner` | `customer.budget-planner` | Publik |
| `/contact-pesan` | `contact.order` | Publik |
| `/customer/dashboard` | `customer.dashboard` | Customer |
| `/customer/cart` | `cart` | Customer |
| `/checkout` | `checkout` | Customer |
| `/customer/orders` | `customer.orders` | Customer |
| `/customer/recommendations` | `customer.recommendations` | Customer |
| `/admin/dashboard` | `admin.dashboard` | Admin |
| `/admin/menus`, `/admin/packages`, `/admin/categories` | — | Admin |
| `/admin/orders`, `/admin/budget-orders`, `/admin/transactions` | — | Admin |
| `/admin/apriori` | `admin.apriori` | Admin |
| `/admin/reports`, `/admin/settings` | — | Admin |

---

## 📄 Lisensi

Dibangun di atas framework [Laravel](https://laravel.com) (lisensi MIT) sebagai proyek implementasi skripsi.
