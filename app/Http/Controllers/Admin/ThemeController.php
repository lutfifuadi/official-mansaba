<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    /**
     * Menampilkan halaman pengaturan theme.
     */
    public function index()
    {
        $settings = Setting::all();

        // Ambil nilai dari database, fallback ke config default jika belum tersimpan
        $theme = [
            'theme_primary_color' => $settings->firstWhere('key', 'theme_primary_color')->value ?? config('custom.custom.primaryColor', '#1B5E42'),
            'theme_mode'          => $settings->firstWhere('key', 'theme_mode')->value ?? config('custom.custom.myTheme', 'light'),
            'theme_skin'          => $settings->firstWhere('key', 'theme_skin')->value ?? config('custom.custom.mySkins', 'mansaba'),
            'theme_semi_dark'     => $settings->firstWhere('key', 'theme_semi_dark')->value ?? '0',
        ];

        return view('content.admin.theme.index', compact('theme'));
    }

    /**
     * Menyimpan pengaturan theme ke database.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme_primary_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'theme_mode'          => 'required|string|in:light,dark,system',
            'theme_skin'          => 'required|string|in:default,bordered,mansaba',
            'theme_semi_dark'     => 'nullable|string|max:1|in:0,1',
        ]);

        // Simpan ke tabel settings (key-value)
        foreach ($validated as $key => $value) {
            if (!is_null($value)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        // Hapus cache settings.all agar nilai terbaru terbaca
        Cache::forget('settings.all');

        return redirect()->route('admin.theme.index')
            ->with('success', 'Pengaturan tema berhasil disimpan.');
    }
}
