<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('images')->latest()->paginate(12);
        return view('content.admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('content.admin.galleries.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
        ]);

        try {
            $gallery = Gallery::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'category' => $validated['category'] ?? '',
            ]);

            $this->saveImages($gallery, $request->file('images'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupload gambar. Periksa koneksi storage atau coba lagi.');
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);
        return view('content.admin.galleries.form', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'category' => $validated['category'] ?? '',
        ]);

        try {
            if ($request->hasFile('images')) {
                $this->saveImages($gallery, $request->file('images'));
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupload gambar. Periksa koneksi storage atau coba lagi.');
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::with('images')->findOrFail($id);

        try {
            foreach ($gallery->images as $gi) {
                Storage::disk('s3')->delete($gi->image);
                $gi->delete();
            }
        } catch (\Exception $e) {
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil dihapus.');
    }

    public function deleteImage($id)
    {
        $gi = GalleryImage::findOrFail($id);

        try {
            Storage::disk('s3')->delete($gi->image);
        } catch (\Exception $e) {
        }

        $gi->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    private function saveImages(Gallery $gallery, array $files): void
    {
        $order = $gallery->images()->max('order') ?? 0;

        foreach ($files as $file) {
            $order++;
            $path = $file->store('galleries', 's3');
            $gallery->images()->create([
                'image' => $path,
                'order' => $order,
            ]);
        }

        if (!$gallery->image) {
            $first = $gallery->images()->orderBy('order')->first();
            if ($first) {
                $gallery->update(['image' => $first->image]);
            }
        }
    }
}
