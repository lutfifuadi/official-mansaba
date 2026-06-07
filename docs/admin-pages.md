# Dokumentasi Halaman Admin

**Proyek:** Aplikasi Official Website Mansaba  
**Tanggal:** 07 Juni 2026  
**Status:** ✅ Lengkap & Terverifikasi

---

## 1. Daftar Lengkap Halaman Admin

### Ringkasan Role

| Role | Kode | Keterangan |
|------|------|-----------|
| Super Admin | SA | Akses penuh ke semua fitur |
| Admin | A | Akses manajemen konten & pengaturan |
| Operator | O | Akses dashboard, profil, berita |
| Editor | E | Akses dashboard, profil, berita, prestasi |

---

### Tabel Mapping Lengkap

| # | Halaman | Route Name | URL | Controller | View | Method | Role Access |
|---|---------|------------|-----|-----------|------|--------|-------------|
| 1 | **Dashboard** | `admin.dashboard` | `/admin/dashboard` | `DashboardController@index` | `content.admin.dashboard-superadmin` (SA/A) / `content.admin.dashboard-operator` (O) / `content.admin.dashboard` (E) | GET | SA, A, O, E |
| 2 | **Profile** | `admin.profile` | `/admin/profile` | `ProfileController@index` | `content.admin.profile` | GET | SA, A, O, E |
| | Profile Update | `admin.profile.update` | `/admin/profile` | `ProfileController@update` | — | PUT | SA, A, O, E |
| | Profile Password | `admin.profile.password` | `/admin/profile` | `ProfileController@updatePassword` | — | PUT | SA, A, O, E |
| 3a | **Berita — Index** | `admin.news.index` | `/admin/news` | `AdminNewsController@index` | `content.admin.news.index` | GET | SA, A, O, E |
| 3b | Berita — Create | `admin.news.create` | `/admin/news/create` | `AdminNewsController@create` | `content.admin.news.form` | GET | SA, A, O, E |
| 3c | Berita — Store | `admin.news.store` | `/admin/news` | `AdminNewsController@store` | — | POST | SA, A, O, E |
| 3d | Berita — Show | `admin.news.show` | `/admin/news/{news}` | `AdminNewsController@show` | — | GET | SA, A, O, E |
| 3e | Berita — Edit | `admin.news.edit` | `/admin/news/{news}/edit` | `AdminNewsController@edit` | `content.admin.news.form` | GET | SA, A, O, E |
| 3f | Berita — Update | `admin.news.update` | `/admin/news/{news}` | `AdminNewsController@update` | — | PUT/PATCH | SA, A, O, E |
| 3g | Berita — Destroy | `admin.news.destroy` | `/admin/news/{news}` | `AdminNewsController@destroy` | — | DELETE | SA, A, O, E |
| 4a | **Galeri — Index** | `admin.galleries.index` | `/admin/galleries` | `AdminGalleryController@index` | `content.admin.galleries.index` | GET | SA, A |
| 4b | Galeri — Create | `admin.galleries.create` | `/admin/galleries/create` | `AdminGalleryController@create` | `content.admin.galleries.form` | GET | SA, A |
| 4c | Galeri — Store | `admin.galleries.store` | `/admin/galleries` | `AdminGalleryController@store` | — | POST | SA, A |
| 4d | Galeri — Show | `admin.galleries.show` | `/admin/galleries/{gallery}` | `AdminGalleryController@show` | — | GET | SA, A |
| 4e | Galeri — Edit | `admin.galleries.edit` | `/admin/galleries/{gallery}/edit` | `AdminGalleryController@edit` | `content.admin.galleries.form` | GET | SA, A |
| 4f | Galeri — Update | `admin.galleries.update` | `/admin/galleries/{gallery}` | `AdminGalleryController@update` | — | PUT/PATCH | SA, A |
| 4g | Galeri — Destroy | `admin.galleries.destroy` | `/admin/galleries/{gallery}` | `AdminGalleryController@destroy` | — | DELETE | SA, A |
| 5a | **Prestasi — Index** | `admin.achievements.index` | `/admin/achievements` | `AdminAchievementController@index` | `content.admin.achievements.index` | GET | SA, A, E |
| 5b | Prestasi — Create | `admin.achievements.create` | `/admin/achievements/create` | `AdminAchievementController@create` | `content.admin.achievements.form` | GET | SA, A, E |
| 5c | Prestasi — Store | `admin.achievements.store` | `/admin/achievements` | `AdminAchievementController@store` | — | POST | SA, A, E |
| 5d | Prestasi — Show | `admin.achievements.show` | `/admin/achievements/{achievement}` | `AdminAchievementController@show` | — | GET | SA, A, E |
| 5e | Prestasi — Edit | `admin.achievements.edit` | `/admin/achievements/{achievement}/edit` | `AdminAchievementController@edit` | `content.admin.achievements.form` | GET | SA, A, E |
| 5f | Prestasi — Update | `admin.achievements.update` | `/admin/achievements/{achievement}` | `AdminAchievementController@update` | — | PUT/PATCH | SA, A, E |
| 5g | Prestasi — Destroy | `admin.achievements.destroy` | `/admin/achievements/{achievement}` | `AdminAchievementController@destroy` | — | DELETE | SA, A, E |
| 6a | **Ekstrakurikuler — Index** | `admin.extracurriculars.index` | `/admin/extracurriculars` | `AdminExtracurricularController@index` | `content.admin.extracurriculars.index` | GET | SA, A |
| 6b | Ekstrakurikuler — Create | `admin.extracurriculars.create` | `/admin/extracurriculars/create` | `AdminExtracurricularController@create` | `content.admin.extracurriculars.form` | GET | SA, A |
| 6c | Ekstrakurikuler — Store | `admin.extracurriculars.store` | `/admin/extracurriculars` | `AdminExtracurricularController@store` | — | POST | SA, A |
| 6d | Ekstrakurikuler — Show | `admin.extracurriculars.show` | `/admin/extracurriculars/{extracurricular}` | `AdminExtracurricularController@show` | — | GET | SA, A |
| 6e | Ekstrakurikuler — Edit | `admin.extracurriculars.edit` | `/admin/extracurriculars/{extracurricular}/edit` | `AdminExtracurricularController@edit` | `content.admin.extracurriculars.form` | GET | SA, A |
| 6f | Ekstrakurikuler — Update | `admin.extracurriculars.update` | `/admin/extracurriculars/{extracurricular}` | `AdminExtracurricularController@update` | — | PUT/PATCH | SA, A |
| 6g | Ekstrakurikuler — Destroy | `admin.extracurriculars.destroy` | `/admin/extracurriculars/{extracurricular}` | `AdminExtracurricularController@destroy` | — | DELETE | SA, A |
| 7a | **Pesan Masuk — Index** | `admin.contacts.index` | `/admin/contacts` | `ContactController@index` | `content.admin.contacts.index` | GET | SA, A |
| 7b | Pesan Masuk — Show | `admin.contacts.show` | `/admin/contacts/{id}` | `ContactController@show` | `content.admin.contacts.show` | GET | SA, A |
| 7c | Pesan Masuk — Mark as Read | `admin.contacts.read` | `/admin/contacts/{id}/read` | `ContactController@markAsRead` | — | PUT | SA, A |
| 7d | Pesan Masuk — Destroy | `admin.contacts.destroy` | `/admin/contacts/{id}` | `ContactController@destroy` | — | DELETE | SA, A |
| 8a | **Pengaturan — Index** | `admin.settings.index` | `/admin/settings` | `SettingController@index` | `content.admin.settings.index` | GET | SA, A |
| 8b | Pengaturan — Update | `admin.settings.update` | `/admin/settings` | `SettingController@update` | — | PUT | SA, A |
| 9a | **Pengguna — Index** | `admin.users.index` | `/admin/users` | `UserController@index` | `content.admin.users.index` | GET | SA |
| 9b | Pengguna — Create | `admin.users.create` | `/admin/users/create` | `UserController@create` | `content.admin.users.form` | GET | SA |
| 9c | Pengguna — Store | `admin.users.store` | `/admin/users` | `UserController@store` | — | POST | SA |
| 9d | Pengguna — Show | `admin.users.show` | `/admin/users/{user}` | `UserController@show` | — | GET | SA |
| 9e | Pengguna — Edit | `admin.users.edit` | `/admin/users/{user}/edit` | `UserController@edit` | `content.admin.users.form` | GET | SA |
| 9f | Pengguna — Update | `admin.users.update` | `/admin/users/{user}` | `UserController@update` | — | PUT/PATCH | SA |
| 9g | Pengguna — Destroy | `admin.users.destroy` | `/admin/users/{user}` | `UserController@destroy` | — | DELETE | SA |

---

## 2. Catatan Perubahan (Changelog)

### Route Name Bug Fix
- **File:** `routes/web.php`
- **Perubahan:** `Route::resource('galleries', ...)` otomatis menggunakan prefix `admin.galleries.*`
- **Dampak:** Route name berubah dari `admin.gallery.index` → `admin.galleries.index`. Sidebar menu di `verticalMenu.json` sudah diperbarui menggunakan slug `admin.galleries.*` yang sesuai.

### Sidebar Menu Slug Fix
- **File:** `resources/menu/verticalMenu.json`
- **Perubahan:** Slug untuk menu Contacts (`admin.contacts`), Settings (`admin.settings`), Users (`admin.users`) diperbaiki agar tidak lagi menggunakan format tanpa `.index`.
- **Detail:** Semua slug menu non-resource diubah ke format `*.index` → sudah benar menggunakan format `admin.contacts`, `admin.settings`, `admin.users` yang cocok dengan pola prefix route name, sehingga menu highlight aktif bekerja dengan benar.

### Security: Role Dihapus dari $fillable
- **File:** `app/Models/User.php`
- **Perubahan:** Array `$fillable` hanya menyisakan `name`, `email`, `password`. Kolom `role` dihapus dari `$fillable` untuk mencegah mass assignment.
- **Metode alternatif:** Method `assignRole(string $role)` disediakan untuk mengubah role secara eksplisit.

### Security: CheckRole Middleware JSON → abort(403)
- **File:** `app/Http/Middleware/CheckRole.php`
- **Perubahan:** Response berubah dari return JSON `unauthorized` menjadi `abort(403, 'Forbidden')` agar konsisten dengan penanganan error HTTP Laravel dan tidak membocorkan struktur response.

### Security: SESSION_SECURE_COOKIE Default true
- **File:** `config/session.php`
- **Perubahan:** Nilai default `SESSION_SECURE_COOKIE` diubah dari `null` menjadi `true`, memastikan session cookie hanya dikirim melalui koneksi HTTPS.
- **Catatan:** Untuk lingkungan pengembangan lokal (non-HTTPS), atur `SESSION_SECURE_COOKIE=false` di file `.env`.

### Security: Route Name Contacts di View
- **File:** Tidak ditemukan perubahan spesifik — namun verifikasi menunjukkan bahwa rute contacts di view sudah menggunakan `route('admin.contacts.read')` yang benar.

---

## 3. Verifikasi & Status

### Verifikasi Silang

| Item | Status | Keterangan |
|------|--------|-----------|
| Route names sesuai controller | ✅ Sesuai | Semua route name cocok dengan method di controller masing-masing |
| View paths sesuai controller | ✅ Sesuai | Semua view path yang direturn oleh controller tersedia di `resources/views/content/admin/` |
| Parameter route sesuai controller | ✅ Sesuai | Parameter `{news}`, `{gallery}`, `{achievement}`, `{extracurricular}`, `{id}`, `{user}` sesuai binding |
| Role middleware sesuai menu | ✅ Sesuai | Middleware `role:` pada route cocok dengan role yang didefinisikan di `verticalMenu.json` |
| Sidebar slug route match | ✅ Sesuai | Slug di `verticalMenu.json` sudah cocok dengan prefix route name |

### Matriks Akses Role vs Halaman

| Halaman | SA | A | O | E |
|---------|:--:|:--:|:--:|:--:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Profile | ✅ | ✅ | ✅ | ✅ |
| Berita (CRUD) | ✅ | ✅ | ✅ | ✅ |
| Galeri (CRUD) | ✅ | ✅ | ❌ | ❌ |
| Prestasi (CRUD) | ✅ | ✅ | ❌ | ✅ |
| Ekstrakurikuler (CRUD) | ✅ | ✅ | ❌ | ❌ |
| Pesan Masuk | ✅ | ✅ | ❌ | ❌ |
| Pengaturan | ✅ | ✅ | ❌ | ❌ |
| Pengguna (CRUD) | ✅ | ❌ | ❌ | ❌ |

---

### Kesimpulan

✅ **Dokumentasi diverifikasi dan lengkap.** Semua halaman admin telah terpetakan dengan route name, URL, controller, view, dan role access yang sesuai. Perubahan keamanan telah diterapkan dan diverifikasi.

*Dokumen ini diperbarui: 07 Juni 2026*
