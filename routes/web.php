<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\ExtracurricularController as AdminExtracurricularController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\VisitStatController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\Admin\DaftarUlangController;
use App\Http\Controllers\Admin\DaftarUlangPeriodeController;
use App\Http\Controllers\Admin\DaftarUlangSiswaController;

// ===================== PUBLIC ROUTES =====================

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/berita', [PublicController::class, 'news'])->name('public.news');
Route::get('/berita/{slug}', [PublicController::class, 'newsDetail'])->name('public.news-detail');
Route::get('/galeri', [PublicController::class, 'galleries'])->name('public.galleries');
Route::get('/prestasi', [PublicController::class, 'achievements'])->name('public.achievements');
Route::get('/ekstrakurikuler', [PublicController::class, 'extracurriculars'])->name('public.extracurriculars');
Route::get('/ekstrakurikuler/{slug}', [PublicController::class, 'extracurricularDetail'])->name('public.extracurricular-detail');
Route::get('/profil', [PublicController::class, 'profile'])->name('public.profile');
Route::get('/layanan', [PublicController::class, 'services'])->name('public.services');
Route::get('/layanan/{slug}', [PublicController::class, 'serviceDetail'])->name('public.services.detail');

// ===================== ADMIN ROUTES (Jetstream / Fortify handles auth) =====================

Route::middleware(['auth:web'])->get('/user/profile', function () {
    return redirect()->route('admin.profile');
});

Route::middleware(['auth:web', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('news', AdminNewsController::class)->middleware('role:super_admin,admin,operator,editor');
    Route::resource('galleries', AdminGalleryController::class)->middleware('role:super_admin,admin');
    Route::resource('achievements', AdminAchievementController::class)->middleware('role:super_admin,admin,editor');
    Route::resource('extracurriculars', AdminExtracurricularController::class)->middleware('role:super_admin,admin');

    Route::get('/settings', [SettingController::class, 'index'])->middleware('role:super_admin,admin')->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->middleware('role:super_admin,admin')->name('settings.update');

    Route::get('/theme', [ThemeController::class, 'index'])->middleware('role:super_admin,admin')->name('theme.index');
    Route::put('/theme', [ThemeController::class, 'update'])->middleware('role:super_admin,admin')->name('theme.update');

    Route::resource('services', ServiceController::class)->middleware('role:super_admin,admin,operator');

    Route::resource('users', UserController::class)->middleware('role:super_admin');

    Route::get('/statistik', [VisitStatController::class, 'index'])->middleware('role:super_admin,admin,operator')->name('visits.index');
    Route::delete('/statistik/reset', [VisitStatController::class, 'reset'])->middleware('role:super_admin')->name('visits.reset');

    Route::resource('announcements', AnnouncementController::class)->middleware('role:super_admin,admin,operator');

    Route::delete('/galleries/image/{id}', [App\Http\Controllers\Admin\GalleryController::class, 'deleteImage'])->middleware('role:super_admin,admin')->name('galleries.image.delete');

    Route::post('/upload/image', [App\Http\Controllers\Admin\UploadController::class, 'image'])->middleware('role:super_admin,admin,operator,editor')->name('upload.image');

    Route::get('/menus', [App\Http\Controllers\Admin\MenuController::class, 'index'])->middleware('role:super_admin,admin')->name('menus.index');
    Route::post('/menus', [App\Http\Controllers\Admin\MenuController::class, 'store'])->middleware('role:super_admin,admin')->name('menus.store');

    // ===================== DAFTAR ULANG SISWA LAMA =====================
    // Halaman checklist, dashboard monitoring, import, reset, destroy, dan periode: role admin, operator, super_admin
    Route::middleware('role:super_admin,admin,operator')->group(function () {
        Route::get('/daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang.index');
        Route::get('/daftar-ulang/dashboard', [DaftarUlangController::class, 'dashboard'])->name('daftar-ulang.dashboard');
        Route::get('/daftar-ulang/stats', [DaftarUlangController::class, 'stats'])->name('daftar-ulang.stats');
        Route::post('/daftar-ulang/{siswa_id}/checklist', [DaftarUlangController::class, 'updateChecklist'])->name('daftar-ulang.update-checklist');
        
        // Import data siswa dari Excel
        Route::post('/daftar-ulang/import', [DaftarUlangSiswaController::class, 'import'])->name('daftar-ulang.import');
        
        // Siswa CRUD
        Route::resource('daftar-ulang-siswa', DaftarUlangSiswaController::class);

        Route::get('/daftar-ulang/periode', [DaftarUlangPeriodeController::class, 'index'])->name('daftar-ulang-periode.index');
        Route::post('/daftar-ulang/periode', [DaftarUlangPeriodeController::class, 'store'])->name('daftar-ulang-periode.store');
        Route::post('/daftar-ulang/reset', [DaftarUlangController::class, 'reset'])->name('daftar-ulang.reset');
    });
});

// ===================== SITEMAP =====================
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

// ===================== LOCALE ROUTE =====================

Route::get('/lang/{locale}', [LanguageController::class, 'swap'])->name('lang.swap');
