# 📐 Rumus Apriori + Lokasi Kodenya (pegangan cepat demo)

> Satu-satunya tempat perhitungan: **`app/Services/AprioriService.php`**.
> Halaman admin & pelanggan hanya **menampilkan** hasil mesin ini.

---

## 1. Rumus Utama

### Support (seberapa sering pola muncul)
```
Support(X) = jumlah transaksi yang mengandung X
             ─────────────────────────────────
             total transaksi yang dianalisis
```
> Syarat lolos: **Support(X) ≥ Min Support (20%)**

### Confidence (keyakinan aturan "Jika A, maka B")
```
Confidence(A → B) = Support(A ∪ B)
                    ─────────────────
                    Support(A)
```
> A ∪ B = kejadian A **dan** B muncul bersama dalam satu transaksi.
> Syarat lolos: **Confidence ≥ Min Confidence (50%)**

### Lift (kekuatan korelasi vs kebetulan)
```
Lift(A → B) =       Support(A ∪ B)
              ─────────────────────────────────
              Support(A) × Support(B)
```

| Nilai Lift | Artinya |
|---|---|
| **> 1** | Korelasi **positif** (A dan B sering bersama — direkomendasikan) |
| **= 1** | **Netral** / kebetulan |
| **< 1** | Korelasi **negatif** (jarang bersama) |

---

## 2. Contoh Nyata (data Nirmala saat demo)

Dataset: **17 transaksi**. Pola paket **Rp35.500** = *Nasi Ayam Goreng Gandeng + Es Jeruk + Dadar Gulung* tampil **4 kali**.

```
Support  = 4 / 17  = 23,5%   ✔ (≥ 20%)

Confidence (jika beli Nasi Ayam Goreng Gandeng + Es Jeruk → Dadar Gulung):
         = Support(A∪B) / Support(A)
         = (4/17) / (4/17) = 100%   ✔ (≥ 50%)

Lift     = Support(A∪B) / (Support(A) × Support(B))
         = (4/17) / ((4/17) × (4/17)) = 4,25   ✔ (jauh > 1 → sangat kuat)
```

---

## 3. Alur Algoritma (3 langkah kalau ditanya)

1. **Itemset 1**: hitung support tiap menu tunggal → simpan yang **≥ 20%** → `L1`.
2. **Itemset 2 → 4**: gabungkan kandidat, ukur lagi support, hanya yang **≥ 20%** lolos → `L2, L3, L4`.
   *(Dibatasi maksimal kombinasi 4 menu.)*
3. **Aturan asosiasi**: pecah tiap itemset jadi A → B, hitung **Confidence ≥ 50%** dan **Lift**.

> Hasil aturan diurutkan dari **Lift tertinggi** → jadilah rekomendasi pelanggan.

---

## 4. Lokasi Kode (kalau ditanya "perhitungannya di mana?")

| Rumus / langkah | File & baris |
|---|---|
| Filter support itemset 1 | `AprioriService.php` baris 502–503 |
| Filter support itemset k (2–4) | baris 548–549 |
| **Rumus Confidence** | **baris 597** |
| **Rumus Lift** | **baris 600** |
| Syarat aturan lolos (support & confidence) | baris 602 |
| Pintu masuk analisis `run()` | baris 198–210 |
| Inti algoritma `analyze()` | baris 447–646 |
| Gabung kandidat `generateCandidates()` | baris 648–672 |
| Rekomendasi menu `getRecommendations()` | baris 42–87 |
| Rekomendasi saat checkout (keranjang) | baris 103–188 |
| Data halaman bukti `getProofForUser()` | baris 235–340 |

**Layar penampil (bukan mesin):**
- Admin → `app/Http/Livewire/Admin/Apriori.php` (form + itemset + rules di `apriori.blade.php`, bukti di `apriori-proof.blade.php`).
- Pelanggan → `app/Http/Livewire/Customer/AprioriRecommendations.php` baris 46: `$service->run(auth()->id())` → **personal per pelanggan**.

---

## 5. Aturan Data yang Dihitung

- Transaksi valid: status **Selesai, Diproses, Menunggu** (order batal tidak dihitung).
- Maksimal **1000 transaksi** terbaru.
- Ambang saat ini: **Min Support 20%**, **Min Confidence 50%** (nilai skripsi, tersimpan di tabel `settings`).