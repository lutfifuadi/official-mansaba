<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    public function index()
    {
        $setting = Setting::where('key', 'nav_menu')->first();
        $items = $setting ? json_decode($setting->value, true) : [];
        if (!is_array($items)) {
            $items = [];
        }
        return view('content.admin.menus.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:255',
            'items.*.url' => 'nullable|string|max:500',
            'items.*.path' => 'nullable|string|max:255',
            'items.*.icon' => 'nullable|string|max:100',
        ]);

        $items = [];

        foreach ($request->items as $item) {
            if (empty(trim($item['label'] ?? ''))) {
                continue;
            }
            $label = trim($item['label']);
            $path = !empty($item['path']) ? trim($item['path']) : $this->slugify($label);
            $url = !empty($item['url']) ? trim($item['url']) : '/' . $path;

            $items[] = [
                'label' => $label,
                'url' => $url,
                'path' => $path,
                'icon' => !empty($item['icon']) ? trim($item['icon']) : '',
            ];
        }

        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal satu item menu harus diisi.',
            ], 422);
        }

        Setting::updateOrCreate(
            ['key' => 'nav_menu'],
            ['value' => json_encode($items)]
        );

        Cache::forget('settings.all');

        return response()->json([
            'success' => true,
            'message' => 'Menu navigasi berhasil disimpan.',
            'items' => $items,
        ]);
    }

    private function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = str_replace(['&', '@'], ['dan', 'at'], $text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}
