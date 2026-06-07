<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\ExtracurricularController as AdminExtracurricularController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\VisitStatController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\language\LanguageController;

// ===================== PUBLIC ROUTES =====================

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/berita', [PublicController::class, 'news'])->name('public.news');
Route::get('/berita/{slug}', [PublicController::class, 'newsDetail'])->name('public.news-detail');
Route::get('/galeri', [PublicController::class, 'galleries'])->name('public.galleries');
Route::get('/prestasi', [PublicController::class, 'achievements'])->name('public.achievements');
Route::get('/ekstrakurikuler', [PublicController::class, 'extracurriculars'])->name('public.extracurriculars');
Route::get('/ekstrakurikuler/{slug}', [PublicController::class, 'extracurricularDetail'])->name('public.extracurricular-detail');
Route::get('/profil', [PublicController::class, 'profile'])->name('public.profile');

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

    Route::resource('users', UserController::class)->middleware('role:super_admin');

    Route::get('/statistik', [VisitStatController::class, 'index'])->middleware('role:super_admin,admin,operator')->name('visits.index');
    Route::delete('/statistik/reset', [VisitStatController::class, 'reset'])->middleware('role:super_admin')->name('visits.reset');

    Route::resource('announcements', AnnouncementController::class)->middleware('role:super_admin,admin,operator');

    Route::delete('/galleries/image/{id}', [App\Http\Controllers\Admin\GalleryController::class, 'deleteImage'])->middleware('role:super_admin,admin')->name('galleries.image.delete');

    Route::post('/upload/image', [App\Http\Controllers\Admin\UploadController::class, 'image'])->middleware('role:super_admin,admin,operator,editor')->name('upload.image');
});

// ===================== SITEMAP =====================
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

// ===================== LOCALE ROUTE =====================

Route::get('/lang/{locale}', [LanguageController::class, 'swap'])->name('lang.swap');
