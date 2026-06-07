<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    protected array $publicPrefixes = ['/', '/berita', '/galeri', '/prestasi', '/ekstrakurikuler', '/profil'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            try {
                $this->recordView($request);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $response;
    }

    protected function shouldTrack(Request $request): bool
    {
        if ($request->isMethod('GET') && !$request->ajax() && !$request->expectsJson()) {
            $path = '/' . ltrim($request->path(), '/');
            if (str_starts_with($path, '/admin') || str_starts_with($path, '/auth') || str_starts_with($path, '/lang')) {
                return false;
            }
            foreach ($this->publicPrefixes as $prefix) {
                if ($path === $prefix || str_starts_with($path, $prefix)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function recordView(Request $request): void
    {
        $path = '/' . ltrim($request->path(), '/');
        $userAgent = $request->userAgent() ?? '';
        $pageType = $this->resolvePageType($path);
        $pageId = $this->resolvePageId($path, $pageType);

        PageView::create([
            'url' => $path,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'referer_url' => $request->header('referer'),
            'page_type' => $pageType,
            'page_id' => $pageId,
            'device_type' => $this->detectDevice($userAgent),
            'browser' => $this->detectBrowser($userAgent),
            'platform' => $this->detectPlatform($userAgent),
            'visited_at' => now(),
        ]);
    }

    protected function resolvePageType(string $path): ?string
    {
        if ($path === '/') return 'home';
        if (preg_match('#^/berita/(.+)#', $path)) return 'news-detail';
        if ($path === '/berita') return 'news';
        if (preg_match('#^/galeri#', $path)) return 'gallery';
        if (preg_match('#^/prestasi#', $path)) return 'achievement';
        if (preg_match('#^/ekstrakurikuler/(.+)#', $path)) return 'extracurricular-detail';
        if (preg_match('#^/ekstrakurikuler#', $path)) return 'extracurricular';
        if ($path === '/profil') return 'profile';
        return 'other';
    }

    protected function resolvePageId(string $path, ?string $pageType): ?int
    {
        if (in_array($pageType, ['news-detail', 'extracurricular-detail'])) {
            $slug = last(explode('/', $path));
            if ($slug) {
                $model = $pageType === 'news-detail'
                    ? \App\Models\News::class
                    : \App\Models\Extracurricular::class;
                $record = $model::where('slug', $slug)->first(['id']);
                return $record?->id;
            }
        }
        return null;
    }

    protected function detectDevice(string $userAgent): string
    {
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent)) {
            if (preg_match('/iPad|Tablet|PlayBook|Silk/i', $userAgent)) return 'tablet';
            return 'mobile';
        }
        return 'desktop';
    }

    protected function detectBrowser(string $userAgent): ?string
    {
        if (str_contains($userAgent, 'Edg/')) return 'Edge';
        if (str_contains($userAgent, 'Chrome/')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox/')) return 'Firefox';
        if (str_contains($userAgent, 'Safari/')) return 'Safari';
        if (str_contains($userAgent, 'OPR/') || str_contains($userAgent, 'Opera/')) return 'Opera';
        if (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident/')) return 'Internet Explorer';
        return 'Unknown';
    }

    protected function detectPlatform(string $userAgent): ?string
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac OS')) return 'macOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) return 'iOS';
        if (str_contains($userAgent, 'Chrome OS')) return 'ChromeOS';
        return 'Unknown';
    }
}
