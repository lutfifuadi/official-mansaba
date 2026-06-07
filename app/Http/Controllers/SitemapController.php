<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\News;

class SitemapController extends Controller
{
    public function index()
    {
        $news = News::published()->latest()->get();
        $achievements = Achievement::latest()->get();
        $extracurriculars = Extracurricular::all();

        return response()->view('sitemap', compact('news', 'achievements', 'extracurriculars'))
            ->header('Content-Type', 'application/xml');
    }
}
