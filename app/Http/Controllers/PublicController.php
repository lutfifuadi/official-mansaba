<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\Announcement;
use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $berita = News::published()->latest()->take(3)->get();
        $prestasi = Achievement::latest()->take(3)->get();
        $extracurriculars = Extracurricular::all();
        $galleries = Gallery::with('images')->latest()->take(6)->get();
        $settings = Cache::remember('settings.all', 3600, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
        $announcements = Announcement::active()->latest('published_at')->take(3)->get();

        $foundedYear = (int)($settings['founded_year'] ?? 1990);
        $stats = [
            'student_count' => $settings['student_count'] ?? '1.248',
            'teacher_count' => $settings['teacher_count'] ?? '84',
            'founded_year'  => $foundedYear,
            'years_active'  => now()->year - $foundedYear,
            'achievement_count' => Achievement::count() ?: '200',
        ];

        return view('content.public.home', [
            'news' => $berita,
            'achievements' => $prestasi,
            'extracurriculars' => $extracurriculars,
            'galleries' => $galleries,
            'settings' => $settings,
            'stats' => $stats,
            'announcements' => $announcements,
        ]);
    }

    public function news()
    {
        $news = News::published()->latest()->paginate(9);
        return view('content.public.news', compact('news'));
    }

    public function newsDetail($slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();
        $beritaLainnya = News::published()->where('id', '!=', $news->id)->latest()->take(4)->get();

        $pageConfigs = [
            'meta_description' => Str::limit(strip_tags($news->content ?? ''), 160),
            'meta_image' => $news->image ? Storage::url($news->image) : '',
            'canonical' => route('public.news-detail', $slug),
        ];

        return view('content.public.news-detail', compact('news', 'beritaLainnya', 'pageConfigs'));
    }

    public function galleries()
    {
        $galleries = Gallery::with('images')->latest()->paginate(12);
        $categories = Gallery::select('category')->distinct()->pluck('category');
        return view('content.public.galleries', compact('galleries', 'categories'));
    }

    public function achievements()
    {
        $achievements = Achievement::latest()->paginate(12);
        $levels = Achievement::select('level')->distinct()->pluck('level');
        return view('content.public.achievements', compact('achievements', 'levels'));
    }

    public function extracurriculars()
    {
        $extracurriculars = Extracurricular::all();
        return view('content.public.extracurriculars', compact('extracurriculars'));
    }

    public function extracurricularDetail($slug)
    {
        $extracurricular = Extracurricular::where('slug', $slug)->firstOrFail();
        return view('content.public.extracurricular-detail', [
            'ekskul' => $extracurricular,
            'extracurricular' => $extracurricular
        ]);
    }

    public function profile()
    {
        return view('content.public.profile');
    }
}
