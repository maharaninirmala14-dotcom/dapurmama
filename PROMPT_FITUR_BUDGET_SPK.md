# PROMPT — Fitur "Rekomendasi Menu Berdasarkan Budget" (SPK Pengelompokan Makanan, Minuman & Snack)

> Salin seluruh isi di bawah ini ke AI coding assistant (mis. Claude / ChatGPT / Cursor) di dalam konteks project `CateringNew`.

---

## PERAN
Kamu adalah senior full-stack developer Laravel 11 (PHP 8.2) + Livewire 3 + Blade + TailwindCSS. Kamu memahami pola penamaan, struktur, dan gaya kode yang sudah ada pada project ini. Kerjakan fitur secara lengkap, rapi, dan siap produksi.

## KONTEKS PROJECT
- Framework: **Laravel** + **Livewire 3**, styling **TailwindCSS**, build **Vite**.
- Model yang tersedia di `app/Models`: `Menu`, `Category`, `Package`, `PackageDetail`, `Cart`, `Order`, `OrderDetail`, `Review`, `Setting`, `User`.
- Struktur penting:
  - `Menu`: `category_id`, `name`, `description`, `price` (decimal), `image`, `is_favorite`. Relasi `belongsTo(Category)`.
  - `Category`: `name`, `slug`, `description`. Relasi `hasMany(Menu)`.
  - Komponen Livewire customer ada di `app/Http/Livewire/Customer/` (contoh: `Catalog.php`, `Packages.php`, `AprioriRecommendations.php`).
  - View Livewire ada di `resources/views/livewire/customer/`.
  - Route publik & customer ada di `routes/web.php` (contoh: `Route::get('/catalog', \App\Http\Livewire\Customer\Catalog::class)->name('catalog');`).
  - Layout: `layouts.app` (publik) dan `layouts.customer` (customer login).
  - Sudah ada `App\Services\AprioriService` untuk rekomendasi — **jangan hapus/ubah**, hanya boleh dimanfaatkan bila relevan.
- Kategori menu yang ada (perhatikan slug dapat bervariasi): `nasi-box`, `tumpeng`, `snack-basah`, `snack-kering`, `minuman`.

## TUJUAN FITUR
Membuat halaman/fitur **Decision Support System (SPK)** di mana **pelanggan menginput budget** (nominal rupiah), lalu sistem menampilkan rekomendasi menu yang **dikelompokkan otomatis menjadi 3 kelompok terpisah**:

1. 🍚 **Makanan** (mis. Nasi Box / Tumpeng)
2. 🥤 **Minuman**
3. 🍪 **Snack** (basah & kering)

Sistem memilih **satu rekomendasi utama per kelompok** (dan boleh menampilkan alternatif) yang **total harganya tidak melebihi budget** yang diinput pelanggan.

## KEBUTUHAN FUNGSIONAL
1. **Input budget**
   - Field input nominal budget (Rupiah) — wajib.
   - Field opsional: **jumlah porsi / pax**, jenis acara, dan tanggal (bila dipakai untuk hitung total).
   - Validasi: budget harus angka > 0. Tampilkan pesan error yang jelas bila kosong/tidak valid.

2. **Pengelompokan menu (grouping)**
   - Petakan setiap `Category` ke salah satu grup: `makanan`, `minuman`, atau `snack`.
   - Mapping harus **toleran** (deteksi berdasarkan slug ATAU nama, case-insensitive, mengandung kata kunci):
     - `makanan`: slug/nama mengandung `nasi`, `box`, `tumpeng`, `prasmanan`, `makanan`.
     - `minuman`: mengandung `minum`, `drink`, `es `, `jus`, `kopi`, `teh`.
     - `snack`: mengandung `snack`, `kering`, `basah`, `dessert`, `jajanan`, `kue`.
   - Buat mapping ini di satu tempat (mis. konstanta / method di service) agar mudah diubah.

3. **Logika SPK (inti)**
   - Buat service baru, contoh `app/Services/BudgetRecommendationService.php`, dengan method publik, mis. `recommend(float $budget, ?int $pax = null): array`.
   - Algoritma yang diminta:
     1. Ambil semua menu aktif (per kategori), urutkan per grup.
     2. Kelompokkan menjadi 3 bucket: makanan, minuman, snack.
     3. Tentukan **alokasi budget per grup** (mis. proporsi default makanan 60%, snack 25%, minuman 15% — buat dapat dikonfigurasi). Pastikan total alokasi = budget.
     4. Untuk **setiap grup secara terpisah**, pilih menu terbaik yang **harga item (atau harga total bila dikali pax) ≤ alokasi budget grup tersebut**, menggunakan pendekatan yang dapat dijelaskan (contoh: greedy berdasarkan kombinasi harga tertinggi yang masih dalam budget, atau knapsack sederhana untuk paket multi-item).
     5. Hasil akhir: satu set rekomendasi berisi 1 makanan + 1 minuman + 1 snack (bila tersedia), beserta total harga dan sisa budget.
     6. Sediakan **alternatif** per grup (mis. 3 opsi teratas) agar pelanggan bisa memilih.
   - **Constraint**: total seluruh rekomendasi (makanan + minuman + snack) **tidak boleh melebihi budget**.
   - Sertakan **alasan/penjelasan singkat** per rekomendasi (mis. "Harga Rp25.000, masuk 60% alokasi makan senilai Rp60.000").
   - Tangani kondisi: budget terlalu kecil untuk grup mana pun, atau tidak ada menu di suatu grup → kembalikan grup kosong dengan pesan yang ramah, jangan error.

4. **Output ke UI**
   - Tampilkan **3 kartu/kolom terpisah**: Makanan, Minuman, Snack.
   - Setiap kartu menampilkan: nama menu, deskripsi, harga, gambar, dan badge grup.
   - Tampilkan ringkasan: `Budget`, `Total Rekomendasi`, `Sisa Budget`.
   - Tombol **"Tambah ke Keranjang"** pada tiap item, memakai pola keranjang yang sudah ada (session `cart`, key `menu_{id}`, dispatch event `cartUpdated` + `notify` — ikuti `Catalog.php` / `Packages.php`).
   - Bila budget kurang, tampilkan pesan yang menyarankan menaikkan budget atau mengurangi grup.

5. **Perilaku hasil**
   - Setiap kali budget berubah dan ditekan tombol "Cari Rekomendasi", hasil dihitung ulang.
   - Bila pelanggan login, boleh memanfaatkan riwayat/`AprioriService` sebagai **tie-breaker** (opsional), tapi fungsi utama tetap berbasis budget.

## KEBUTUHAN NON-FUNGSIONAL
- Ikuti konvensi project: nama kelas PascalCase, service tanpa state global, validasi Livewire pada properti `#[Validate]` atau rule `$rules`.
- Gunakan **Livewire** (bukan controller REST baru) kecuali ada alasan kuat; ikuti gaya `Catalog.php`.
- Query efisien: hindari N+1, gunakan `with('category')`.
- Kode diberi komentar singkat berbahasa Indonesia seperti gaya file yang sudah ada.
- Uang diformat `Rp` dengan pemisah ribuan (mis. `Rp 25.000`).

## FILE YANG HARUS DIBUAT / DIUBAH
1. **BARU** `app/Services/BudgetRecommendationService.php` — logika SPK + grouping.
2. **BARU** `app/Http/Livewire/Customer/BudgetPlanner.php` — komponen Livewire (properti `budget`, `pax`, `result`, method `recommend()`, `addToCart()`).
3. **BARU** `resources/views/livewire/customer/budget-planner.blade.php` — UI 3 kolom + form budget.
4. **UBAH** `routes/web.php` — tambahkan route publik:
   ```php
   Route::get('/budget-planner', \App\Http\Livewire\Customer\BudgetPlanner::class)
       ->name('customer.budget-planner');
   ```
   (Letakkan di bagian PUBLIC ROUTES agar bisa diakses tanpa login.)
5. **OPSIONAL** Tambahkan tautan menu ke halaman ini di navbar layout (`resources/views/layouts/...`).

## KRITERIA PENERIMAAN (ACCEPTANCE CRITERIA)
- [ ] Pelanggan dapat mengisi budget dan menekan tombol untuk melihat rekomendasi.
- [ ] Hasil tampil dalam 3 kelompok terpisah: Makanan, Minuman, Snack.
- [ ] Total harga rekomendasi ≤ budget yang diinput.
- [ ] Setiap grup punya minimal 1 alternatif bila data tersedia.
- [ ] Ada ringkasan total & sisa budget.
- [ ] Item bisa ditambahkan ke keranjang dan badge keranjang ikut berubah.
- [ ] Budget tidak valid / terlalu kecil menampilkan pesan informatif (tidak error 500).
- [ ] Tidak mengubah atau merusak fitur Apriori, Catalog, Packages, Cart, Checkout yang sudah ada.
- [ ] `php artisan route:list` menampilkan route baru tanpa error.

## STYLE PENGERJAAN
- Tampilkan kode **lengkap per file** (bukan potongan), dengan path file di atas setiap blok kode.
- Setelah selesai, berikan **ringkasan singkat** perubahan dan **langkah mengetes**: buka `/budget-planner`, isi budget (contoh `Rp 100.000`), verifikasi 3 kelompok muncul dan total ≤ budget.
- Jangan gunakan placeholder seperti `// TODO`; tulis implementasi nyata.
- Jangan menghapus data atau file yang sudah ada tanpa menjelaskan alasannya.
