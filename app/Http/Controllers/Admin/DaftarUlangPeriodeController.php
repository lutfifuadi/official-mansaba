<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlangPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DaftarUlangPeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only accessible by super_admin (which is also guarded by middleware in routes, but good to have)
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Forbidden');
        }

        $periodes = DaftarUlangPeriode::with('createdBy')->latest()->get();

        return view('admin.daftar-ulang.periode.index', compact('periodes'));
    }

    /**
     * Store or update a resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Forbidden');
        }

        $validated = $request->validate([
            'id' => 'nullable|exists:daftar_ulang_periode,id',
            'tahun_ajaran' => 'required|string|max:20',
            'kelas_target' => 'required|in:XI,XII',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            // Jika ID disediakan, update record yang existing berdasarkan ID.
            // Jika tidak, gunakan updateOrCreate berdasarkan tahun_ajaran + kelas_target
            // untuk mencegah duplikasi (unique constraint di DB).
            DB::transaction(function () use ($validated, $request) {
                if (!empty($validated['id'])) {
                    // Update existing record by ID
                    $periode = DaftarUlangPeriode::findOrFail($validated['id']);
                    
                    // Cek apakah kombinasi tahun_ajaran + kelas_target sudah ada di record LAIN
                    $exists = DaftarUlangPeriode::where('tahun_ajaran', $validated['tahun_ajaran'])
                        ->where('kelas_target', $validated['kelas_target'])
                        ->where('id', '!=', $periode->id)
                        ->exists();
                    
                    if ($exists) {
                        throw new \Exception('Kombinasi tahun ajaran dan kelas target sudah ada pada periode lain.');
                    }

                    $isActive = $request->boolean('is_active');

                    $periode->update([
                        'tahun_ajaran' => $validated['tahun_ajaran'],
                        'kelas_target' => $validated['kelas_target'],
                        'tanggal_buka' => $validated['tanggal_buka'],
                        'tanggal_tutup' => $validated['tanggal_tutup'],
                        'is_active' => $isActive,
                    ]);

                    // Jika status is_active bernilai true, matikan periode lain dengan kelas_target yang sama
                    if ($isActive) {
                        DaftarUlangPeriode::where('kelas_target', $validated['kelas_target'])
                            ->where('id', '!=', $periode->id)
                            ->update(['is_active' => false]);
                    }
                } else {
                    // Cek apakah kombinasi tahun_ajaran + kelas_target sudah ada
                    $exists = DaftarUlangPeriode::where('tahun_ajaran', $validated['tahun_ajaran'])
                        ->where('kelas_target', $validated['kelas_target'])
                        ->exists();
                    
                    if ($exists) {
                        throw new \Exception('Kombinasi tahun ajaran dan kelas target sudah ada. Gunakan form edit untuk mengubahnya.');
                    }

                    $isActive = $request->boolean('is_active');

                    // Create new record
                    $periode = DaftarUlangPeriode::create([
                        'tahun_ajaran' => $validated['tahun_ajaran'],
                        'kelas_target' => $validated['kelas_target'],
                        'tanggal_buka' => $validated['tanggal_buka'],
                        'tanggal_tutup' => $validated['tanggal_tutup'],
                        'is_active' => $isActive,
                        'created_by' => Auth::id(),
                    ]);

                    // Jika status is_active bernilai true, matikan periode lain dengan kelas_target yang sama
                    if ($isActive) {
                        DaftarUlangPeriode::where('kelas_target', $validated['kelas_target'])
                            ->where('id', '!=', $periode->id)
                            ->update(['is_active' => false]);
                    }
                }
            });

            return redirect()->back()->with('success', 'Periode daftar ulang berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tahun_ajaran' => $e->getMessage()]);
        }
    }
}
