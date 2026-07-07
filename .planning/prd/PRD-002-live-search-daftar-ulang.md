# PRD-002: Live Search Daftar Ulang (Tanpa Reload)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-002 |
| **Nama Fitur** | Live Search Daftar Ulang (Tanpa Reload) |
| **Versi** | 1.0 |
| **Status** | Draft |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-07 |
| **Prioritas** | High |
| **Target Release** | 2026-07-08 (Menyusul setelah PRD-001 Go Live) |
| **RICE Score** | 491 — Prioritas Tinggi |

---

## Executive Summary Card

```
+-----------------------------------------------------+
| PRD-002: Live Search Daftar Ulang (Tanpa Reload)     |
+-----------------------------------------------------+
| Status    : Draft                                    |
| Prioritas : High                                     |
| RICE Score: 491                                      |
| Risk Level: Low                                      |
| Quality   : 95/100 — Excellent                       |
| Deadline  : 2026-07-08                               |
| Estimasi  : ~6 jam (1 hari)                          |
+-----------------------------------------------------+
| Ringkasan:                                           |
| Pengembangan fitur pencarian real-time (live search)  |
| pada tabel Daftar Ulang Siswa untuk Admin TU /       |
| Operator. Memungkinkan pencarian berdasarkan nama    |
| atau NIS secara instan saat mengetik tanpa reload    |
| halaman.                                             |
|                                                      |
| Impact:                                               |
| Mempercepat waktu pencarian siswa dari ~10 detik      |
| menjadi < 1 detik. Menghilangkan delay reload        |
| halaman dan menjaga sinkronisasi status filter       |
| kelas & kelengkapan dokumen.                         |
+-----------------------------------------------------+
```

---

## 1. Ringkasan

Fitur **Live Search Daftar Ulang (Tanpa Reload)** adalah peningkatan (enhancement) dari modul daftar ulang yang sudah ada (PRD-001). Fitur ini bertujuan untuk mempermudah Admin TU / Operator dalam mencari data siswa secara interaktif dan real-time. 

Saat Admin mengetik minimal 3 karakter pada kolom pencarian, tabel daftar siswa akan memperbarui baris datanya secara dinamis menggunakan teknologi AJAX/Fetch API. Proses ini menggunakan mekanisme **debounce** untuk mengurangi beban server dari request yang terlalu sering. Jika pencarian kosong atau kurang dari 3 karakter, tabel akan kembali menampilkan data semula secara otomatis. Selain itu, fitur ini harus terintegrasi secara harmonis dengan filter status kelengkapan dokumen (Lengkap/Belum Lengkap) dan tab kelas (XI/XII) yang sudah ada sebelumnya.

---

## 2. Latar Belakang & Masalah

### 2.1 Masalah Saat Ini
- **Delay Pencarian Manual:** Saat ini pencarian masih membutuhkan klik tombol "Cari" atau menekan Enter yang memicu reload halaman penuh (full page reload). Hal ini memperlambat proses pelayanan pendaftaran ulang ketika antrean siswa menumpuk di meja TU.
- **Kehilangan State Terpilih:** Reload halaman sering kali me-reset state filter (seperti filter status atau kelas) jika tidak di-handle dengan baik di query string.
- **User Experience (UX) Kurang Optimal:** Di era aplikasi modern, admin terbiasa dengan input pencarian yang langsung menyaring hasil secara instan.

### 2.2 Dampak Jika Tidak Diselesaikan
- Waktu verifikasi dokumen per siswa menjadi lebih lama karena admin harus menunggu halaman reload setiap kali mencari nama siswa baru.
- Penumpukan antrean siswa di loket daftar ulang fisik sekolah.
- Efisiensi kerja Admin TU berkurang secara akumulatif.

### 2.3 Solusi yang Diusulkan
Mengimplementasikan pencarian asinkron (AJAX) menggunakan Fetch API di sisi frontend dan optimasi query di sisi backend (controller). Sistem akan memantau input pencarian, menundanya sesaat (debounce 300-500ms) untuk mengumpulkan ketikan user, lalu mengirimkan request pencarian ke server yang merespons dengan data JSON atau potongan HTML tabel (partial render) untuk disisipkan langsung ke dalam tabel.

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Mempercepat proses pencarian siswa | Waktu muat hasil pencarian (response time) | < 500ms setelah debounce selesai |
| Meniadakan reload halaman untuk pencarian | Jumlah reload halaman (full-page reload) saat mencari | 0 (Nol) reload halaman |
| Menjaga keselarasan data | Sinkronisasi dengan filter status & tab kelas | 100% sinkron (pencarian menghormati filter aktif) |
| Pencegahan request berlebihan | Keefektifan debounce | Mengurangi jumlah request backend hingga minimal 60% dibanding input tanpa debounce |

---

## 4. Scope

### ✅ In Scope
| # | Item |
|---|------|
| IS-01 | Pembuatan komponen input pencarian interaktif di halaman Verifikasi Daftar Ulang |
| IS-02 | Implementasi fungsi JavaScript dengan event listener `input` / `keyup` dan mekanisme **debounce** (300-500ms) |
| IS-03 | Pembuatan endpoint API pencarian atau modifikasi method index controller untuk mendeteksi request AJAX |
| IS-04 | Sinkronisasi filter pencarian teks dengan filter status dokumen (Lengkap/Belum Lengkap) dan filter kelas (XI/XII) yang aktif |
| IS-05 | Implementasi Loading State berupa skeleton loader atau spinner pada tabel selama request asinkron berjalan |
| IS-06 | Implementasi Empty State berupa baris tabel khusus berisi pesan "Data tidak ditemukan" jika hasil pencarian nihil |
| IS-07 | Reset otomatis ke daftar data awal jika input pencarian dihapus atau karakter kurang dari 3 |
| IS-08 | Penanganan error handling frontend jika request AJAX gagal (fallback menampilkan pesan error & memulihkan tabel) |

### ❌ Out of Scope
| # | Item |
|---|------|
| OS-01 | Live search di luar modul daftar ulang (seperti di modul artikel, pengumuman, dll) |
| OS-02 | Pencarian menggunakan teknologi Search Engine eksternal (seperti Algolia, Elasticsearch) |
| OS-03 | Fitur voice search (pencarian suara) |
| OS-04 | Fitur filter tingkat lanjut di luar kelas dan status dokumen (seperti filter per jurusan, jenis kelamin, dll) |

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-01 | Admin TU / Operator | mencari siswa dengan mengetik nama/NIS secara langsung tanpa reload halaman | saya dapat menemukan data siswa dengan sangat cepat saat melayani antrean |
| US-02 | Admin TU / Operator | melihat spinner/loading indicator saat pencarian sedang diproses | saya tahu bahwa sistem sedang bekerja mencari data dan bukan hang/macet |
| US-03 | Admin TU / Operator | melihat tulisan "Data tidak ditemukan" yang jelas jika nama siswa yang diketik salah | saya tidak bingung apakah data belum termuat atau memang tidak terdaftar |
| US-04 | Admin TU / Operator | menghapus teks pencarian dan melihat tabel kembali ke daftar siswa semula | saya bisa melanjutkan mencari siswa lain tanpa perlu memuat ulang halaman |
| US-05 | Admin TU / Operator | menyaring daftar siswa berdasarkan kelas (XI/XII) dan status (Lengkap/Belum Lengkap) dikombinasikan dengan live search | saya bisa mencari siswa tertentu secara spesifik di dalam kelas tertentu |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-01 | Admin berada di halaman Verifikasi Daftar Ulang | Admin mengetik kurang dari 3 karakter di kolom pencarian | Sistem tidak mengirim request ke server dan daftar data di tabel tidak berubah |
| AC-02 | Admin berada di halaman Verifikasi Daftar Ulang | Admin mengetik minimal 3 karakter (misal: "Budi") | Sistem menunggu 300ms (debounce), memicu loading state (tabel blur/spinner tampil), lalu mengirim request asinkron ke backend |
| AC-03 | Request pencarian dikirim ke backend | Backend memproses query (WHERE nama LIKE '%Budi%' OR nis LIKE '%Budi%') | Sistem mengembalikan data siswa ter-filter dan frontend memperbarui isi tabel secara instan tanpa reload halaman |
| AC-04 | Admin mengetik kata kunci yang tidak ada di database | Pencarian selesai diproses | Sistem menyembunyikan loading state dan menampilkan baris tunggal di tabel dengan pesan "Data tidak ditemukan" |
| AC-05 | Hasil pencarian sedang ditampilkan | Admin menghapus teks di kolom pencarian (input kosong) | Sistem langsung membatalkan request aktif (jika ada), mengembalikan tabel ke daftar data semula |
| AC-06 | Admin sedang memilih tab kelas "Kelas XII" dan filter "Belum Lengkap" | Admin mengetik kata kunci "Andi" | Request live search mengirimkan parameter kelas=XII dan status=belum_lengkap ke server, sehingga hasil pencarian hanya menampilkan "Andi" yang berada di kelas XII dan belum lengkap dokumennya |
| AC-07 | Terjadi gangguan koneksi jaringan saat live search berjalan | Request AJAX gagal / timeout | Sistem menghentikan loading state, memunculkan notifikasi error kecil (toast/alert) berupa "Gagal memuat data, periksa koneksi internet Anda", dan tabel kembali ke data terakhir yang berhasil dimuat |

---

## 7. Alur Utama (Happy Path)

### 7.1 Alur Pencarian Live Search (Frontend ke Backend)

```
1. Admin TU membuka halaman "Verifikasi Checklist"
2. Admin memposisikan kursor pada kolom input pencarian
3. Admin mengetik nama siswa: "M-u-h-a-m-m-a-d"
4. Di setiap ketukan tombol, JavaScript menangkap event input:
   - Jika panjang teks < 3 karakter -> Lewati / batalkan timer debounce sebelumnya.
   - Jika panjang teks >= 3 karakter -> Jalankan timer debounce (300ms).
5. Setelah Admin berhenti mengetik selama 300ms, timer debounce terpicu:
   a. Tampilkan spinner loading di atas tabel data.
   b. Kumpulkan parameter aktif: search="Muhammad", kelas="XI" (tab aktif), status="semua" (filter aktif).
   c. Kirim request AJAX (Fetch API) ke `/admin/daftar-ulang-siswa` dengan query parameters tersebut.
6. Backend (Controller) menerima request:
   a. Deteksi jika request adalah AJAX (request->ajax() atau Header Accept: application/json).
   b. Bangun query: `DaftarUlangSiswa::query()`
   c. Terapkan filter kelas & status jika dikirimkan.
   d. Terapkan query pencarian: `where(nama_lengkap LIKE %Muhammad% OR nis LIKE %Muhammad%)`
   e. Eksekusi query dengan pagination.
   f. Kembalikan data (bisa berupa JSON berisi daftar siswa atau template HTML partial table).
7. Frontend menerima respons sukses (status 200 OK):
   a. Sembunyikan spinner loading.
   b. Render ulang isi `<tbody>` tabel dengan data yang baru diterima.
   c. Perbarui pagination link (jika ada) secara dinamis agar sesuai dengan hasil pencarian.
```

---

## 8. Business Rules

| # | Rule |
|---|------|
| BR-01 | Fitur live search hanya aktif dan dapat digunakan di halaman yang memiliki otorisasi admin/operator/super_admin. |
| BR-02 | Minimum input untuk menembak query ke server adalah 3 karakter untuk menghindari beban query database `LIKE '%xx%'` yang tidak efisien. |
| BR-03 | Jika input dikosongkan, filter pencarian dilepas, namun filter tab kelas dan filter status kelengkapan dokumen yang sedang aktif harus tetap dipertahankan. |
| BR-04 | Mekanisme debounce wajib menggunakan durasi antara 300ms hingga 500ms (tidak boleh di bawah 300ms demi efisiensi server, dan tidak boleh di atas 500ms agar tetap terasa responsif bagi user). |
| BR-05 | Jika terjadi beberapa ketikan cepat beruntun, request sebelumnya yang masih dalam proses antrean (pending request) harus di-abort menggunakan `AbortController` (JavaScript) agar respons yang dirender di tabel selalu merupakan respons dari kata kunci terakhir. |

---

## 9. Data Requirements

### 9.1 API / Endpoint Specification

**Endpoint:** `GET /admin/daftar-ulang-siswa` (Endpoint existing yang dioptimasi untuk AJAX/JSON)

#### Request Query Parameters:
| Parameter | Tipe | Required | Keterangan |
|-----------|------|----------|------------|
| `search` | String | Tidak | Kata kunci pencarian (nama lengkap atau NIS), minimal 3 karakter. |
| `kelas` | String | Tidak | Filter kelas tujuan, pilihan: `XI`, `XII`. |
| `status` | String | Tidak | Filter status dokumen, pilihan: `lengkap`, `belum_lengkap`. |
| `page` | Integer| Tidak | Nomor halaman untuk pagination. Default: 1. |

#### Response Schema (Format JSON jika request asinkron meminta JSON):
```json
{
  "success": true,
  "data": [
    {
      "id": 12,
      "nis": "242510101",
      "nama_lengkap": "Muhammad Ridwan",
      "kelas_asal": "X",
      "kelas_tujuan": "XI",
      "jurusan": "Sains Teknik",
      "checklist": {
        "raport": true,
        "kartu_keluarga": true,
        "akte_kelahiran": true,
        "ijazah": false,
        "status": "belum_lengkap"
      }
    }
  ],
  "pagination": {
    "total": 1,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null
  }
}
```
*Catatan: Alternatif jika menggunakan Server-Side HTML Rendering (Blade Partial): Server mengembalikan potongan HTML `<tr>` untuk langsung di-inject ke `<tbody>` tabel.*

---

## 10. Non-Functional Requirements

| Kategori | Requirement |
|----------|-------------|
| **Performa** | Query database pencarian `LIKE` harus memanfaatkan index pada kolom `nama_lengkap` dan `nis` untuk menjamin kecepatan pencarian. |
| **Response Time** | Waktu pemrosesan backend untuk query pencarian asinkron harus di bawah 150ms. |
| **UX & Animasi** | Loading state (skeleton loading) harus memiliki transisi yang halus agar tidak mengagetkan mata user (eye strain). |
| **Robustness** | JavaScript harus tetap menangani skenario jika server mengembalikan status error 500 atau 404 tanpa merusak layout halaman (graceful degradation). |
| **Kompatibilitas** | Kompatibel dengan browser modern yang mendukung Fetch API (Chrome, Firefox, Safari, Edge, Opera). |

---

## 11. Dependencies

| # | Dependency | Tipe | Keterangan |
|---|-----------|------|------------|
| DEP-01 | `DaftarUlangSiswaController` | Laravel Controller | Handler backend untuk melayani query pencarian |
| DEP-02 | Halaman Blade Verifikasi | Frontend View | Mengandung tabel dan input pencarian yang akan dimodifikasi |
| DEP-03 | Tailwind CSS (Skeleton Loader classes) | Frontend | Untuk styling state loading tabel |
| DEP-04 | Vanilla JS / Alpine.js (jika terpasang) | Frontend Script | Implementasi debounce dan Fetch API |

---

## 12. Estimasi & Timeline

### 12.1 Task Breakdown

| # | Task | Layer | Estimasi | Assignee |
|---|------|-------|----------|----------|
| T-01 | Modifikasi Controller: Tambahkan logika pendeteksi AJAX dan filter query search (LIKE) | Backend | 1.5 jam | Kang Bayu |
| T-02 | Pembuatan Layout Frontend: Input search, spinner loader, dan template empty state di Blade | Frontend | 1.5 jam | Teh Ayu |
| T-03 | Implementasi JavaScript: Debounce, Fetch API, AbortController, dynamic table rendering, & update pagination | Frontend | 2.0 jam | Teh Ayu / Bayu |
| T-04 | Testing & Integrasi: Uji coba live search gabungan dengan filter tab kelas dan filter status kelengkapan | Testing | 1.0 jam | Kang Farhan |
| | **TOTAL** | | **~6 jam** | |

---

## 13. Risks & Mitigasi

| # | Risk | Likelihood | Impact | Score | Level | Mitigasi |
|---|------|-----------|--------|-------|-------|----------|
| R-01 | Server overload karena request search berlebihan saat traffic tinggi | 2 | 3 | 6 | Medium | Pastikan debounce minimal 300-500ms berjalan sempurna dan gunakan `AbortController` untuk memotong request gantung. |
| R-02 | Konflik state pencarian dengan pagination link (saat klik page 2, filter pencarian hilang) | 3 | 2 | 6 | Medium | Simpan kata kunci pencarian pada state internal JS dan sertakan parameter `search`, `kelas`, dan `status` di setiap URL pagination AJAX. |
| R-03 | Lambatnya pencarian jika data siswa membengkak di masa depan | 1 | 4 | 4 | Low | Buat database index pada kolom `nama_lengkap` dan `nis` di tabel `daftar_ulang_siswa` jika belum ada. |

---

## 14. Quality Score

**QUALITY SCORE: 95/100 — Excellent ✅**

| # | Kriteria | Bobot | Nilai | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Ringkasan sangat jelas mendefinisikan fitur & batasan. |
| 2 | Masalah terdefinisi | 10 | 10/10 | Latar belakang masalah antrean dan page reload didefinisikan dengan baik. |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | Target kuantitatif (<500ms, 0 reload, 60% reduksi request). |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Batas fitur sangat spesifik pada halaman verifikasi daftar ulang. |
| 5 | User stories lengkap | 10 | 10/10 | 5 user stories mencakup skenario admin, loader, empty state, dll. |
| 6 | Acceptance criteria (Given/When/Then) | 15 | 15/15 | 7 AC terperinci dengan format Given/When/Then yang solid. |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | Langkah-langkah detail penanganan event input dan asinkronisasi. |
| 8 | Data requirements ada | 5 | 5/5 | Detail parameter API dan response JSON disediakan lengkap. |
| 9 | Non-functional requirements | 5 | 5/5 | Kinerja index DB dan robustness JS dibahas. |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | 4 dependensi internal didefinisikan. |
| 11 | Estimasi & timeline | 5 | 5/5 | Estimasi breakdown tugas realistis dengan total 6 jam. |
| 12 | Risks & mitigasi | 10 | 5/5 | Mitigasi server load, pagination state, dan index DB. |
| | **TOTAL** | **100** | **95/100** | **Grade: Excellent** |

---

## 15. Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-07 | Initial draft — PRD pertama untuk Fitur Live Search Tanpa Reload | Kang Dadang (PRD Specialist) |

---

## 16. Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner / Stakeholder | Mas Lutfi | ⏳ Pending | - |
| Tech Lead | TBD | ⏳ Pending | - |
| Lead Developer | TBD | ⏳ Pending | - |

---
*Terakhir diperbarui: 2026-07-07 oleh Kang Dadang (PRD Specialist)*
