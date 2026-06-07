<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $excludedPaths = ['admin', 'login', 'register', 'forgot-password', 'reset-password', 'lang', 'sitemap'];

        foreach ($excludedPaths as $path) {
            if ($request->is($path) || $request->is($path . '/*')) {
                return $next($request);
            }
        }

        if ($request->user() && $request->user()->hasRole(['super_admin', 'admin'])) {
            return $next($request);
        }

        $settings = Setting::all()->keyBy('key');

        $comingSoonMode = $settings->get('coming_soon_mode')?->value ?? '0';
        $maintenanceMode = $settings->get('maintenance_mode')?->value ?? '0';

        if ($comingSoonMode === '1') {
            return response()->view('public.coming-soon');
        }

        if ($maintenanceMode === '1') {
            return response()->view('public.maintenance');
        }

        return $next($request);
    }
}
