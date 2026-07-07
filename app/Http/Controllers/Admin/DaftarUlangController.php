<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangPeriode;
use App\Models\DaftarUlangChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DaftarUlangController extends Controller
{
    /**
     * Halaman utama daftar ulang. Menerima query filter (tab kelas: XI/XII, search: nama/NIS, status: lengkap/belum_lengkap).
     * Tampilkan daftar siswa dan status checklist dokumennya. Tampilkan juga informasi periode aktif saat ini.
     */
    public function index(Request $request)
    {
        // 1. Get active periods
        $periodeXI = DaftarUlangPeriode::active()->where('kelas_target', 'XI')->first();
        $periodeXII = DaftarUlangPeriode::active()->where('kelas_target', 'XII')->first();

        // 2. Fetch siswa based on filters
        $query = DaftarUlangSiswa::with(['periode', 'checklist.verifiedBy']);

        // Filter tab kelas (default to XI if none provided or invalid)
        $kelas = $request->input('kelas', 'XI');
        if (!in_array($kelas, ['XI', 'XII'])) {
            $kelas = 'XI';
        }
        $query->where('kelas_tujuan', $kelas);

        // Search filter (nama / NIS)
        if ($request->filled('search') && strlen($request->input('search')) >= 3) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Status filter (lengkap / belum_lengkap)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if (in_array($status, ['lengkap', 'belum_lengkap'])) {
                $query->whereHas('checklist', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        $siswas = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();

        // Calculate global statistics (both classes XI and XII combined)
        $totalSiswa = DaftarUlangSiswa::count();
        $jumlahLengkap = DaftarUlangChecklist::where('status', 'lengkap')->count();
        $jumlahBelumLengkap = $totalSiswa - $jumlahLengkap;
        $progressPersen = $totalSiswa > 0 ? round(($jumlahLengkap / $totalSiswa) * 100, 1) : 0;
        
        // Handle AJAX/JSON requests
        if ($request->ajax() || $request->wantsJson()) {
            $formattedData = collect($siswas->items())->map(function ($siswa) {
                return [
                    'id' => $siswa->id,
                    'nis' => $siswa->nis,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'kelas_asal' => $siswa->kelas_asal,
                    'kelas_tujuan' => $siswa->kelas_tujuan,
                    'jurusan' => $siswa->jurusan,
                    'checklist' => $siswa->checklist ? [
                        'raport' => $siswa->checklist->raport,
                        'kartu_keluarga' => $siswa->checklist->kartu_keluarga,
                        'akte_kelahiran' => $siswa->checklist->akte_kelahiran,
                        'ijazah' => $siswa->checklist->ijazah,
                        'status' => $siswa->checklist->status,
                        'verified_at' => $siswa->checklist->verified_at ? $siswa->checklist->verified_at->toIso8601String() : null,
                    ] : null,
                    'verified_by_name' => $siswa->checklist && $siswa->checklist->verifiedBy ? $siswa->checklist->verifiedBy->name : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'current_page' => $siswas->currentPage(),
                    'data' => $formattedData,
                    'first_page_url' => $siswas->url(1),
                    'from' => $siswas->firstItem(),
                    'last_page' => $siswas->lastPage(),
                    'last_page_url' => $siswas->url($siswas->lastPage()),
                    'next_page_url' => $siswas->nextPageUrl(),
                    'path' => $siswas->path(),
                    'per_page' => $siswas->perPage(),
                    'prev_page_url' => $siswas->previousPageUrl(),
                    'to' => $siswas->lastItem(),
                    'total' => $siswas->total(),
                ],
                'stats' => [
                    'total' => $totalSiswa,
                    'lengkap' => $jumlahLengkap,
                    'belum' => $jumlahBelumLengkap,
                    'persen' => $progressPersen
                ]
            ]);
        }
 
        return view('admin.daftar-ulang.index', compact('siswas', 'periodeXI', 'periodeXII', 'kelas', 'totalSiswa', 'jumlahLengkap', 'jumlahBelumLengkap', 'progressPersen'));
    }

    /**
     * Halaman dashboard rekap daftar ulang.
     * Mengembalikan statistik (total siswa, jumlah lengkap, jumlah belum lengkap, persentase progress) secara global dan rekap per kelas.
     */
    public function dashboard(Request $request)
    {
        // Global stats
        $totalSiswa = DaftarUlangSiswa::count();
        $jumlahLengkap = DaftarUlangChecklist::where('status', 'lengkap')->count();
        $jumlahBelumLengkap = DaftarUlangChecklist::where('status', 'belum_lengkap')->count();
        $progressGlobal = $totalSiswa > 0 ? round(($jumlahLengkap / $totalSiswa) * 100, 2) : 0;

        // Class XI stats
        $totalSiswaXI = DaftarUlangSiswa::where('kelas_tujuan', 'XI')->count();
        $jumlahLengkapXI = DaftarUlangChecklist::whereHas('siswa', function ($q) {
            $q->where('kelas_tujuan', 'XI');
        })->where('status', 'lengkap')->count();
        $jumlahBelumLengkapXI = $totalSiswaXI - $jumlahLengkapXI;
        $progressXI = $totalSiswaXI > 0 ? round(($jumlahLengkapXI / $totalSiswaXI) * 100, 2) : 0;

        // Class XII stats
        $totalSiswaXII = DaftarUlangSiswa::where('kelas_tujuan', 'XII')->count();
        $jumlahLengkapXII = DaftarUlangChecklist::whereHas('siswa', function ($q) {
            $q->where('kelas_tujuan', 'XII');
        })->where('status', 'lengkap')->count();
        $jumlahBelumLengkapXII = $totalSiswaXII - $jumlahLengkapXII;
        $progressXII = $totalSiswaXII > 0 ? round(($jumlahLengkapXII / $totalSiswaXII) * 100, 2) : 0;

        // Active periods
        $periodeXI = DaftarUlangPeriode::active()->where('kelas_target', 'XI')->first();
        $periodeXII = DaftarUlangPeriode::active()->where('kelas_target', 'XII')->first();

        return view('admin.daftar-ulang.dashboard', compact(
            'totalSiswa', 'jumlahLengkap', 'jumlahBelumLengkap', 'progressGlobal',
            'totalSiswaXI', 'jumlahLengkapXI', 'jumlahBelumLengkapXI', 'progressXI',
            'totalSiswaXII', 'jumlahLengkapXII', 'jumlahBelumLengkapXII', 'progressXII',
            'periodeXI', 'periodeXII'
        ));
    }

    /**
     * API Endpoint untuk mengupdate checklist dokumen siswa via AJAX.
     */
    public function updateChecklist(Request $request, $siswa_id)
    {
        // 1. Ensure admin role validation (handled by middleware but let's be safe here too)
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'operator', 'super_admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk melakukan operasi ini.'
            ], 403);
        }

        // 2. Fetch siswa and verify period is currently active
        $siswa = DaftarUlangSiswa::with('periode')->findOrFail($siswa_id);
        $periode = $siswa->periode;

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode daftar ulang tidak ditemukan untuk siswa ini.'
            ], 422);
        }

        // Check if today is between tanggal_buka and tanggal_tutup, and is_active is true
        $today = Carbon::today();
        $isPeriodActive = $periode->is_active 
            && $today->greaterThanOrEqualTo($periode->tanggal_buka) 
            && $today->lessThanOrEqualTo($periode->tanggal_tutup);

        if (!$isPeriodActive) {
            return response()->json([
                'success' => false,
                'message' => 'Periode daftar ulang untuk kelas ' . $siswa->kelas_tujuan . ' saat ini sedang tidak aktif.'
            ], 422);
        }

        // 3. Validate request data
        $validated = $request->validate([
            'raport' => 'required|boolean',
            'kartu_keluarga' => 'required|boolean',
            'akte_kelahiran' => 'required|boolean',
            'ijazah' => 'required|boolean',
        ]);

        // 4. Update checklist record
        $checklist = DaftarUlangChecklist::firstOrCreate(
            ['siswa_id' => $siswa->id],
            [
                'raport' => false,
                'kartu_keluarga' => false,
                'akte_kelahiran' => false,
                'ijazah' => false,
                'status' => 'belum_lengkap',
            ]
        );

        $checklist->raport = $validated['raport'];
        $checklist->kartu_keluarga = $validated['kartu_keluarga'];
        $checklist->akte_kelahiran = $validated['akte_kelahiran'];
        $checklist->ijazah = $validated['ijazah'];
        
        // Simpan user id admin yang melakukan perubahan ke verified_by dan waktu sekarang ke verified_at
        $checklist->verified_by = $user->id;
        $checklist->verified_at = now();

        $checklist->save(); // This will trigger the 'saving' model event to calculate 'status' automatically

        $totalSiswa = DaftarUlangSiswa::count();
        $jumlahLengkap = DaftarUlangChecklist::where('status', 'lengkap')->count();
        $jumlahBelumLengkap = $totalSiswa - $jumlahLengkap;
        $progressPersen = $totalSiswa > 0 ? round(($jumlahLengkap / $totalSiswa) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'message' => 'Checklist dokumen berhasil diperbarui.',
            'data' => [
                'siswa_id' => $siswa->id,
                'raport' => $checklist->raport,
                'kartu_keluarga' => $checklist->kartu_keluarga,
                'akte_kelahiran' => $checklist->akte_kelahiran,
                'ijazah' => $checklist->ijazah,
                'status' => $checklist->status,
                'verified_by_name' => $user->name,
                'verified_at' => $checklist->verified_at->toIso8601String(),
            ],
            'stats' => [
                'total' => $totalSiswa,
                'lengkap' => $jumlahLengkap,
                'belum' => $jumlahBelumLengkap,
                'persen' => $progressPersen
            ]
        ]);
    }

    /**
     * Reset semua data siswa daftar ulang dan data checklist-nya.
     */
    public function reset(Request $request)
    {
        // Proteksi role: Pastikan Auth::user()->isSuperAdmin() bernilai true
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        // Reset semua checklist berkas ke default awal
        \App\Models\DaftarUlangChecklist::query()->update([
            'raport' => false,
            'kartu_keluarga' => false,
            'akte_kelahiran' => false,
            'ijazah' => false,
            'status' => 'belum_lengkap',
            'verified_by' => null,
            'verified_at' => null,
        ]);

        return redirect()->route('admin.daftar-ulang.index')->with('success', 'Semua log verifikasi dokumen berhasil direset ke awal.');
    }
}
