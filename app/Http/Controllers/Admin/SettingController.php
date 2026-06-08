<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('content.admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'app_name' => 'nullable|string|max:255',
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operational_hours' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'headmaster_name' => 'nullable|string|max:255',
            'headmaster_message' => 'nullable|string',
            'headmaster_label' => 'nullable|string|max:255',
            'headmaster_title' => 'nullable|string|max:255',
            'announcement' => 'nullable|string',
            'announcement_active' => 'nullable|string|max:1',
            'hero_title' => 'nullable|string|max:255',
            'hero_highlight' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'stats_label_1' => 'nullable|string|max:255',
            'stats_label_2' => 'nullable|string|max:255',
            'stats_label_3' => 'nullable|string|max:255',
            'stats_label_4' => 'nullable|string|max:255',
            'section_news_label' => 'nullable|string|max:255',
            'section_news_title' => 'nullable|string|max:255',
            'section_news_desc' => 'nullable|string',
            'section_achievement_label' => 'nullable|string|max:255',
            'section_achievement_title' => 'nullable|string|max:255',
            'section_achievement_desc' => 'nullable|string',
            'section_gallery_label' => 'nullable|string|max:255',
            'section_gallery_title' => 'nullable|string|max:255',
            'section_gallery_desc' => 'nullable|string',
            'section_extracurricular_label' => 'nullable|string|max:255',
            'section_extracurricular_title' => 'nullable|string|max:255',
            'section_extracurricular_desc' => 'nullable|string',
            'contact_label_address' => 'nullable|string|max:255',
            'contact_label_phone' => 'nullable|string|max:255',
            'contact_label_email' => 'nullable|string|max:255',
            'service_ptsp' => 'nullable|string|max:255',
            'service_esurat' => 'nullable|string|max:255',
            'service_presensi' => 'nullable|string|max:255',
            'service_ujian_online' => 'nullable|string|max:255',
            'service_rdm' => 'nullable|string|max:255',
            'service_emis' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string|max:500',
            'footer_show_credit' => 'nullable|string|max:1',
            'meta_title_home' => 'nullable|string|max:255',
            'meta_title_separator' => 'nullable|string|max:10',
            'meta_title_suffix' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keyword' => 'nullable|string|max:500',
            'google_analytics' => 'nullable|string|max:50',
            'google_site_verification' => 'nullable|string|max:255',
            'facebook_pixel' => 'nullable|string|max:50',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'og_image_alt' => 'nullable|string|max:255',
            'twitter_handle' => 'nullable|string|max:50',
            'twitter_card_type' => 'nullable|string|max:30',
            'meta_title_berita' => 'nullable|string|max:255',
            'meta_title_galeri' => 'nullable|string|max:255',
            'meta_title_prestasi' => 'nullable|string|max:255',
            'meta_title_ekstrakurikuler' => 'nullable|string|max:255',
            'meta_title_profil' => 'nullable|string|max:255',
            'registration_enabled' => 'nullable|string|max:1',
            'coming_soon_mode' => 'nullable|string|max:1',
            'coming_soon_date' => 'nullable|string|max:30',
            'maintenance_mode' => 'nullable|string|max:1',
            'maintenance_est_time' => 'nullable|string|max:255',
            'nav_menu' => 'nullable|string',
            
            // Image Upload Fields
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'headmaster_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'school_logo_url' => 'nullable|string|max:500',
            'favicon_url' => 'nullable|string|max:500',
            'headmaster_photo_url' => 'nullable|string|max:500',
        ]);

        $fileFields = ['school_logo', 'favicon', 'headmaster_photo'];
        $urlFields = ['school_logo_url', 'favicon_url', 'headmaster_photo_url'];

        // Handle file uploads first (priority over URL)
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $oldValue = Setting::where('key', $field)->value('value');
                if (!empty($oldValue) && !str_starts_with($oldValue, 'http')) {
                    Storage::disk('s3')->delete($oldValue);
                }
                $path = $request->file($field)->store('settings', 's3');
                Setting::updateOrCreate(['key' => $field], ['value' => $path]);
            } elseif ($request->filled($field . '_url')) {
                // Only update if no file was uploaded
                $oldValue = Setting::where('key', $field)->value('value');
                if (!empty($oldValue) && !str_starts_with($oldValue, 'http')) {
                    Storage::disk('s3')->delete($oldValue);
                }
                Setting::updateOrCreate(['key' => $field], ['value' => $request->input($field . '_url')]);
            }
        }

        // Save text-based validated fields
        foreach ($validated as $key => $value) {
            if (!in_array($key, $fileFields) && !in_array($key, $urlFields)) {
                if (!is_null($value)) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            }
        }

        \Illuminate\Support\Facades\Cache::forget('settings.all');

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
