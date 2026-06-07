<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
            if ($src !== null) {
                return [
                    'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
                ];
            }
            return [];
        });

        View::composer('layouts/*', function ($view) {
            $settings = Cache::remember('settings.all', 3600, function () {
                return Setting::pluck('value', 'key')->toArray();
            });
            $view->with('globalSettings', $settings);
        });

        View::share('catColors', [
            'Akademik' => 'primary',
            'Non-Akademik' => 'info',
            'Pengumuman' => 'warning',
            'Kegiatan' => 'success',
            'Prestasi' => 'danger',
            'Umum' => 'secondary',
        ]);

        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
