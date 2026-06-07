<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::latest()->paginate(10);
        return view('content.admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('content.admin.achievements.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'achievement_date' => 'nullable|date',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('achievements', 's3');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupload gambar. Periksa koneksi storage atau coba lagi.');
        }

        Achievement::create($validated);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $achievement = Achievement::findOrFail($id);
        return view('content.admin.achievements.form', compact('achievement'));
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'achievement_date' => 'nullable|date',
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($achievement->image) {
                    Storage::disk('s3')->delete($achievement->image);
                }
                $validated['image'] = $request->file('image')->store('achievements', 's3');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupload gambar. Periksa koneksi storage atau coba lagi.');
        }

        $achievement->update($validated);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        try {
            if ($achievement->image) {
                Storage::disk('s3')->delete($achievement->image);
            }
        } catch (\Exception $e) {
        }

        $achievement->delete();

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
