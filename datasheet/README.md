# Data Dictionary — Dataset Klasifikasi & Prediksi Panen Tambak
Sumber: TA Siska Amalia (PENS), *"Prediction Of Milkfish Harvest Potential Based On Pond Environment..."* + buku TA lengkap.
Diverifikasi terhadap PDF asli (Tabel 4.4–4.10, Lampiran 2 & 3) — lihat bagian "Catatan Verifikasi" di bawah.

## 1. Alur / pipeline yang direplikasi

```
[Suhu, PH, Padat Tebar, Jenis Ikan] --Naive Bayes--> [Kondisi Tambak: Normal/Tidak Normal]
                                                              |
                                                              v
                          [Kondisi Tambak + Bulan] --Regresi Linier--> [Hasil Panen (kg)]
```

Tahap 1 (klasifikasi) → `dataset_training_klasifikasi_tambak.csv` (fit) + `dataset_testing_klasifikasi_tambak.csv` (evaluasi).
Tahap 2 (regresi) → `dataset_prediksi_panen_regresi_linier.csv` (actual vs prediction, untuk validasi/refit).

Tabel 1 & 2 di paper IEEE (fish survival rate, standardisasi padat tebar) **tidak diikutkan** — itu konstanta referensi/lookup table (business rule), bukan data training.

## 2. `dataset_training_klasifikasi_tambak.csv` (1016 baris)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `waktu` | kategorik | Pagi / Siang / Sore — **lihat catatan di bawah, ini bukan kolom literal di tabel sumber** |
| `jenis_ikan` | kategorik | Bandeng / Vaname / Windu |
| `suhu_c` | numerik | Suhu air (°C) |
| `ph` | numerik | pH air |
| `padat_tebar` | kategorik | Normal / Tidak Normal |
| `hasil_klasifikasi` | target (y) | Normal / Tidak Normal — label kondisi tambak |

**Asal kolom `waktu` (penting):** tabel data training di buku (Lampiran 2–4) **tidak punya kolom waktu** — tiap baris cuma berisi Suhu/PH/Jenis Ikan/Padat Tebar/Hasil Klasifikasi. Nilai `waktu` di CSV ini **di-infer dari section/lampiran tempat baris itu berada**, bukan disalin dari kolom tabel:

| Lampiran | Judul section | Halaman PDF (absolut) | Jumlah baris |
|---|---|---|---|
| Lampiran 2 | "Data Training Pagi" | 141–150 | 338 |
| Lampiran 3 | "Data Training Siang" | 151–160 | 339 |
| Lampiran 4 | "Data Training Sore" | 161–170 | 339 |

Sudah dicek: jumlah baris per section di CSV (338/339/339) cocok persis dengan hitungan manual baris data di tiap Lampiran. Catatan: narasi di badan buku yang bilang "339 data masing-masing pagi, siang, sore" itu sendiri kurang presisi (Pagi aktualnya 338, bukan 339) — itu pembulatan/typo di teks buku, bukan salah ekstraksi.

**Penting — stratifikasi per waktu:** di TA asli, mean & stddev Gaussian untuk `suhu_c`/`ph` dihitung **terpisah per `waktu`** (Pagi/Siang/Sore), bukan sebagai satu fitur di model gabungan. Kalau 1016 baris di-pool jadi satu model tanpa stratifikasi, hasilnya tidak akan reproduce akurasi training yang diklaim TA. Rekomendasi: replikasi dulu 3 model Naive Bayes terpisah (per waktu) sebagai baseline sebelum eksperimen model gabungan.

**Data quality notes:**
- Typo `7..9` di baris `Pagi, Bandeng, suhu 28.0` (Lampiran 2, hlm. 121) sudah dibetulkan jadi `7.9`.
- Koma desimal (format PDF) diubah jadi titik supaya `pd.read_csv` langsung baca sebagai float.
- **5 baris duplikat exact** sengaja **tidak dihapus** (belum jelas apakah itu duplikat data lapangan asli atau input error) — keputusan drop/keep diserahkan ke tim ML.

## 3. `dataset_testing_klasifikasi_tambak.csv` (54 baris)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `waktu` | kategorik | Pagi / Siang / Sore |
| `tambak` | kategorik | Kode tambak A/B/C (per waktu — bukan ID unik lintas waktu) |
| `jenis_ikan` | kategorik | Bandeng / Vaname / Windu |
| `suhu_c`, `ph` | numerik | |
| `padat_tebar` | kategorik | Normal / Tidak Normal |
| `hasil_program` | keluaran model 1 | Hasil klasifikasi dari sistem/website TA |
| `hasil_rapidminer` | keluaran model 2 | Hasil klasifikasi dari RapidMiner (dipakai TA sebagai pembanding validasi) |

⚠️ **Bukan ground-truth murni.** `hasil_program` dan `hasil_rapidminer` adalah dua *keluaran model* yang dibandingkan satu sama lain (cocok 53/54 = 98,15%, sesuai Tabel 4.10), bukan label kebenaran lapangan independen. Kalau dipakai sebagai eval set, `hasil_rapidminer` bisa di-treat sebagai proxy ground-truth (itu yang dipakai TA sebagai pembanding) — tapi ini asumsi pemakaian, bukan fakta eksplisit di buku.

Tabel sumber (Tabel 4.10) memakai merged cell untuk `waktu`/`tambak` (label cuma muncul sekali per grup) → di-forward-fill supaya tiap baris punya nilai lengkap.

## 4. `dataset_prediksi_panen_regresi_linier.csv` (30 baris)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `tabel` | referensi | Nomor tabel asal di buku TA (Tabel 4.4–4.9) |
| `kondisi_tambak` | kategorik | Kode kondisi A–E (+ D*, lihat catatan) |
| `jenis_ikan` | kategorik | Bandeng / Vaname / Windu |
| `keadaan_tambak` | kategorik | Normal / Tidak Normal (hasil klasifikasi tahap 1) |
| `luas_lahan_m2` | numerik | Luas lahan tambak |
| `banyak_benih` | numerik | Jumlah benih ditebar |
| `bulan_ke` | numerik | Bulan ke-n dalam siklus panen |
| `actual_kg` | numerik | Hasil panen aktual (kg) |
| `prediction_kg` | numerik | Hasil panen prediksi regresi linier (kg) |
| `error_kg` | numerik | `actual_kg − prediction_kg` (**di-recompute**, lihat catatan) |
| `error_pct_of_actual` | numerik | `|error_kg| / actual_kg` — komponen MAPE per baris |

Hanya **30 baris agregat bulanan per skenario tambak** — ini validation/reference table, bukan "big dataset".

**Catatan penting — kolom Error di sumber asli:**
- Di Tabel 4.4 (kondisi A), kolom "Error" di PDF **konsisten** dengan `actual − prediction` (mis. 375,5 − 397,43 = -21,93 ✓).
- Tapi di Tabel 4.5 (kondisi B), kolom "Error" di PDF ternyata **tertukar** — nilainya sama persis dengan kolom "Error/Akt" di sebelahnya (bug copy-paste di penulisan buku), bukan `actual − prediction` yang sebenarnya.
- Kolom `error_kg` di CSV ini **di-recompute manual** (`actual_kg − prediction_kg`) untuk semua 30 baris, bukan copy nilai mentah dari PDF. Hasil MAPE dari nilai recompute **sudah diverifikasi cocok** dengan klaim MAPE per tabel di buku (mis. Tabel 4.5 → MAPE 2,13%) — jadi kolom `actual_kg`/`prediction_kg` sendiri terpercaya (dicek sama persis dengan PDF), cuma kolom Error asli yang bug.

**Kode `D*`:** sumber PDF memakai label "Kondisi Tambak D" **dua kali** untuk kondisi berbeda — Tabel 4.7 (Vaname, Tidak Normal) dan Tabel 4.9 (Windu, Tidak Normal). Baris dari Tabel 4.9 diberi kode `D*` di kolom `kondisi_tambak` supaya tidak tertukar saat join/groupby dengan baris dari Tabel 4.7.

## 5. Yang sengaja TIDAK dilakukan (keputusan modeling, bukan ekstraksi data)

- Tidak drop baris duplikat di training set
- Tidak scaling/normalize
- Tidak encoding kategorikal (one-hot/label)
- Tidak bikin train-test split baru dari gabungan 1016+54 baris

Semua itu diserahkan sebagai keputusan tim ML, bukan diputuskan sepihak saat ekstraksi.

## 6. Catatan Verifikasi

Ketiga file di atas sudah dicek ulang baris-per-baris terhadap sumber PDF asli:
- `dataset_training_klasifikasi_tambak.csv`: dicocokkan dengan Lampiran 2/3/4 (Data Training Pagi/Siang/Sore) — total baris per section (338/339/339), typo, dan 5 duplikat terkonfirmasi persis sama dengan sumber. Kolom `waktu` dikonfirmasi sebagai field hasil inferensi dari section header lampiran (bukan kolom asli tabel) — lihat catatan di bagian 2.
- `dataset_testing_klasifikasi_tambak.csv`: dicocokkan dengan Tabel 4.10 (Hasil Data Uji Testing) — seluruh 54 baris cocok persis, termasuk 1 baris mismatch (Vaname, Pagi, tambak B, suhu 30,5, ph 6,7) yang menghasilkan akurasi 53/54 = 98,15% sesuai klaim buku.
- `dataset_prediksi_panen_regresi_linier.csv`: dicocokkan dengan Tabel 4.4–4.9 — seluruh nilai `actual_kg`/`prediction_kg` cocok persis dengan PDF; kolom `error_kg` hasil recompute sudah diverifikasi menghasilkan MAPE yang sama dengan yang diklaim di tiap tabel sumber.

**Tidak ditemukan data fabrikasi.** Semua angka pada ketiga file berasal langsung dari tabel di buku TA, dengan koreksi yang didokumentasikan di atas (1 typo, 1 bug kolom Error, standardisasi format desimal, forward-fill label merged cell).
