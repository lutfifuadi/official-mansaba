<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        if (in_array($role, ['super_admin', 'admin'])) {
            return $this->analyticsDashboard($role);
        }

        if ($role === 'operator') {
            return $this->operatorDashboard($role);
        }

        return $this->simpleDashboard($role);
    }

    private function analyticsDashboard($role)
    {
        $totalBerita = News::count();
        $totalGaleri = Gallery::count();
        $totalPrestasi = Achievement::count();
        $totalEkskul = Extracurricular::count();
        $totalPengguna = User::count();

        $beritaPublished = News::where('is_published', true)->count();
        $galleryPublished = $totalGaleri;

        $totalPublished = $beritaPublished + $totalGaleri + $totalPrestasi + $totalEkskul;
        $totalDraft = ($totalBerita - $beritaPublished);
        $totalAll = $totalPublished + $totalDraft;
        $totalPublishedPercent = $totalAll > 0 ? round($totalPublished / $totalAll * 100) : 0;

        $weeklyPublish = [];
        $weeklyBreakdown = ['news' => 0, 'gallery' => 0, 'achievement' => 0];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayCount = News::where('is_published', true)->whereDate('published_at', $date)->count();
            $weeklyPublish[] = $dayCount;
            if ($i < 1) {
                $weeklyBreakdown['news'] += $dayCount;
                $weeklyBreakdown['gallery'] += Gallery::whereDate('created_at', $date)->count();
                $weeklyBreakdown['achievement'] += Achievement::whereDate('created_at', $date)->count();
            }
        }

        $beritaTerbaru = News::latest()->take(5)->get();

        $userRoleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $recentActivities = collect()
            ->merge(News::latest()->take(3)->get()->map(fn($item) => [
                'title' => 'Berita: ' . $item->title,
                'icon' => 'tabler-news',
                'color' => 'primary',
                'time' => $item->created_at->diffForHumans(),
                'sort' => $item->created_at->timestamp,
            ]))
            ->sortByDesc('sort')
            ->take(6)
            ->values();

        $visitToday = PageView::whereDate('visited_at', today())->count();
        $visitTodayUnique = PageView::whereDate('visited_at', today())->distinct('session_id')->count('session_id');
        $visitYesterday = PageView::whereDate('visited_at', today()->subDay())->count();
        $visitWeek = PageView::where('visited_at', '>=', now()->startOfWeek())->count();
        $visitTotal = PageView::count();
        $visitTotalUnique = PageView::distinct('session_id')->count('session_id');
        $visitOnline = PageView::where('visited_at', '>=', now()->subMinutes(5))->distinct('session_id')->count('session_id');
        $visitByDevice = PageView::whereDate('visited_at', '>=', now()->startOfWeek())
            ->select('device_type', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->pluck('total', 'device_type');
        $visitTopPage = PageView::select('url', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->first();

        return view('content.admin.dashboard-superadmin', compact(
            'role',
            'totalBerita', 'totalGaleri', 'totalPrestasi', 'totalEkskul',
            'totalPengguna',
            'totalPublished', 'totalDraft', 'totalPublishedPercent',
            'beritaPublished', 'galleryPublished',
            'weeklyPublish', 'weeklyBreakdown',
            'beritaTerbaru',
            'userRoleCounts', 'recentActivities',
            'visitToday', 'visitTodayUnique', 'visitYesterday', 'visitWeek', 'visitTotal', 'visitTotalUnique',
            'visitOnline',
            'visitByDevice', 'visitTopPage',
        ));
    }

    private function operatorDashboard($role)
    {
        $totalBerita = News::count();
        $beritaPublished = News::where('is_published', true)->count();
        $beritaDraft = $totalBerita - $beritaPublished;
        $publishedPercent = $totalBerita > 0 ? round($beritaPublished / $totalBerita * 100) : 0;

        $totalPrestasi = Achievement::count();
        $totalGaleri = Gallery::count();
        $totalEkskul = Extracurricular::count();

        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D');
            $weeklyData[] = (int) News::where('is_published', true)
                ->whereDate('published_at', $date->format('Y-m-d'))
                ->count();
        }

        $newsByCategory = News::where('is_published', true)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $beritaTerbaru = News::latest()->take(5)->get();

        $draftNews = News::where('is_published', false)
            ->orWhereNull('is_published')
            ->latest()
            ->take(5)
            ->get();

        $visitToday = PageView::whereDate('visited_at', today())->count();
        $visitWeek = PageView::where('visited_at', '>=', now()->startOfWeek())->count();
        $visitTotal = PageView::count();

        return view('content.admin.dashboard-operator', compact(
            'role',
            'totalBerita', 'beritaPublished', 'beritaDraft', 'publishedPercent',
            'totalPrestasi', 'totalGaleri', 'totalEkskul',
            'weeklyLabels', 'weeklyData',
            'newsByCategory',
            'beritaTerbaru', 'draftNews',
            'visitToday', 'visitWeek', 'visitTotal',
        ));
    }

    private function simpleDashboard($role)
    {
        $totalBerita = News::count();
        $beritaTerbaru = News::latest()->take(5)->get();

        $data = compact('role', 'totalBerita', 'beritaTerbaru');

        if (in_array($role, ['super_admin', 'admin', 'editor'])) {
            $data['totalPrestasi'] = Achievement::count();
        }

        if (in_array($role, ['super_admin', 'admin'])) {
            $data['totalGaleri'] = Gallery::count();
            $data['totalEkskul'] = Extracurricular::count();
        }

        if ($role === 'super_admin') {
            $data['totalPengguna'] = User::count();
        }

        $data['visitToday'] = PageView::whereDate('visited_at', today())->count();
        $data['visitTodayUnique'] = PageView::whereDate('visited_at', today())->distinct('session_id')->count('session_id');
        $data['visitYesterday'] = PageView::whereDate('visited_at', today()->subDay())->count();
        $data['visitWeek'] = PageView::where('visited_at', '>=', now()->startOfWeek())->count();
        $data['visitTotal'] = PageView::count();
        $data['visitTotalUnique'] = PageView::distinct('session_id')->count('session_id');
        $data['visitOnline'] = PageView::where('visited_at', '>=', now()->subMinutes(5))->distinct('session_id')->count('session_id');
        $data['visitByDevice'] = PageView::whereDate('visited_at', '>=', now()->startOfWeek())
            ->select('device_type', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->pluck('total', 'device_type');
        $data['visitTopPage'] = PageView::select('url', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->first();

        return view('content.admin.dashboard', $data);
    }
}
