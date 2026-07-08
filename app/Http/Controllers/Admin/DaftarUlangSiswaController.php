<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\DaftarUlangImport;
use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangPeriode;
use App\Models\DaftarUlangChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DaftarUlangSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DaftarUlangSiswa::with(['periode', 'checklist']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_tujuan')) {
            $query->where('kelas_tujuan', $request->input('kelas_tujuan'));
        }

        $siswas = $query->latest()->paginate(15);
        $periodes = DaftarUlangPeriode::where('is_active', true)->get();

        return view('admin.daftar-ulang.siswa.index', compact('siswas', 'periodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodes = DaftarUlangPeriode::where('is_active', true)->get();
        return view('admin.daftar-ulang.siswa.create', compact('periodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:daftar_ulang_periode,id',
            'nis' => 'required|string|max:20',
            'nama_lengkap' => 'required|string|max:100',
            'kelas_asal' => 'required|in:X,XI',
            'kelas_tujuan' => 'required|in:XI,XII',
            'jurusan' => 'nullable|string|max:50',
        ]);

        // Add additional validation to ensure target class matches period target class
        $periode = DaftarUlangPeriode::findOrFail($validated['periode_id']);
        if ($periode->kelas_target !== $validated['kelas_tujuan']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['kelas_tujuan' => 'Kelas tujuan harus sesuai dengan kelas target periode yang dipilih (' . $periode->kelas_target . ').']);
        }

        // Validate unique nis within the same period (migration has unique constraint: unique(['nis', 'periode_id']))
        $exists = DaftarUlangSiswa::where('nis', $validated['nis'])
            ->where('periode_id', $validated['periode_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nis' => 'Siswa dengan NIS ini sudah terdaftar pada periode yang dipilih.']);
        }

        try {
            DB::transaction(function () use ($validated) {
                $siswa = DaftarUlangSiswa::create($validated);

                // Automatically create the associated checklist with default false values
                DaftarUlangChecklist::create([
                    'siswa_id' => $siswa->id,
                    'raport' => false,
                    'kartu_keluarga' => false,
                    'akte_kelahiran' => false,
                    'ijazah' => false,
                    'status' => 'belum_lengkap',
                    'verified_by' => null,
                    'verified_at' => null,
                ]);
            });

            return redirect()->route('admin.daftar-ulang-siswa.index')
                ->with('success', 'Data siswa dan checklist dokumen berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $siswa = DaftarUlangSiswa::findOrFail($id);
        $periodes = DaftarUlangPeriode::where('is_active', true)->get();

        return view('admin.daftar-ulang.siswa.edit', compact('siswa', 'periodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = DaftarUlangSiswa::findOrFail($id);

        $validated = $request->validate([
            'periode_id' => 'required|exists:daftar_ulang_periode,id',
            'nis' => 'required|string|max:20',
            'nama_lengkap' => 'required|string|max:100',
            'kelas_asal' => 'required|in:X,XI',
            'kelas_tujuan' => 'required|in:XI,XII',
            'jurusan' => 'nullable|string|max:50',
        ]);

        $periode = DaftarUlangPeriode::findOrFail($validated['periode_id']);
        if ($periode->kelas_target !== $validated['kelas_tujuan']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['kelas_tujuan' => 'Kelas tujuan harus sesuai dengan kelas target periode yang dipilih (' . $periode->kelas_target . ').']);
        }

        // Validate unique nis within the same period excluding current record
        $exists = DaftarUlangSiswa::where('nis', $validated['nis'])
            ->where('periode_id', $validated['periode_id'])
            ->where('id', '!=', $siswa->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nis' => 'Siswa dengan NIS ini sudah terdaftar pada periode yang dipilih.']);
        }

        try {
            $siswa->update($validated);

            return redirect()->route('admin.daftar-ulang-siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Import data siswa dari file Excel.
     * Hanya bisa diakses oleh super_admin, admin, dan operator.
     */
    public function import(Request $request)
    {
        // Validasi role
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['super_admin', 'admin', 'operator'])) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki izin untuk mengimpor data.');
        }

        // Validasi input
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // max 5MB
            'periode_id' => 'required|exists:daftar_ulang_periode,id',
        ]);

        $periode = DaftarUlangPeriode::findOrFail($validated['periode_id']);

        // Pastikan periode untuk target kelas XI (karena data Excel adalah data siswa naik ke XI)
        if ($periode->kelas_target !== 'XI') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode_id' => 'Periode yang dipilih harus memiliki kelas target XI.']);
        }

        try {
            // Simpan file ke storage sementara
            $file = $request->file('file');
            $filePath = $file->storeAs('temp', 'import_daftar_ulang_' . time() . '.' . $file->getClientOriginalExtension());

            $import = new DaftarUlangImport($validated['periode_id']);
            $results = $import->import(storage_path('app/' . $filePath));

            $message = "Import selesai! ";
            $message .= "Berhasil: {$results['success']} siswa, ";
            $message .= " dilewati: {$results['skipped']} siswa, ";
            $message .= " sheet diproses: {$results['sheets_processed']}.";

            if (!empty($results['errors'])) {
                $errorCount = count($results['errors']);
                $message .= " {$errorCount} error ditemukan.";
                
                // Simpan error detail ke flash session untuk ditampilkan
                $request->session()->flash('import_errors', $results['errors']);
            }

            return redirect()->route('admin.daftar-ulang-siswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Only accessible by super_admin, admin, and operator
        if (!Auth::user() || !in_array(Auth::user()->role, ['super_admin', 'admin', 'operator'])) {
            abort(403, 'Forbidden');
        }

        try {
            $siswa = DaftarUlangSiswa::findOrFail($id);
            $siswa->delete();

            return redirect()->route('admin.daftar-ulang-siswa.index')
                ->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }
}
