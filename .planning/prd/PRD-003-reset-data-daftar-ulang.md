# PRD-003: Reset Log Verifikasi Daftar Ulang

## 1. Tujuan Fitur
Menyediakan fitur untuk mereset seluruh data log verifikasi (checklist berkas) daftar ulang kelas XI & XII ke default awal (tidak lengkap/belum diverifikasi) tanpa menghapus data siswa yang terdaftar di database. Fitur ini berguna saat memulai tahun ajaran baru atau membersihkan data simulasi verifikasi sementara data siswa tetap dipertahankan utuh 100%.

## 2. Aturan Keamanan & Hak Akses
- Hanya user dengan role `super_admin` (`Auth::user()->isSuperAdmin() === true`) yang berhak melakukan reset log verifikasi ini.
- Untuk mencegah ketidaksengajaan, user harus melakukan konfirmasi dengan mengetik teks `"RESET DATA"` (huruf kapital) sebelum proses reset dijalankan.

## 3. Desain Endpoint & Controller
- **Route Method**: POST
- **Route Path**: `/admin/daftar-ulang/reset` (atau sesuai struktur routing admin)
- **Route Name**: `admin.daftar-ulang.reset` (mengikuti prefix/grup `admin.`)
- **Controller Method**: `DaftarUlangController@reset`
- **Operasi Database**:
  - Melakukan update massal pada query `DaftarUlangChecklist::query()->update(...)`.
  - Mengubah status berkas `raport`, `kartu_keluarga`, `akte_kelahiran`, dan `ijazah` menjadi `false`.
  - Mengubah `status` menjadi `'belum_lengkap'`.
  - Mengosongkan `verified_by` menjadi `null` dan `verified_at` menjadi `null`.

## 4. Antarmuka Pengguna (UI)
- Menampilkan tombol merah "Reset Data" pada header/bagian filter form daftar ulang.
- Tombol hanya terlihat jika user saat ini adalah `super_admin`.
- Interaksi tombol menggunakan prompt dialog JavaScript konfirmasi kata kunci `"RESET DATA"`.
- Menggunakan form tersembunyi ber-CSRF Token untuk melakukan POST request jika konfirmasi valid.
