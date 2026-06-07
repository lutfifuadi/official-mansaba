# Changelog

> Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- Dokumentasi halaman admin lengkap di `docs/admin-pages.md`
- Dokumentasi changelog di `docs/changelog.md`

### Fixed
- **Route name bug fix:** Resource route `galleries` sekarang menggunakan prefix `admin.galleries.*` (perbaikan dari `admin.gallery.*`)
- **Sidebar menu slug fix:** Slug menu Contacts, Settings, dan Users di `resources/menu/verticalMenu.json` diperbaiki agar sesuai dengan route name prefix
- **Security: Mass assignment protection —** Kolom `role` dihapus dari `$fillable` pada model `User` untuk mencegah mass assignment tidak sah
- **Security: CheckRole middleware —** Response diubah dari JSON unauthorized menjadi `abort(403)` untuk konsistensi dan keamanan
- **Security: Session cookie secure —** Default `SESSION_SECURE_COOKIE` diubah menjadi `true` di `config/session.php`
- **Security: Route name contacts —** Route name di view contacts diperbaiki menggunakan `admin.contacts.read` yang benar
- **Pengalihan Rute Profil:** Mengalihkan `/user/profile` (Jetstream default) ke rute `/admin/profile` (kustom dengan tema Bootstrap 5) karena halaman default Jetstream tidak memiliki style Tailwind yang sesuai. Menambahkan test case otomatis untuk memverifikasi fungsionalitas pengalihan.

### Changed
- Tidak ada perubahan destruktif pada rute atau API yang ada
- Mengubah tautan profil di navigasi (`navbar-partial.blade.php`) agar langsung mengarah ke `admin.profile`.

### Security
- Role assignment hanya dapat dilakukan melalui method eksplisit `assignRole()` — tidak melalui mass assignment
- Middleware CheckRole menggunakan Laravel `abort(403)` daripada respons JSON kustom
- Session cookie secure diaktifkan secara default untuk koneksi HTTPS
