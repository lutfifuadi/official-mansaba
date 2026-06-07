<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitStatController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30days');

        $dateFrom = match ($period) {
            'today' => now()->startOfDay(),
            '7days' => now()->subDays(7)->startOfDay(),
            '90days' => now()->subDays(90)->startOfDay(),
            '1year' => now()->subYear()->startOfDay(),
            default => now()->subDays(30)->startOfDay(),
        };

        $baseQuery = PageView::where('visited_at', '>=', $dateFrom);

        $totalVisits = (clone $baseQuery)->count();
        $uniqueVisitors = (clone $baseQuery)->distinct('session_id')->count('session_id');
        $todayVisits = PageView::whereDate('visited_at', today())->count();
        $todayUnique = PageView::whereDate('visited_at', today())->distinct('session_id')->count('session_id');

        $totalAllTime = PageView::count();
        $uniqueAllTime = PageView::distinct('session_id')->count('session_id');

        $visitsByPageType = (clone $baseQuery)
            ->select('page_type', DB::raw('count(*) as total'))
            ->whereNotNull('page_type')
            ->groupBy('page_type')
            ->orderByDesc('total')
            ->pluck('total', 'page_type');

        $dailyVisits = (clone $baseQuery)
            ->select(DB::raw('DATE(visited_at) as date'), DB::raw('count(*) as total'), DB::raw('COUNT(DISTINCT session_id) as unique_count'))
            ->groupBy(DB::raw('DATE(visited_at)'))
            ->orderBy('date')
            ->get();

        $mostVisitedPages = (clone $baseQuery)
            ->select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $deviceBreakdown = (clone $baseQuery)
            ->select('device_type', DB::raw('count(*) as total'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        $browserBreakdown = (clone $baseQuery)
            ->select('browser', DB::raw('count(*) as total'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->pluck('total', 'browser');

        $platformBreakdown = (clone $baseQuery)
            ->select('platform', DB::raw('count(*) as total'))
            ->whereNotNull('platform')
            ->groupBy('platform')
            ->orderByDesc('total')
            ->pluck('total', 'platform');

        $hourlyTraffic = (clone $baseQuery)
            ->select(DB::raw('HOUR(visited_at) as hour'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('HOUR(visited_at)'))
            ->orderBy('hour')
            ->pluck('total', 'hour');

        $refererData = (clone $baseQuery)
            ->select('referer_url', DB::raw('count(*) as total'))
            ->whereNotNull('referer_url')
            ->where('referer_url', '!=', '')
            ->groupBy('referer_url')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $recentVisits = (clone $baseQuery)
            ->orderByDesc('visited_at')
            ->paginate(10)
            ->appends(request()->query());

        $peakDay = $dailyVisits->sortByDesc('total')->first();
        $avgDaily = $totalVisits > 0 && $dailyVisits->count() > 0
            ? round($totalVisits / $dailyVisits->count(), 1)
            : 0;

        return view('content.admin.visits.index', compact(
            'totalVisits',
            'uniqueVisitors',
            'todayVisits',
            'todayUnique',
            'totalAllTime',
            'uniqueAllTime',
            'visitsByPageType',
            'dailyVisits',
            'mostVisitedPages',
            'deviceBreakdown',
            'browserBreakdown',
            'platformBreakdown',
            'hourlyTraffic',
            'refererData',
            'recentVisits',
            'period',
            'peakDay',
            'avgDaily',
        ));
    }

    public function reset(Request $request)
    {
        PageView::truncate();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.visits.index')->with('success', 'Semua data statistik kunjungan berhasil direset.');
    }
}
