# PRD-001: Daftar Ulang Siswa Lama

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-001 |
| **Nama Fitur** | Daftar Ulang Siswa Lama |
| **Versi** | 1.0 |
| **Status** | Approved |
| **Penulis** | Sophia (PM) |
| **Tanggal Dibuat** | 2026-07-07 |
| **Tanggal Direvisi** | - |
| **Prioritas** | High |
| **Target Release** | TBD |
| **RICE Score** | Reach: 4 | Impact: 5 | Confidence: 5 | Effort: 3 → **Score: 33** |

---

## 📋 Executive Summary

Fitur **Daftar Ulang Siswa Lama** adalah modul administrasi yang memungkinkan Admin TU / Operator Sekolah MAN Sabdodadi untuk melakukan verifikasi kelengkapan dokumen fisik siswa yang akan naik kelas — dari kelas X ke XI, dan dari kelas XI ke XII.

Admin melakukan checklist dokumen secara digital melalui dashboard, sementara siswa datang langsung membawa dokumen fisik. Sistem akan memperbarui status daftar ulang siswa di database dan menyediakan dashboard monitoring real-time. Periode daftar ulang dapat dikonfigurasi oleh admin untuk setiap tahun ajaran baru.

---

## 1. Latar Belakang & Masalah

### 1.1 Masalah Saat Ini
- Proses daftar ulang siswa lama masih dilakukan secara manual (kertas/buku catatan)
- Admin TU kesulitan memantau siapa saja yang sudah dan belum daftar ulang secara real-time
- Tidak ada rekapitulasi digital yang mudah diakses oleh pihak sekolah
- Risiko kehilangan/kerusakan data akibat pencatatan manual
- Tidak ada pembatas waktu/periode yang terstruktur di sistem

### 1.2 Dampak Jika Tidak Diselesaikan
- Data daftar ulang tidak akurat dan sulit ditelusuri
- Admin TU harus merekap manual yang memakan banyak waktu
- Pimpinan sekolah tidak bisa memantau progress daftar ulang secara langsung
- Potensi siswa yang terlewat tidak terdaftar ulang

### 1.3 Solusi yang Diusulkan
Membangun modul Daftar Ulang Siswa Lama berbasis web yang terintegrasi dengan sistem Mansaba Official Website, lengkap dengan:
- Form checklist dokumen per siswa oleh Admin TU
- Dashboard monitoring progress daftar ulang
- Konfigurasi periode daftar ulang per tahun ajaran
- Rekap status per kelas dan keseluruhan

---

## 2. Tujuan & Metrik Keberhasilan

| # | Tujuan | Metrik | Target |
|---|--------|--------|--------|
| 1 | Digitalisasi proses daftar ulang | 100% data daftar ulang masuk ke sistem | ≥ 95% siswa terdata |
| 2 | Efisiensi waktu admin TU | Waktu proses checklist per siswa | ≤ 2 menit per siswa |
| 3 | Monitoring real-time | Dashboard tersedia dan akurat | Akurasi data 100% |
| 4 | Fleksibilitas periode | Admin bisa setting periode kapan saja | Bisa diubah < 1 menit |
| 5 | Kemudahan penggunaan | Admin bisa pakai tanpa pelatihan teknis | Sistem mudah dipahami |

---

## 3. Stakeholder

| Stakeholder | Peran | Kepentingan |
|------------|-------|-------------|
| **Admin TU / Operator** | Pengguna utama sistem | Melakukan checklist dokumen siswa |
| **Kepala Sekolah** | Pemantau | Melihat dashboard rekap daftar ulang |
| **Wakasek Kesiswaan** | Pemantau | Monitoring per kelas/angkatan |
| **Super Admin** | Pengelola sistem | Konfigurasi periode, manajemen user |
| **Siswa (Kelas X & XI)** | Subjek data | Datang membawa dokumen fisik |
| **Wali Murid** | Tidak langsung | Memastikan siswa daftar ulang tepat waktu |

---

## 4. Scope

### ✅ In Scope
- Data master siswa (nama, NIS, kelas) — diinput oleh admin
- Checklist 4 item dokumen: Raport, KK (QR Code), Akte Kelahiran, Ijazah
- Status daftar ulang: **Lengkap** / **Belum Lengkap**
- Konfigurasi periode daftar ulang (tanggal buka & tutup) per kelas per tahun ajaran
- Dashboard monitoring: status per siswa, per kelas, rekap keseluruhan
- Filter dan pencarian siswa di dashboard
- Riwayat perubahan status checklist (audit trail sederhana)
- Akses berbasis role (Admin TU, Super Admin, Kepala Sekolah sebagai viewer)

### ❌ Out of Scope
- Upload dokumen digital oleh siswa
- Fitur pembayaran / keuangan
- Cetak surat / bukti daftar ulang
- Notifikasi email / SMS / WhatsApp ke siswa atau wali murid
- Integrasi dengan sistem eksternal (Dapodik, dll)
- Fitur PPDB (Penerimaan Peserta Didik Baru) — sudah ada modul terpisah
- Login/akun untuk siswa atau wali murid

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-01 | Admin TU | mencari siswa berdasarkan nama atau NIS | saya bisa menemukan data siswa dengan cepat saat antrian panjang |
| US-02 | Admin TU | melihat daftar semua siswa kelas X yang belum daftar ulang | saya bisa mengetahui siapa yang belum hadir |
| US-03 | Admin TU | mencentang dokumen Raport yang sudah dibawa siswa | saya bisa mencatat bahwa dokumen tersebut sudah diverifikasi |
| US-04 | Admin TU | mencentang dokumen KK (QR Code) yang sudah dibawa siswa | saya bisa mencatat dokumen KK sudah diverifikasi |
| US-05 | Admin TU | mencentang dokumen Akte Kelahiran yang sudah dibawa siswa | saya bisa mencatat dokumen Akte sudah diverifikasi |
| US-06 | Admin TU | mencentang dokumen Ijazah yang sudah dibawa siswa | saya bisa mencatat dokumen Ijazah sudah diverifikasi |
| US-07 | Admin TU | melihat status otomatis berubah menjadi "Lengkap" ketika semua dokumen dicentang | saya tidak perlu mengubah status secara manual |
| US-08 | Admin TU | membatalkan centang dokumen jika terjadi kesalahan input | saya bisa mengoreksi data yang salah |
| US-09 | Super Admin | mengatur tanggal buka dan tutup periode daftar ulang kelas XI | sistem hanya menerima input pada rentang waktu yang benar |
| US-10 | Super Admin | mengatur tanggal buka dan tutup periode daftar ulang kelas XII | sistem hanya menerima input pada rentang waktu yang benar |
| US-11 | Super Admin | menambahkan data siswa baru yang akan daftar ulang | semua siswa terdaftar dalam sistem |
| US-12 | Kepala Sekolah | melihat dashboard rekap daftar ulang secara keseluruhan | saya bisa memantau progress tanpa harus bertanya ke admin TU |
| US-13 | Kepala Sekolah | melihat berapa persen siswa yang sudah daftar ulang | saya bisa mengevaluasi progress dengan cepat |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-01 | Admin TU sudah login dan periode daftar ulang aktif | Admin membuka halaman daftar ulang kelas XI | Sistem menampilkan daftar siswa kelas XI dengan status masing-masing |
| AC-02 | Admin TU sudah menemukan siswa di sistem | Admin mencentang checkbox "Raport" untuk siswa tersebut | Sistem menyimpan perubahan dan menampilkan centang yang tersimpan |
| AC-03 | Admin TU sudah mencentang semua 4 dokumen siswa | Semua checkbox tercentang | Status siswa otomatis berubah menjadi **"Lengkap"** |
| AC-04 | Admin TU belum mencentang semua dokumen | Halaman dimuat | Status siswa tampil sebagai **"Belum Lengkap"** |
| AC-05 | Admin TU sudah mencentang dokumen secara keliru | Admin membatalkan centang | Sistem menyimpan perubahan, status kembali ke "Belum Lengkap" jika ada dokumen yang belum dicentang |
| AC-06 | Super Admin membuka halaman konfigurasi periode | Super Admin mengisi tanggal buka dan tutup untuk kelas XI | Sistem menyimpan konfigurasi dan menampilkan konfirmasi sukses |
| AC-07 | Periode daftar ulang belum dibuka | Admin TU membuka halaman checklist | Sistem menampilkan pesan bahwa periode belum dibuka |
| AC-08 | Periode daftar ulang sudah ditutup | Admin TU mencoba mencentang dokumen | Sistem menampilkan pesan bahwa periode sudah ditutup dan tidak memperbolehkan perubahan |
| AC-09 | Admin TU mengetikkan nama siswa di kolom pencarian | Sistem menemukan data | Daftar siswa difilter sesuai kata kunci secara real-time |
| AC-10 | Kepala Sekolah membuka dashboard | Halaman dashboard dimuat | Sistem menampilkan: total siswa, jumlah Lengkap, jumlah Belum Lengkap, persentase, dan tabel rekap per kelas |
| AC-11 | Super Admin menambah data siswa baru | Sistem menyimpan data | Siswa baru muncul di daftar dengan status "Belum Lengkap" dan semua checkbox unchecked |
| AC-12 | Admin memfilter berdasarkan kelas | Filter diterapkan | Hanya siswa dari kelas yang dipilih yang tampil |

---

## 7. Alur Utama (Happy Path)

### 7.1 Alur Setting Periode (Super Admin)

```
1. Super Admin login ke sistem
2. Buka menu "Daftar Ulang" → "Pengaturan Periode"
3. Pilih Tahun Ajaran (contoh: 2026/2027)
4. Isi form:
   - Periode Kelas XI: Tanggal Buka [dd/mm/yyyy] s.d Tanggal Tutup [dd/mm/yyyy]
   - Periode Kelas XII: Tanggal Buka [dd/mm/yyyy] s.d Tanggal Tutup [dd/mm/yyyy]
5. Klik tombol "Simpan Pengaturan"
6. Sistem menyimpan konfigurasi ke database
7. Sistem menampilkan notifikasi sukses
8. Periode aktif ditampilkan di halaman utama daftar ulang
```

### 7.2 Alur Input Data Siswa (Super Admin / Admin TU)

```
1. Admin login ke sistem
2. Buka menu "Daftar Ulang" → "Data Siswa"
3. Klik tombol "Tambah Siswa"
4. Isi form:
   - NIS (Nomor Induk Siswa)
   - Nama Lengkap
   - Kelas Asal (X atau XI)
   - Kelas Tujuan (XI atau XII)
   - Jurusan/Program (jika ada)
5. Klik "Simpan"
6. Sistem membuat record daftar ulang dengan status "Belum Lengkap"
7. Semua checkbox dokumen dalam kondisi unchecked
```

### 7.3 Alur Verifikasi Dokumen (Admin TU — Alur Utama)

```
1. Admin TU login ke sistem
2. Buka menu "Daftar Ulang"
3. Pilih tab kelas (Kelas XI atau Kelas XII)
4. Sistem mengecek apakah periode daftar ulang sedang aktif
   → Jika aktif: tampilkan daftar siswa
   → Jika belum/sudah lewat: tampilkan pesan informasi
5. Admin TU mencari siswa menggunakan:
   - Kolom pencarian (nama atau NIS)
   - Filter berdasarkan status (Semua / Lengkap / Belum Lengkap)
6. Admin TU menemukan baris siswa yang bersangkutan
7. Siswa menunjukkan dokumen fisik kepada admin
8. Admin TU mencentang checkbox sesuai dokumen yang dibawa:
   ☐ Raport
   ☐ Kartu Keluarga (QR Code)
   ☐ Akte Kelahiran
   ☐ Ijazah
9. Sistem menyimpan perubahan secara real-time (atau saat tombol Simpan ditekan)
10. Jika semua 4 checkbox tercentang:
    → Status otomatis berubah menjadi "Lengkap" (badge hijau)
11. Jika ada yang belum:
    → Status tetap "Belum Lengkap" (badge merah/oranye)
12. Admin TU lanjut ke siswa berikutnya
```

### 7.4 Alur Monitoring Dashboard (Kepala Sekolah / Admin)

```
1. Kepala Sekolah / Admin login
2. Buka menu "Daftar Ulang" → "Dashboard"
3. Sistem menampilkan:
   a. Kartu statistik:
      - Total siswa daftar ulang
      - Jumlah yang sudah Lengkap
      - Jumlah yang Belum Lengkap
      - Persentase progress (progress bar)
   b. Tabel rekap per kelas
   c. Tabel detail per siswa dengan filter dan pencarian
4. Admin bisa filter berdasarkan:
   - Kelas (XI / XII)
   - Status (Lengkap / Belum Lengkap)
5. Data dashboard diperbarui secara real-time
```

---

## 8. Alur Alternatif & Edge Cases

| # | Skenario | Penanganan |
|---|----------|------------|
| EC-01 | Siswa datang sebelum periode dibuka | Sistem menolak input checklist, tampilkan info tanggal pembukaan |
| EC-02 | Siswa datang setelah periode ditutup | Sistem menolak input checklist, tampilkan pesan periode sudah berakhir |
| EC-03 | Admin salah mencentang dokumen | Admin bisa uncheck kapan saja selama periode aktif |
| EC-04 | Siswa belum terdaftar di sistem | Admin TU menambahkan data siswa terlebih dahulu |
| EC-05 | NIS duplikat saat input siswa baru | Sistem menampilkan error "NIS sudah terdaftar" |
| EC-06 | Koneksi internet terputus saat checklist | Tampilkan error, data belum tersimpan, minta retry |
| EC-07 | Admin mencoba akses periode yang tidak aktif | Dashboard tetap bisa diakses (view only), checklist dikunci |
| EC-08 | Tahun ajaran belum dikonfigurasi | Sistem menampilkan peringatan "Periode belum dikonfigurasi" |

---

## 9. Business Rules

| # | Rule |
|---|------|
| BR-01 | Hanya user dengan role `admin`, `operator`, atau `super_admin` yang dapat mengakses modul Daftar Ulang |
| BR-02 | Hanya `super_admin` yang dapat mengatur periode daftar ulang |
| BR-03 | Checklist hanya dapat dilakukan ketika periode daftar ulang sedang aktif (tanggal buka ≤ hari ini ≤ tanggal tutup) |
| BR-04 | Status "Lengkap" hanya diberikan jika SEMUA 4 dokumen sudah dicentang |
| BR-05 | Status "Belum Lengkap" adalah default untuk setiap siswa yang baru terdaftar |
| BR-06 | Admin TU dapat membatalkan centang selama periode masih aktif |
| BR-07 | Setelah periode ditutup, data checklist bersifat read-only |
| BR-08 | NIS harus unik per tahun ajaran |
| BR-09 | Kelas asal dan kelas tujuan harus konsisten (X→XI, XI→XII) |
| BR-10 | Periode kelas XI dan kelas XII dikonfigurasi secara terpisah |
| BR-11 | Dashboard (monitoring) dapat diakses kapan saja, terlepas dari status periode |

---

## 10. Data Model / Entity

### 10.1 Tabel: `daftar_ulang_periode`
Menyimpan konfigurasi periode daftar ulang per tahun ajaran.

| Field | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| `tahun_ajaran` | VARCHAR(20) | NOT NULL | Contoh: "2026/2027" |
| `kelas_target` | ENUM('XI','XII') | NOT NULL | Target kelas daftar ulang |
| `tanggal_buka` | DATE | NOT NULL | Tanggal mulai daftar ulang |
| `tanggal_tutup` | DATE | NOT NULL | Tanggal akhir daftar ulang |
| `is_active` | BOOLEAN | DEFAULT TRUE | Status aktif/non-aktif periode |
| `created_by` | BIGINT UNSIGNED | FK → users.id | Admin yang membuat |
| `created_at` | TIMESTAMP | NULLABLE | Timestamp dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | Timestamp diperbarui |

**Unique constraint:** (`tahun_ajaran`, `kelas_target`)

---

### 10.2 Tabel: `daftar_ulang_siswa`
Menyimpan data siswa yang mengikuti proses daftar ulang.

| Field | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| `periode_id` | BIGINT UNSIGNED | FK → daftar_ulang_periode.id | Relasi ke periode |
| `nis` | VARCHAR(20) | NOT NULL | Nomor Induk Siswa |
| `nama_lengkap` | VARCHAR(100) | NOT NULL | Nama lengkap siswa |
| `kelas_asal` | ENUM('X','XI') | NOT NULL | Kelas saat ini |
| `kelas_tujuan` | ENUM('XI','XII') | NOT NULL | Kelas yang dituju |
| `jurusan` | VARCHAR(50) | NULLABLE | Jurusan/program studi |
| `created_at` | TIMESTAMP | NULLABLE | Timestamp dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | Timestamp diperbarui |

**Unique constraint:** (`nis`, `periode_id`)

---

### 10.3 Tabel: `daftar_ulang_checklist`
Menyimpan status checklist dokumen per siswa.

| Field | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| `siswa_id` | BIGINT UNSIGNED | FK → daftar_ulang_siswa.id | Relasi ke siswa |
| `raport` | BOOLEAN | DEFAULT FALSE | Status dokumen Raport |
| `kartu_keluarga` | BOOLEAN | DEFAULT FALSE | Status dokumen KK (QR Code) |
| `akte_kelahiran` | BOOLEAN | DEFAULT FALSE | Status dokumen Akte Kelahiran |
| `ijazah` | BOOLEAN | DEFAULT FALSE | Status dokumen Ijazah |
| `status` | ENUM('lengkap','belum_lengkap') | DEFAULT 'belum_lengkap' | Status keseluruhan |
| `verified_by` | BIGINT UNSIGNED | FK → users.id, NULLABLE | Admin yang terakhir verifikasi |
| `verified_at` | TIMESTAMP | NULLABLE | Waktu verifikasi terakhir |
| `created_at` | TIMESTAMP | NULLABLE | Timestamp dibuat |
| `updated_at` | TIMESTAMP | NULLABLE | Timestamp diperbarui |

**Relasi:** One-to-one dengan `daftar_ulang_siswa`

---

### 10.4 Diagram Relasi (ERD Teks)

```
users (existing)
  └──────────────────────────────────────────┐
                                             │
daftar_ulang_periode                         │
  ├── id (PK)                                │
  ├── tahun_ajaran                           │
  ├── kelas_target                           │
  ├── tanggal_buka                           │
  ├── tanggal_tutup                          │
  ├── is_active                              │
  └── created_by ──────────────────────► users.id
        │
        │ (1:N)
        ▼
daftar_ulang_siswa
  ├── id (PK)
  ├── periode_id ──────────────────────► daftar_ulang_periode.id
  ├── nis
  ├── nama_lengkap
  ├── kelas_asal
  ├── kelas_tujuan
  └── jurusan
        │
        │ (1:1)
        ▼
daftar_ulang_checklist
  ├── id (PK)
  ├── siswa_id ───────────────────────► daftar_ulang_siswa.id
  ├── raport
  ├── kartu_keluarga
  ├── akte_kelahiran
  ├── ijazah
  ├── status
  ├── verified_by ─────────────────── ► users.id
  └── verified_at
```

---

## 11. UI/UX Requirements

### 11.1 Halaman: Daftar Ulang — Beranda (Admin TU)
- **URL:** `/admin/daftar-ulang`
- **Komponen:**
  - Header informasi periode aktif (Kelas XI: tgl buka - tgl tutup | Kelas XII: tgl buka - tgl tutup)
  - Tab navigasi: [Kelas XI] [Kelas XII]
  - Statistik cepat: total siswa, sudah lengkap, belum lengkap, persentase
  - Tabel daftar siswa dengan kolom: No, NIS, Nama, Raport, KK, Akte, Ijazah, Status
  - Setiap checkbox di tabel bisa langsung diklik
  - Kolom Status menampilkan badge: 🟢 Lengkap / 🔴 Belum Lengkap
  - Kolom pencarian (nama / NIS)
  - Filter dropdown: Status (Semua / Lengkap / Belum Lengkap)
  - Tombol "Tambah Siswa" (Admin/Super Admin)

### 11.2 Halaman: Dashboard Monitoring
- **URL:** `/admin/daftar-ulang/dashboard`
- **Komponen:**
  - Kartu statistik (4 kartu): Total Siswa, Sudah Lengkap, Belum Lengkap, Persentase
  - Progress bar visual per kelas (XI dan XII)
  - Tabel rekap per kelas: Kelas, Total, Lengkap, Belum, Persentase
  - Tabel detail siswa dengan filter & search
  - Info periode aktif

### 11.3 Halaman: Pengaturan Periode
- **URL:** `/admin/daftar-ulang/pengaturan`
- **Akses:** Super Admin only
- **Komponen:**
  - Form per kelas (XI dan XII):
    - Input Tahun Ajaran (contoh: 2026/2027)
    - Date picker: Tanggal Buka
    - Date picker: Tanggal Tutup
    - Toggle: Aktifkan Periode
  - Tombol "Simpan Pengaturan"
  - Tabel riwayat periode sebelumnya

### 11.4 Halaman: Tambah / Edit Siswa
- **URL:** `/admin/daftar-ulang/siswa/tambah` dan `/admin/daftar-ulang/siswa/{id}/edit`
- **Komponen:**
  - Input: NIS, Nama Lengkap
  - Dropdown: Kelas Asal (X / XI)
  - Dropdown: Kelas Tujuan (XI / XII) — auto-set berdasarkan kelas asal
  - Input: Jurusan (opsional)
  - Dropdown: Pilih Periode
  - Tombol Simpan & Batal

### 11.5 Desain Umum
- Mengikuti design system yang sudah ada di Mansaba (Tailwind CSS)
- Responsive (mobile-friendly) karena admin mungkin menggunakan tablet
- Feedback visual saat checkbox diklik (loading indicator kecil)
- Konfirmasi SweetAlert jika admin ingin menghapus siswa
- Toast notification untuk setiap aksi berhasil/gagal

---

## 12. API Endpoints (Laravel Routes)

| Method | Endpoint | Deskripsi | Role | Response |
|--------|----------|-----------|------|----------|
| GET | `/admin/daftar-ulang` | Halaman utama daftar ulang | Admin, Operator | View |
| GET | `/admin/daftar-ulang/dashboard` | Halaman dashboard monitoring | All roles | View |
| GET | `/admin/daftar-ulang/pengaturan` | Halaman pengaturan periode | Super Admin | View |
| POST | `/admin/daftar-ulang/periode` | Simpan konfigurasi periode | Super Admin | JSON |
| GET | `/admin/daftar-ulang/siswa` | List data siswa (JSON) | Admin, Operator | JSON |
| POST | `/admin/daftar-ulang/siswa` | Tambah siswa baru | Admin, Operator | JSON |
| PUT | `/admin/daftar-ulang/siswa/{id}` | Edit data siswa | Admin, Operator | JSON |
| DELETE | `/admin/daftar-ulang/siswa/{id}` | Hapus data siswa | Super Admin | JSON |
| PATCH | `/admin/daftar-ulang/checklist/{siswa_id}` | Update checklist dokumen siswa | Admin, Operator | JSON |
| GET | `/admin/daftar-ulang/rekap` | Data rekap untuk dashboard (JSON) | All roles | JSON |

### Contoh Request/Response: PATCH Checklist

**Request:**
```json
PATCH /admin/daftar-ulang/checklist/42
Content-Type: application/json
Authorization: Bearer {token}

{
  "raport": true,
  "kartu_keluarga": true,
  "akte_kelahiran": false,
  "ijazah": true
}
```

**Response Sukses:**
```json
{
  "success": true,
  "message": "Checklist berhasil diperbarui",
  "data": {
    "siswa_id": 42,
    "raport": true,
    "kartu_keluarga": true,
    "akte_kelahiran": false,
    "ijazah": true,
    "status": "belum_lengkap",
    "updated_at": "2026-07-08T08:30:00Z"
  }
}
```

---

## 13. Functional Requirements

| # | Requirement |
|---|-------------|
| FR-01 | Sistem HARUS menampilkan daftar siswa berdasarkan kelas (XI atau XII) |
| FR-02 | Sistem HARUS memiliki checkbox untuk setiap dokumen: Raport, KK, Akte, Ijazah |
| FR-03 | Sistem HARUS otomatis mengubah status ke "Lengkap" jika semua 4 dokumen dicentang |
| FR-04 | Sistem HARUS memungkinkan pembatalan centang (uncheck) selama periode aktif |
| FR-05 | Sistem HARUS mengunci checklist saat periode belum buka atau sudah tutup |
| FR-06 | Sistem HARUS menyediakan kolom pencarian berdasarkan nama dan NIS |
| FR-07 | Sistem HARUS menyediakan filter berdasarkan status (Semua / Lengkap / Belum Lengkap) |
| FR-08 | Sistem HARUS menampilkan dashboard dengan statistik: total, lengkap, belum, persentase |
| FR-09 | Sistem HARUS menyediakan form konfigurasi periode (tanggal buka & tutup) per kelas |
| FR-10 | Sistem HARUS menyimpan informasi user yang terakhir melakukan verifikasi (verified_by) |
| FR-11 | Sistem HARUS memvalidasi bahwa NIS tidak duplikat dalam satu periode |
| FR-12 | Sistem HARUS menampilkan info periode aktif di halaman utama daftar ulang |
| FR-13 | Sistem HARUS membatasi akses pengaturan periode hanya untuk Super Admin |
| FR-14 | Sistem HARUS menampilkan rekap per kelas di dashboard |
| FR-15 | Sistem HARUS menampilkan feedback (toast/alert) setiap kali aksi berhasil atau gagal |

---

## 14. Non-Functional Requirements

| Kategori | Requirement |
|----------|-------------|
| **Performa** | Halaman daftar siswa (200 siswa) harus dimuat dalam < 2 detik |
| **Performa** | Respons AJAX checklist update < 500ms |
| **Keamanan** | Semua endpoint diproteksi dengan autentikasi (Laravel Sanctum/session) |
| **Keamanan** | Validasi role di setiap endpoint (middleware) |
| **Keamanan** | Input divalidasi dan disanitasi di sisi server |
| **Keamanan** | CSRF protection aktif untuk semua form |
| **Usability** | Interface harus intuitif, dapat digunakan tanpa pelatihan teknis |
| **Usability** | Responsive design: berfungsi baik di desktop, tablet, dan mobile |
| **Kompatibilitas** | Mendukung browser: Chrome 90+, Firefox 88+, Edge 90+, Safari 14+ |
| **Reliabilitas** | Data tidak boleh hilang meski koneksi terputus saat proses input |
| **Maintainability** | Kode mengikuti Laravel conventions dan clean code principles |
| **Audit** | Setiap perubahan checklist menyimpan `verified_by` dan `verified_at` |

---

## 15. Error Handling

| Skenario Error | Pesan yang Ditampilkan | Penanganan |
|---------------|----------------------|------------|
| Periode belum dibuka | "Periode daftar ulang belum dibuka. Akan dibuka pada [tanggal]." | Checklist dikunci, pesan informatif |
| Periode sudah ditutup | "Periode daftar ulang sudah berakhir pada [tanggal]." | Checklist dikunci (read-only) |
| NIS duplikat | "NIS [xxx] sudah terdaftar pada periode ini." | Form tidak tersimpan, error inline |
| Data siswa tidak ditemukan | "Siswa tidak ditemukan. Silakan periksa kembali pencarian." | Empty state dengan ilustrasi |
| Koneksi gagal saat update | "Gagal menyimpan. Periksa koneksi internet Anda." | Toast error, retry button |
| Akses tidak diizinkan | "Anda tidak memiliki akses ke halaman ini." | Redirect ke dashboard dengan alert |
| Validasi form gagal | Pesan per field (contoh: "NIS wajib diisi") | Highlight field merah + pesan error |
| Server error (500) | "Terjadi kesalahan sistem. Silakan coba lagi." | Toast error, log ke sistem |

---

## 16. Dependencies

| # | Dependency | Tipe | Keterangan |
|---|-----------|------|------------|
| DEP-01 | Tabel `users` (existing) | Internal | Untuk autentikasi dan `created_by`, `verified_by` |
| DEP-02 | Role system existing | Internal | Role `super_admin`, `admin`, `operator` sudah ada |
| DEP-03 | Laravel Jetstream/Fortify | Framework | Auth system yang sudah ada |
| DEP-04 | Tailwind CSS | Frontend | Design system yang sudah dipakai |
| DEP-05 | Alpine.js | Frontend | Untuk interaktivitas checkbox (jika dipakai) |
| DEP-06 | SweetAlert2 | Frontend | Untuk konfirmasi aksi (sudah dipakai di project) |

---

## 17. Testing Requirements

| Tipe Test | Cakupan |
|-----------|---------|
| **Unit Test** | Model: kalkulasi status (lengkap/belum), validasi periode aktif |
| **Feature Test** | CRUD siswa, update checklist, konfigurasi periode |
| **Authorization Test** | Pastikan role yang salah tidak bisa akses endpoint tertentu |
| **Validation Test** | NIS duplikat, field wajib kosong, periode invalid |
| **Edge Case Test** | Checklist di luar periode, semua dokumen dicentang, unchecked ulang |
| **Manual Test** | UI/UX flow lengkap dari input siswa → checklist → dashboard |
| **Browser Test** | Chrome, Firefox, Edge, Safari |

---

## 18. Estimasi Effort

| Layer | Task | Estimasi |
|-------|------|----------|
| **Database** | Migration 3 tabel + seeder dummy | 3 jam |
| **Backend** | Model + Controller (CRUD siswa, checklist, periode) | 8 jam |
| **Backend** | Service class (logika status, validasi periode) | 3 jam |
| **Backend** | Middleware & policy (role-based access) | 2 jam |
| **Backend** | Routes & API response | 2 jam |
| **Frontend** | Halaman utama daftar ulang (tabel + checkbox) | 6 jam |
| **Frontend** | Dashboard monitoring | 4 jam |
| **Frontend** | Halaman pengaturan periode | 3 jam |
| **Frontend** | Form tambah/edit siswa | 2 jam |
| **Frontend** | Interaktivitas AJAX + feedback UI | 3 jam |
| **Testing** | Feature test + manual test | 4 jam |
| **Buffer** | Review, bugfix, integrasi | 4 jam |
| **TOTAL** | | **~44 jam** (~5-6 hari kerja) |

---

## 19. Timeline & Milestone

| Milestone | Task | Estimasi Waktu | Keterangan |
|-----------|------|----------------|------------|
| **M1: Database** | Migration + Seeder | Hari 1 | Kang Encep |
| **M2: Backend Core** | Model + Service + Controller | Hari 2-3 | Kang Bayu |
| **M3: Frontend** | Semua halaman UI | Hari 3-4 | Teh Ayu |
| **M4: Integrasi** | Koneksi Backend-Frontend (AJAX) | Hari 4-5 | Kang Dika / Kang Bayu |
| **M5: Testing** | Feature test + manual test | Hari 5-6 | Kang Asep |
| **M6: Review** | Code review + bugfix | Hari 6 | Kang Rian |

---

## 20. Risiko & Mitigasi

| # | Risiko | Probabilitas | Dampak | Risk Score | Mitigasi |
|---|--------|-------------|--------|------------|----------|
| R-01 | Data siswa tidak lengkap/akurat saat input awal | Tinggi | Tinggi | 🔴 9 | Buat form validasi ketat + panduan input untuk admin TU |
| R-02 | Admin salah mencentang dokumen | Sedang | Rendah | 🟡 4 | Sediakan fitur uncheck + audit log `verified_by` |
| R-03 | Periode tidak dikonfigurasi sebelum hari H | Sedang | Tinggi | 🟡 6 | Tampilkan warning di dashboard jika periode belum diset |
| R-04 | Performa lambat jika siswa > 500 | Rendah | Sedang | 🟢 3 | Implementasi pagination + indexing database |
| R-05 | Konflik akses jika 2 admin TU bekerja bersamaan | Rendah | Rendah | 🟢 2 | Last-write-wins + tampilkan `verified_by` dan waktu |
| R-06 | Admin tidak familiar dengan sistem baru | Sedang | Sedang | 🟡 4 | UI intuitif + tooltip bantuan pada setiap komponen |

---

## 21. Changelog

| Versi | Tanggal | Perubahan | Penulis |
|-------|---------|-----------|---------|
| 1.0 | 2026-07-07 | Dokumen PRD dibuat pertama kali | Sophia (PM) |

---

## 22. Persetujuan

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Manager | Sophia | ✅ Draft Selesai | 2026-07-07 |
| Stakeholder / User | Mas Lutfi | ✅ Approved | - |

---

*PRD ini adalah acuan resmi pengerjaan fitur Daftar Ulang Siswa Lama. Semua tim developer wajib merujuk dokumen ini sebelum memulai implementasi.*
