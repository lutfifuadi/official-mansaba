<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::latest()->paginate(10);
        return view('content.admin.extracurriculars.index', compact('extracurriculars'));
    }

    public function create()
    {
        return view('content.admin.extracurriculars.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coach' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'schedule' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('extracurriculars', 's3');
        }

        Extracurricular::create($validated);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        return view('content.admin.extracurriculars.form', compact('extracurricular'));
    }

    public function update(Request $request, $id)
    {
        $extracurricular = Extracurricular::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coach' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'schedule' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
        ]);

        if ($request->filled('name') && $request->name !== $extracurricular->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            if ($extracurricular->image) {
                Storage::disk('s3')->delete($extracurricular->image);
            }
            $validated['image'] = $request->file('image')->store('extracurriculars', 's3');
        }

        $extracurricular->update($validated);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        if ($extracurricular->image) {
            Storage::disk('s3')->delete($extracurricular->image);
        }
        $extracurricular->delete();

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
