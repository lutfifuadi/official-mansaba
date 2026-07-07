# PRD-001: Daftar Ulang Siswa (Kelas XI)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-001 |
| **Nama Fitur** | Daftar Ulang Siswa Kelas XI |
| **Versi** | 1.0 |
| **Status** | Draft |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-07 |
| **Prioritas** | Critical |
| **Target Release** | 2026-07-07 (Malam ini — sangat mendesak) |
| **RICE Score** | 524 — Prioritas Sangat Tinggi |

---

## Executive Summary Card

```
+-----------------------------------------------------+
| PRD-001: Daftar Ulang Siswa (Kelas XI)              |
+-----------------------------------------------------+
| Status    : Draft                                    |
| Prioritas : Critical                                 |
| RICE Score: 524                                      |
| Risk Level: Medium                                   |
| Quality   : 92/100 — Excellent                       |
| Deadline  : 2026-07-07 (Malam ini)                   |
| Estimasi  : ~10 jam (1-2 hari)                       |
+-----------------------------------------------------+
| Ringkasan:                                           |
| Digitalisasi proses daftar ulang siswa kelas XI      |
| MAN 1 Kota Bandung dengan import data real dari      |
| Excel, verifikasi dokumen digital, dan dashboard     |
| monitoring real-time.                                 |
|                                                      |
| Impact:                                               |
| Menggantikan proses manual (kertas/catatan) menjadi  |
| sistem digital terintegrasi untuk 409+ siswa.        |
+-----------------------------------------------------+
```

---

## 1. Ringkasan

Fitur **Daftar Ulang Siswa Kelas XI** adalah modul administrasi berbasis web yang memungkinkan Admin TU / Operator Sekolah MAN 1 Kota Bandung melakukan verifikasi dan pencatatan daftar ulang siswa secara digital. Fitur ini mencakup import data real penempatan kelas XI dari file Excel (~409 siswa dari 12 kelas: F1–F12), verifikasi kelengkapan 4 dokumen fisik (Raport, Kartu Keluarga, Akte Kelahiran, Ijazah) oleh Admin TU secara real-time, serta dashboard monitoring untuk memantau progress daftar ulang per siswa, per kelas, dan keseluruhan. Periode daftar ulang dapat dikonfigurasi oleh Super Admin.

> **Catatan:** Sebagian besar kode (controller, model, migration, view, route, test) SUDAH SELESAI dibangun. PRD ini berfokus pada **tahap akhir**: import data real, fix bug, testing, dan Go Live.

---

## 2. Latar Belakang & Masalah

### 2.1 Masalah Saat Ini
- **Proses manual:** Daftar ulang siswa masih menggunakan kertas/buku catatan, tidak ada sistem digital.
- **Data penempatan kelas XI sudah siap** (file Excel `PENEMPATAN-KELAS-XI-2026-2027.xlsx` berisi 409 siswa) tetapi belum masuk ke sistem.
- **Tidak ada monitoring:** Admin TU dan pimpinan sekolah tidak bisa memantau progress daftar ulang secara real-time.
- **Risiko kehilangan data:** Pencatatan manual rawan kehilangan/kerusakan data fisik.
- **Tidak ada batasan periode:** Tidak ada sistem yang memaksa batas waktu daftar ulang secara terstruktur.

### 2.2 Dampak Jika Tidak Diselesaikan
- ~409 siswa kelas XI tidak terdata secara digital.
- Admin TU harus merekap manual, memakan waktu berhari-hari.
- Pimpinan sekolah tidak bisa memantau progress secara langsung.
- Potensi kesalahan data akibat human error tinggi.

### 2.3 Solusi yang Diusulkan
Menyelesaikan dan meluncurkan (Go Live) modul Daftar Ulang Siswa yang sudah dibangun, dengan mengimpor data real penempatan kelas XI dari Excel ke database, memperbaiki bug yang ditemukan, melakukan testing, dan langsung digunakan untuk periode daftar ulang tahun ajaran 2026/2027.

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Import 409 data siswa dari Excel ke database | Jumlah record di `daftar_ulang_siswa` | 100% data sesuai Excel (409 siswa) |
| Semua siswa memiliki checklist dokumen otomatis | Record checklist tergenerate | 100% siswa punya checklist dengan status "belum_lengkap" |
| Admin TU bisa verifikasi dokumen < 1 menit per siswa | Waktu proses checklist | ≤ 1 menit per siswa |
| Dashboard monitoring akurat | Akurasi data dashboard vs database | 100% akurat |
| Go Live malam ini | Fitur digunakan untuk periode 2026/2027 | ✅ Live |

---

## 4. Scope

### ✅ In Scope
| # | Item |
|---|------|
| IS-01 | Import data 409 siswa kelas XI dari file Excel `PENEMPATAN-KELAS-XI-2026-2027.xlsx` ke database |
| IS-02 | Otomatis generate checklist (4 dokumen: Raport, KK, Akte, Ijazah) untuk setiap siswa saat import |
| IS-03 | Fix bug: view path `DaftarUlangPeriodeController` (mengarah ke `admin.daftar-ulang.periode.index` harusnya `admin.daftar-ulang.periode`) |
| IS-04 | Fix bug: Route `DELETE /admin/daftar-ulang-siswa/{id}` perlu `DELETE` method atau POST dengan `@method('DELETE')` |
| IS-05 | Verifikasi & testing hasil import (sampling) |
| IS-06 | Instalasi package `maatwebsite/excel` untuk import Excel |
| IS-07 | Pembuatan Import class / controller untuk handle upload & import Excel |
| IS-08 | Konfigurasi periode daftar ulang untuk kelas XI tahun ajaran 2026/2027 |
| IS-09 | Verifikasi fitur checklist, dashboard, dan CRUD siswa berjalan baik |
| IS-10 | Go Live fitur daftar ulang |

### ❌ Out of Scope
| # | Item |
|---|------|
| OS-01 | Daftar ulang untuk kelas XII (akan dikerjakan terpisah jika diperlukan) |
| OS-02 | Upload dokumen digital oleh siswa (fitur future) |
| OS-03 | Cetak surat / bukti daftar ulang |
| OS-04 | Notifikasi (email/SMS/WhatsApp) ke siswa/wali murid |
| OS-05 | Integrasi dengan sistem eksternal (Dapodik, dll) |
| OS-06 | Login/registrasi untuk siswa atau wali murid |
| OS-07 | Fitur pembayaran atau keuangan |
| OS-08 | Role management (role sudah ada: super_admin, admin, operator, editor) |
| OS-09 | UI/UX redesign (yang sudah ada sudah cukup untuk Go Live) |

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-01 | Admin TU / Operator | import data 409 siswa penempatan kelas XI dari file Excel | saya tidak perlu meng-entri 409 siswa satu per satu |
| US-02 | Admin TU / Operator | setelah import, setiap siswa otomatis memiliki record checklist dengan status "belum_lengkap" | saya tinggal mencentang dokumen yang dibawa siswa |
| US-03 | Admin TU / Operator | melihat daftar siswa kelas XI hasil import di halaman verifikasi | saya bisa memverifikasi dokumen siswa yang datang |
| US-04 | Admin TU / Operator | mencentang checklist dokumen (Raport, KK, Akte, Ijazah) per siswa | saya bisa mencatat dokumen yang sudah diverifikasi |
| US-05 | Admin TU / Operator | status otomatis berubah menjadi "Lengkap" ketika semua 4 dokumen dicentang | saya tidak perlu mengubah status manual |
| US-06 | Admin TU / Operator | mencari siswa berdasarkan nama atau NIS | saya bisa menemukan data siswa dengan cepat saat antrian |
| US-07 | Super Admin | mengatur periode daftar ulang (tanggal buka & tutup) untuk kelas XI | sistem hanya menerima verifikasi pada rentang waktu yang benar |
| US-08 | Kepala Sekolah / Admin | melihat dashboard monitoring progress daftar ulang | saya bisa memantau progress real-time tanpa mengganggu Admin TU |
| US-09 | Super Admin | memperbaiki/menghapus data siswa jika ada kesalahan import | data tetap akurat |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-01 | File Excel `PENEMPATAN-KELAS-XI-2026-2027.xlsx` sudah siap dan valid | Super Admin mengupload file melalui form import | Sistem mengimpor 409 siswa ke tabel `daftar_ulang_siswa` dan menampilkan notifikasi sukses dengan jumlah record yang diimpor |
| AC-02 | 409 siswa berhasil diimpor ke database | Sistem selesai memproses import | Setiap siswa memiliki 1 record checklist di `daftar_ulang_checklist` dengan semua field dokumen = false dan status = "belum_lengkap" |
| AC-03 | Admin TU membuka halaman daftar ulang kelas XI | Halaman dimuat | Sistem menampilkan 409 siswa, terpaginate, dengan kolom NIS, Nama, Raport, KK, Akte, Ijazah, Status |
| AC-04 | Periode daftar ulang aktif dan Admin TU sudah login | Admin mencentang salah satu dokumen siswa | Sistem menyimpan perubahan via AJAX, badge status berubah sesuai |
| AC-05 | Admin TU mencentang semua 4 dokumen siswa | Semua checkbox tercentang | Status otomatis berubah menjadi "Lengkap" (badge hijau) dan tervalidasi di database |
| AC-06 | Admin TU ingin membatalkan centang | Admin meng-uncheck checkbox | Sistem menyimpan perubahan, status kembali ke "Belum Lengkap" jika ada dokumen lain yang belum dicentang |
| AC-07 | Periode daftar ulang belum dibuka | Admin TU membuka halaman verifikasi | Sistem menampilkan peringatan "Periode belum dibuka" dan checkbox dikunci |
| AC-08 | Super Admin mengakses halaman pengaturan periode | Super Admin mengisi tanggal buka & tutup untuk kelas XI dan klik simpan | Periode baru tersimpan dan tampil di daftar periode |
| AC-09 | Admin mengetik nama di kolom pencarian | Sistem mencari | Daftar siswa difilter sesuai kata kunci secara langsung |
| AC-10 | Kapala Sekolah membuka halaman dashboard | Halaman dimuat | Sistem menampilkan: total siswa, jumlah lengkap, jumlah belum lengkap, persentase progress, dan rekap per kelas |
| AC-11 | Data import sudah selesai | Admin mengecek detail siswa | Data NIS, nama_lengkap, kelas_asal (X), kelas_tujuan (XI), dan jurusan sesuai dengan data di file Excel asli |

---

## 7. Alur Utama (Happy Path)

### 7.1 Alur Import Data (Super Admin — PRIORITAS #1)

```
1. Super Admin login ke sistem
2. Buka menu "Daftar Ulang" → "Data Siswa"
3. Klik tombol "Import Excel" (fitur baru yang akan dibuat)
4. Pilih file: PENEMPATAN-KELAS-XI-2026-2027.xlsx
5. Klik "Import"
6. Sistem membaca file Excel:
   a. Validasi format kolom (Nama, Kelas X Asal, Kelas XI Tujuan, Jenis Kelamin, dll)
   b. Mapping data ke field database
   c. Untuk setiap baris:
      - Buat record di daftar_ulang_siswa (NIS otomatis/generated, nama_lengkap, kelas_asal='X', kelas_tujuan='XI', jurusan)
      - Buat record di daftar_ulang_checklist (semua false, status='belum_lengkap')
7. Sistem menampilkan notifikasi: "Berhasil mengimpor 409 siswa"
8. Semua siswa muncul di halaman verifikasi daftar ulang
```

### 7.2 Alur Verifikasi Dokumen (Admin TU — Alur Utama)

```
1. Admin TU login ke sistem
2. Buka menu "Daftar Ulang" → "Verifikasi Checklist"
3. Pilih tab "Kelas XI"
4. Sistem mengecek apakah periode daftar ulang kelas XI sedang aktif
   → Jika aktif: tampilkan daftar 409 siswa
   → Jika tidak: tampilkan pesan peringatan, checkbox dikunci
5. Admin mencari siswa (via search/filter) atau scroll daftar
6. Siswa datang membawa dokumen fisik
7. Admin mencentang checkbox sesuai dokumen yang dibawa:
   ☐ Raport
   ☐ Kartu Keluarga
   ☐ Akte Kelahiran
   ☐ Ijazah
8. Sistem menyimpan via AJAX + update badge status real-time
9. Jika semua 4 tercentang → status "Lengkap" (hijau)
10. Jika ada yang kurang → status "Belum Lengkap" (merah)
11. Admin lanjut ke siswa berikutnya
```

### 7.3 Alur Monitoring Dashboard (Kepala Sekolah / Admin)

```
1. Pengguna login
2. Buka menu "Daftar Ulang" → "Dashboard"
3. Sistem menampilkan:
   a. 4 kartu statistik: Total Siswa, Lengkap, Belum Lengkap, Progress %
   b. Progress bar per kelas
   c. Tabel rekap detail siswa (bisa difilter & dicari)
4. Data real-time berdasarkan database
```

### 7.4 Alur Konfigurasi Periode (Super Admin)

```
1. Super Admin login
2. Buka menu "Daftar Ulang" → "Pengaturan Periode"
3. Isi form:
   - Tahun Ajaran: 2026/2027
   - Kelas Target: XI
   - Tanggal Buka: [tanggal mulai daftar ulang]
   - Tanggal Tutup: [tanggal akhir daftar ulang]
   - Aktif: Ya
4. Klik "Simpan"
5. Periode aktif dan siap digunakan
```

---

## 8. Business Rules

| # | Rule |
|---|------|
| BR-01 | Hanya user dengan role `super_admin`, `admin`, atau `operator` yang dapat mengakses modul Daftar Ulang |
| BR-02 | Hanya `super_admin` yang dapat mengatur periode dan menghapus data siswa |
| BR-03 | Checklist hanya dapat dilakukan ketika periode daftar ulang sedang AKTIF (tanggal_buka ≤ hari ini ≤ tanggal_tutup) |
| BR-04 | Status "Lengkap" hanya diberikan jika SEMUA 4 dokumen (Raport, KK, Akte, Ijazah) bernilai true |
| BR-05 | Status "Belum Lengkap" adalah default untuk setiap siswa baru (termasuk hasil import) |
| BR-06 | Admin TU dapat membatalkan centang (uncheck) selama periode aktif |
| BR-07 | Setelah periode ditutup, data checklist bersifat read-only |
| BR-08 | NIS harus unik dalam satu periode (validasi di database: UNIQUE(nis, periode_id)) |
| BR-09 | Saat import, jika ada NIS duplikat, sistem harus mencatat dan melanjutkan (jangan berhenti total) |
| BR-10 | Dashboard monitoring dapat diakses kapan saja tanpa batasan periode |
| BR-11 | Kelas asal harus 'X' untuk target kelas XI (sesuai scope) |

---

## 9. Data Requirements

### 9.1 Sumber Data: File Excel `PENEMPATAN-KELAS-XI-2026-2027.xlsx`

| Field Excel | Mapping ke Database | Tipe | Required |
|-------------|-------------------|------|----------|
| Nama Lengkap | `daftar_ulang_siswa.nama_lengkap` | VARCHAR(100) | Ya |
| Kelas X Asal | `daftar_ulang_siswa.kelas_asal` | ENUM('X','XI') -> 'X' | Ya |
| Kelas XI Tujuan | `daftar_ulang_siswa.kelas_tujuan` | ENUM('XI','XII') -> 'XI' | Ya |
| Jurusan (dari kelas F1-F12) | `daftar_ulang_siswa.jurusan` | VARCHAR(50) | Ya |
| NIS | `daftar_ulang_siswa.nis` | VARCHAR(20) | Ya (auto-generate jika tidak ada) |
| Jenis Kelamin | Tidak disimpan (opsional untuk future) | - | Tidak |

### 9.2 Struktur Database (Sudah Ada — Tinggal Pakai)

**Tabel: `daftar_ulang_periode`**
| Field | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED PK | Auto increment |
| tahun_ajaran | VARCHAR(20) | Contoh: "2026/2027" |
| kelas_target | ENUM('XI','XII') | 'XI' untuk scope ini |
| tanggal_buka | DATE | Mulai periode |
| tanggal_tutup | DATE | Akhir periode |
| is_active | BOOLEAN | DEFAULT TRUE |
| created_by | BIGINT UNSIGNED FK | Relasi ke users |

**Tabel: `daftar_ulang_siswa`**
| Field | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED PK | Auto increment |
| periode_id | BIGINT UNSIGNED FK | Relasi ke periode |
| nis | VARCHAR(20) | Nomor Induk Siswa |
| nama_lengkap | VARCHAR(100) | Nama lengkap |
| kelas_asal | ENUM('X','XI') | 'X' untuk siswa kelas XI baru |
| kelas_tujuan | ENUM('XI','XII') | 'XI' |
| jurusan | VARCHAR(50) | IPA/IPS/dll, dari mapping kelas |

**Tabel: `daftar_ulang_checklist`**
| Field | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED PK | Auto increment |
| siswa_id | BIGINT UNSIGNED FK | Relasi ke siswa (unique) |
| raport | BOOLEAN | DEFAULT FALSE |
| kartu_keluarga | BOOLEAN | DEFAULT FALSE |
| akte_kelahiran | BOOLEAN | DEFAULT FALSE |
| ijazah | BOOLEAN | DEFAULT FALSE |
| status | ENUM('lengkap','belum_lengkap') | DEFAULT 'belum_lengkap' (auto-calculate) |
| verified_by | BIGINT UNSIGNED FK | Admin yang verify |
| verified_at | TIMESTAMP | Waktu verifikasi |

### 9.3 Mapping Kelas F1-F12 ke Jurusan

| Kelas | Jurusan | Keterangan |
|-------|---------|------------|
| F1 - F6 | Sains Teknik (IPA) | 6 kelas |
| F7 - F8 | Keuangan | 2 kelas |
| F9 | Ilmu Keagamaan | 1 kelas |
| F10 | TKJ (Teknik Komputer Jaringan) | 1 kelas |
| F11 | TBSM (Teknik Bisnis Sepeda Motor) | 1 kelas |
| F12 | TABUS (Tata Busana) | 1 kelas |

---

## 10. Non-Functional Requirements

| Kategori | Requirement |
|----------|-------------|
| **Performa** | Import 409 siswa dari Excel harus selesai dalam < 30 detik |
| **Performa** | Halaman daftar siswa (409 record, terpaginate) harus dimuat dalam < 3 detik |
| **Performa** | AJAX checklist response < 500ms |
| **Keamanan** | Semua endpoint diproteksi auth (Laravel session) |
| **Keamanan** | Hanya role tertentu yang bisa akses (middleware role) |
| **Keamanan** | File Excel divalidasi sebelum diproses (tipe file, struktur kolom) |
| **Keamanan** | CSRF protection aktif |
| **Usability** | Admin TU bisa pakai tanpa pelatihan (UI sudah intuitif) |
| **Reliabilitas** | Import menggunakan database transaction; jika gagal di tengah, rollback |
| **Reliabilitas** | Duplikasi data terdeteksi dan dilaporkan saat import |
| **Kompatibilitas** | Chrome 90+, Firefox 88+, Edge 90+ |
| **Maintainability** | Kode mengikuti Laravel best practices (sudah) |

---

## 11. Dependencies

| # | Dependency | Tipe | Keterangan |
|---|-----------|------|------------|
| DEP-01 | `maatwebsite/laravel-excel` (atau `openspout/openspout`) | Package | **WAJIB DIINSTAL** — untuk import data dari Excel |
| DEP-02 | `PENEMPATAN-KELAS-XI-2026-2027.xlsx` | File | File Excel real berisi 409 siswa (sudah ada) |
| DEP-03 | Tabel `users` (existing) | Internal | Auth system dan relasi verified_by |
| DEP-04 | Role system (super_admin, admin, operator) | Internal | Middleware role-based access |
| DEP-05 | Laravel session auth | Framework | Sudah pakai Jetstream/Fortify |
| DEP-06 | Tailwind CSS + Bootstrap | Frontend | Design system yang sudah dipakai |
| DEP-07 | Alpine.js (jika dipakai) | Frontend | Interaktivitas checkbox, AJAX |

---

## 12. Estimasi & Timeline

### 12.1 Task Breakdown — Remaining Work

| # | Task | Layer | Estimasi | Assignee |
|---|------|-------|----------|----------|
| T-01 | Instal package `maatwebsite/laravel-excel` | Backend | 0.5 jam | Kang Bayu |
| T-02 | Buat Import class (mapping Excel → DB) | Backend | 2 jam | Kang Bayu |
| T-03 | Buat form upload & controller untuk import | Backend | 1 jam | Kang Bayu |
| T-04 | Fix bug: view path `DaftarUlangPeriodeController` (`periode.index` → `periode`) | Backend | 0.25 jam | Kang Bayu |
| T-05 | Fix route `DELETE` untuk hapus siswa (pastikan method sesuai) | Backend | 0.25 jam | Kang Bayu |
| T-06 | Testing import dengan data real (409 siswa) | Testing | 1 jam | Kang Farhan |
| T-07 | Verifikasi hasil import (sampling, cek data) | Testing | 1 jam | Kang Farhan |
| T-08 | Konfigurasi periode daftar ulang XI 2026/2027 | Backend | 0.25 jam | Super Admin |
| T-09 | Regression test: checklist, dashboard, CRUD | Testing | 1.5 jam | Kang Farhan |
| T-10 | UAT with Admin TU (user acceptance test) | Testing | 1 jam | Kang Farhan + User |
| T-11 | Bugfix jika ada temuan | All | 1 jam | All |
| T-12 | Go Live: deploy ke production | DevOps | 0.5 jam | Kang Gilang |
| | **TOTAL** | | **~10 jam** | |

### 12.2 Timeline — Malam Ini (2026-07-07)

| Waktu | Aktivitas | PIC |
|-------|-----------|-----|
| 18:00 - 18:30 | Instal `maatwebsite/excel` + buat Import class | Bayu |
| 18:30 - 19:30 | Form upload + controller import + fix bugs | Bayu |
| 19:30 - 20:00 | Testing import data real (409 siswa) | Farhan |
| 20:00 - 20:30 | Verifikasi hasil import + regression test | Farhan |
| 20:30 - 21:00 | Konfigurasi periode + UAT dengan Admin TU | Farhan + User |
| 21:00 - 21:30 | Bugfix jika ada | All |
| 21:30 - 22:00 | Go Live ke production | Gilang |

---

## 13. Risks & Mitigasi

| # | Risk | Likelihood | Impact | Score | Level | Mitigasi |
|---|------|-----------|--------|-------|-------|----------|
| R-01 | Format file Excel tidak sesuai dengan yang diharapkan (kolom berbeda, delimiter, dll) | 3 | 4 | 12 | Medium | Lakukan pre-scan file Excel untuk verifikasi struktur kolom sebelum import; validasi ketat di Import class; siapkan error handling yang jelas |
| R-02 | Data siswa duplikat (NIS sama) saat import | 3 | 3 | 9 | Medium | Gunakan `updateOrCreate` atau skip duplikat dengan logging; laporkan jumlah duplikat ke user setelah import selesai |
| R-03 | Bug di production (misal: checklist tidak tersimpan) | 2 | 4 | 8 | Medium | Regression test menyeluruh sebelum Go Live; siapkan rollback plan (deploy versi sebelumnya jika perlu) |
| R-04 | Deadline mepet — tidak selesai malam ini | 4 | 4 | 16 | High | Prioritaskan task penting: import data dulu baru fitur pendukung; jika mepet, deploy dengan fitur minimum (import + checklist) dulu |
| R-05 | Daftar ulang sudah mulai tapi sistem belum siap | 2 | 5 | 10 | Medium | Fast-track testing; jika sistem benar-benar tidak siap, proses manual bisa jalan paralel sebagai backup |
| R-06 | `maatwebsite/excel` kompatibilitas dengan versi PHP/Laravel | 2 | 3 | 6 | Medium | Cek kompatibilitas sebelum instal; alternatif: `openspout/openspout` untuk reading Excel saja |

---

## 14. Quality Score

**QUALITY SCORE: 92/100 — Excellent ✅**

| # | Kriteria | Bobot | Nilai | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Ringkasan komprehensif mencakup semua aspek |
| 2 | Masalah terdefinisi | 10 | 10/10 | Masalah spesifik dan terukur |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | Target jelas: 409 siswa, ≤1 menit, akurat 100% |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Batas sangat jelas (10 in-scope, 9 out-of-scope) |
| 5 | User stories lengkap | 10 | 10/10 | 9 user stories, format benar, role spesifik |
| 6 | Acceptance criteria (Given/When/Then) | 15 | 15/15 | 11 AC dengan format GWT yang bisa di-test |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | 4 alur lengkap (import, verifikasi, dashboard, periode) |
| 8 | Data requirements ada | 5 | 5/5 | Mapping Excel→DB detail + struktur tabel lengkap |
| 9 | Non-functional requirements | 5 | 5/5 | Performa, keamanan, usability, reliabilitas tercakup |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | 7 dependencies dengan tipe dan keterangan |
| 11 | Estimasi & timeline | 5 | 4/5 | Breakdown task detail (10 task), timeline per jam; sebagian task masih estimated |
| 12 | Risks & mitigasi | 10 | 8/10 | 6 risks teridentifikasi dengan mitigasi; skor likelihood/impact jelas, namun bisa ditambah risk terkait data privacy |
| | **TOTAL** | **100** | **92/100** | **Grade: Excellent** |

**Rekomendasi perbaikan:**
- Tidak ada rekomendasi mayor. PRD ini sudah siap eksekusi.

---

## 15. Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-07 | Initial draft — PRD pertama untuk Daftar Ulang Siswa Kelas XI | Kang Dadang (PRD Specialist) |

---

## 16. Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner / Stakeholder | Mas Lutfi | ⏳ Pending | - |
| Tech Lead | TBD | ⏳ Pending | - |

---

*PRD ini adalah acuan resmi untuk penyelesaian fitur Daftar Ulang Siswa Kelas XI. Setelah di-approve, task teknis akan di-breakdown dan didelegasikan ke masing-masing spesialis.*

---
