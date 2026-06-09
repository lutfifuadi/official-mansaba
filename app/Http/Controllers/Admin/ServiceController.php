<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::ordered()->paginate(20);
        return view('content.admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('content.admin.services.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:services,name',
            'icon'       => 'nullable|string|max:100',
            'url'        => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|string|max:1',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('content.admin.services.form', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:services,name,' . $service->id,
            'icon'       => 'nullable|string|max:100',
            'url'        => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|string|max:1',
        ]);

        if ($request->name !== $service->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
