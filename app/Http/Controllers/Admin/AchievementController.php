<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Extracurricular;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::with('extracurriculars')->latest()->paginate(10);
        return view('content.admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        $extracurriculars = Extracurricular::all();
        return view('content.admin.achievements.form', compact('extracurriculars'));
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
            'extracurricular_ids' => 'nullable|array',
            'extracurricular_ids.*' => 'exists:extracurriculars,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = StorageHelper::putFile('achievements', $request->file('image'));
            if (!$validated['image']) {
                return back()->withInput()->with('error', 'Gagal mengupload gambar. Periksa koneksi storage atau coba lagi.');
            }
        }

        $achievement = Achievement::create($validated);
        $achievement->extracurriculars()->sync($validated['extracurricular_ids'] ?? []);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $extracurriculars = Extracurricular::all();
        $achievement = Achievement::with('extracurriculars')->findOrFail($id);
        return view('content.admin.achievements.form', compact('achievement', 'extracurriculars'));
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
            'extracurricular_ids' => 'nullable|array',
            'extracurricular_ids.*' => 'exists:extracurriculars,id',
        ]);

        if ($request->hasFile('image')) {
            StorageHelper::deleteFile($achievement->image);
            $validated['image'] = StorageHelper::putFile('achievements', $request->file('image'));
            if (!$validated['image']) {
                return back()->withInput()->with('error', 'Gagal mengupload gambar. Periksa koneksi storage atau coba lagi.');
            }
        }

        $achievement->update($validated);
        $achievement->extracurriculars()->sync($validated['extracurricular_ids'] ?? []);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        StorageHelper::deleteFile($achievement->image);

        $achievement->delete();

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
