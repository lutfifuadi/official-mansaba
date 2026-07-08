<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangPeriode;
use App\Models\DaftarUlangChecklist;
use App\Events\DaftarUlangChecklistUpdated;
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

        // Kelompok kelengkapan filter
        if ($request->filled('kelompok')) {
            $query->whereKelompokKelengkapan($request->input('kelompok'));
        }

        // Kurang berkas filter
        if ($request->filled('kurang_berkas')) {
            $query->whereKurangBerkas($request->input('kurang_berkas'));
        }

        $siswas = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();

        // Calculate global statistics (both classes XI and XII combined)
        $totalSiswa = DaftarUlangSiswa::count();
        $jumlahLengkap = DaftarUlangChecklist::where('status', 'lengkap')->count();
        $jumlahBelumLengkap = $totalSiswa - $jumlahLengkap;
        $progressPersen = $totalSiswa > 0 ? round(($jumlahLengkap / $totalSiswa) * 100, 1) : 0;

        // Hitung statistik kelompok kelengkapan (global)
        $kelompokCounts = [
            'lengkap' => 0,
            'hampir_lengkap' => 0,
            'setengah_lengkap' => 0,
            'baru_memulai' => 0,
            'belum_kumpul' => 0,
        ];
        
        $checklistScores = DaftarUlangChecklist::selectRaw('(raport + kartu_keluarga + akte_kelahiran + ijazah) as score, count(*) as total')
            ->groupBy('score')
            ->get();
            
        $siswaTanpaChecklist = DaftarUlangSiswa::doesntHave('checklist')->count();
        $kelompokCounts['belum_kumpul'] += $siswaTanpaChecklist;

        foreach ($checklistScores as $cs) {
            $score = (int) $cs->score;
            $total = (int) $cs->total;
            switch ($score) {
                case 4:
                    $kelompokCounts['lengkap'] += $total;
                    break;
                case 3:
                    $kelompokCounts['hampir_lengkap'] += $total;
                    break;
                case 2:
                    $kelompokCounts['setengah_lengkap'] += $total;
                    break;
                case 1:
                    $kelompokCounts['baru_memulai'] += $total;
                    break;
                default:
                    $kelompokCounts['belum_kumpul'] += $total;
                    break;
            }
        }

        // Ringkasan berkas kurang terbanyak
        $kurangRaport = DaftarUlangChecklist::where('raport', false)->count() + $siswaTanpaChecklist;
        $kurangKK = DaftarUlangChecklist::where('kartu_keluarga', false)->count() + $siswaTanpaChecklist;
        $kurangAkte = DaftarUlangChecklist::where('akte_kelahiran', false)->count() + $siswaTanpaChecklist;
        $kurangIjazah = DaftarUlangChecklist::where('ijazah', false)->count() + $siswaTanpaChecklist;

        $berkasKurangSummary = [
            'raport' => $kurangRaport,
            'kartu_keluarga' => $kurangKK,
            'akte_kelahiran' => $kurangAkte,
            'ijazah' => $kurangIjazah,
        ];
        
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
                        'skor_kelengkapan' => $siswa->checklist->skor_kelengkapan,
                        'nama_kelompok' => $siswa->checklist->nama_kelompok,
                        'kurang_item' => $siswa->checklist->kurang_item,
                    ] : [
                        'raport' => false,
                        'kartu_keluarga' => false,
                        'akte_kelahiran' => false,
                        'ijazah' => false,
                        'status' => 'belum_lengkap',
                        'verified_at' => null,
                        'skor_kelengkapan' => 0,
                        'nama_kelompok' => 'Belum Kumpul',
                        'kurang_item' => ['Raport', 'Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'],
                    ],
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
                    'persen' => $progressPersen,
                    'kelompok_counts' => $kelompokCounts,
                    'statistik_kelompok' => $kelompokCounts,
                    'berkas_kurang' => $berkasKurangSummary,
                    'ringkasan_kurang' => $berkasKurangSummary,
                ]
            ]);
        }
  
        return view('admin.daftar-ulang.index', compact(
            'siswas', 
            'periodeXI', 
            'periodeXII', 
            'kelas', 
            'totalSiswa', 
            'jumlahLengkap', 
            'jumlahBelumLengkap', 
            'progressPersen',
            'kelompokCounts',
            'berkasKurangSummary'
        ));
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

        $statsGlobal = $this->getKelompokAndKurangStats();
        $statsXI = $this->getKelompokAndKurangStats('XI');
        $statsXII = $this->getKelompokAndKurangStats('XII');

        $statistikKelompok = $statsGlobal['kelompok'];
        $ringkasanKurang = $statsGlobal['kurang'];

        $statistikKelompokXI = $statsXI['kelompok'];
        $ringkasanKurangXI = $statsXI['kurang'];

        $statistikKelompokXII = $statsXII['kelompok'];
        $ringkasanKurangXII = $statsXII['kurang'];

        return view('admin.daftar-ulang.dashboard', compact(
            'totalSiswa', 'jumlahLengkap', 'jumlahBelumLengkap', 'progressGlobal',
            'totalSiswaXI', 'jumlahLengkapXI', 'jumlahBelumLengkapXI', 'progressXI',
            'totalSiswaXII', 'jumlahLengkapXII', 'jumlahBelumLengkapXII', 'progressXII',
            'periodeXI', 'periodeXII', 'statistikKelompok', 'ringkasanKurang',
            'statistikKelompokXI', 'ringkasanKurangXI',
            'statistikKelompokXII', 'ringkasanKurangXII'
        ));
    }

    /**
     * API Endpoint untuk mengambil statistik terkini daftar ulang (digunakan fallback AJAX dashboard).
     */
    public function stats(Request $request)
    {
        // Proteksi role
        if (!Auth::user() || !in_array(Auth::user()->role, ['super_admin', 'admin', 'operator'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        // Global Stats
        $totalSiswa = DaftarUlangSiswa::count();
        $jumlahLengkap = DaftarUlangChecklist::where('status', 'lengkap')->count();
        $jumlahBelumLengkap = $totalSiswa - $jumlahLengkap;
        $progressPersen = $totalSiswa > 0 ? round(($jumlahLengkap / $totalSiswa) * 100, 1) : 0;

        // Class XI Stats
        $totalSiswaXI = DaftarUlangSiswa::where('kelas_tujuan', 'XI')->count();
        $jumlahLengkapXI = DaftarUlangChecklist::whereHas('siswa', function ($q) {
            $q->where('kelas_tujuan', 'XI');
        })->where('status', 'lengkap')->count();
        $jumlahBelumLengkapXI = $totalSiswaXI - $jumlahLengkapXI;
        $progressXI = $totalSiswaXI > 0 ? round(($jumlahLengkapXI / $totalSiswaXI) * 100, 2) : 0;

        // Class XII Stats
        $totalSiswaXII = DaftarUlangSiswa::where('kelas_tujuan', 'XII')->count();
        $jumlahLengkapXII = DaftarUlangChecklist::whereHas('siswa', function ($q) {
            $q->where('kelas_tujuan', 'XII');
        })->where('status', 'lengkap')->count();
        $jumlahBelumLengkapXII = $totalSiswaXII - $jumlahLengkapXII;
        $progressXII = $totalSiswaXII > 0 ? round(($jumlahLengkapXII / $totalSiswaXII) * 100, 2) : 0;

        $statsGlobal = $this->getKelompokAndKurangStats();
        $statsXI = $this->getKelompokAndKurangStats('XI');
        $statsXII = $this->getKelompokAndKurangStats('XII');

        return response()->json([
            'success' => true,
            'stats' => [
                'total'       => $totalSiswa,
                'lengkap'     => $jumlahLengkap,
                'belum'       => $jumlahBelumLengkap,
                'persen'      => $progressPersen,
                'total_xi'    => $totalSiswaXI,
                'lengkap_xi'  => $jumlahLengkapXI,
                'belum_xi'    => $jumlahBelumLengkapXI,
                'persen_xi'   => $progressXI,
                'total_xii'   => $totalSiswaXII,
                'lengkap_xii' => $jumlahLengkapXII,
                'belum_xii'   => $jumlahBelumLengkapXII,
                'persen_xii'  => $progressXII,
                'kelompok_counts' => $statsGlobal['kelompok'],
                'statistik_kelompok' => $statsGlobal['kelompok'],
                'berkas_kurang' => $statsGlobal['kurang'],
                'ringkasan_kurang' => $statsGlobal['kurang'],
                // Detail per tingkat
                'statistik_kelompok_xi' => $statsXI['kelompok'],
                'ringkasan_kurang_xi' => $statsXI['kurang'],
                'statistik_kelompok_xii' => $statsXII['kelompok'],
                'ringkasan_kurang_xii' => $statsXII['kurang'],
            ],
        ]);
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

        // 1. Global Stats
        $totalSiswa = DaftarUlangSiswa::count();
        $jumlahLengkap = DaftarUlangChecklist::where('status', 'lengkap')->count();
        $jumlahBelumLengkap = $totalSiswa - $jumlahLengkap;
        $progressPersen = $totalSiswa > 0 ? round(($jumlahLengkap / $totalSiswa) * 100, 1) : 0;

        // 2. Class XI Stats
        $totalSiswaXI = DaftarUlangSiswa::where('kelas_tujuan', 'XI')->count();
        $jumlahLengkapXI = DaftarUlangChecklist::whereHas('siswa', function ($q) {
            $q->where('kelas_tujuan', 'XI');
        })->where('status', 'lengkap')->count();
        $jumlahBelumLengkapXI = $totalSiswaXI - $jumlahLengkapXI;
        $progressXI = $totalSiswaXI > 0 ? round(($jumlahLengkapXI / $totalSiswaXI) * 100, 2) : 0;

        // 3. Class XII Stats
        $totalSiswaXII = DaftarUlangSiswa::where('kelas_tujuan', 'XII')->count();
        $jumlahLengkapXII = DaftarUlangChecklist::whereHas('siswa', function ($q) {
            $q->where('kelas_tujuan', 'XII');
        })->where('status', 'lengkap')->count();
        $jumlahBelumLengkapXII = $totalSiswaXII - $jumlahLengkapXII;
        $progressXII = $totalSiswaXII > 0 ? round(($jumlahLengkapXII / $totalSiswaXII) * 100, 2) : 0;

        $statsGlobal = $this->getKelompokAndKurangStats();
        $statsXI = $this->getKelompokAndKurangStats('XI');
        $statsXII = $this->getKelompokAndKurangStats('XII');

        // Broadcast real-time event for checklist update
        $statsPayload = [
            'total' => $totalSiswa,
            'lengkap' => $jumlahLengkap,
            'belum' => $jumlahBelumLengkap,
            'persen' => $progressPersen,
            
            // Class XI
            'total_xi' => $totalSiswaXI,
            'lengkap_xi' => $jumlahLengkapXI,
            'belum_xi' => $jumlahBelumLengkapXI,
            'persen_xi' => $progressXI,
            
            // Class XII
            'total_xii' => $totalSiswaXII,
            'lengkap_xii' => $jumlahLengkapXII,
            'belum_xii' => $jumlahBelumLengkapXII,
            'persen_xii' => $progressXII,

            // Kelompok counts
            'kelompok_counts' => $statsGlobal['kelompok'],
            'statistik_kelompok' => $statsGlobal['kelompok'],
            'berkas_kurang' => $statsGlobal['kurang'],
            'ringkasan_kurang' => $statsGlobal['kurang'],

            // Detail per tingkat
            'statistik_kelompok_xi' => $statsXI['kelompok'],
            'ringkasan_kurang_xi' => $statsXI['kurang'],
            'statistik_kelompok_xii' => $statsXII['kelompok'],
            'ringkasan_kurang_xii' => $statsXII['kurang'],
        ];

        $segmentasi = [
            'skor_kelengkapan' => $checklist->skor_kelengkapan,
            'nama_kelompok' => $checklist->nama_kelompok,
            'kurang_item' => $checklist->kurang_item,
        ];

        $checklistPayload = [
            'raport' => $checklist->raport,
            'kartu_keluarga' => $checklist->kartu_keluarga,
            'akte_kelahiran' => $checklist->akte_kelahiran,
            'ijazah' => $checklist->ijazah,
            'status' => $checklist->status,
            'verified_by_name' => $user->name,
            'verified_at' => $checklist->verified_at ? $checklist->verified_at->toIso8601String() : null,
            'skor_kelengkapan' => $checklist->skor_kelengkapan,
            'nama_kelompok' => $checklist->nama_kelompok,
            'kurang_item' => $checklist->kurang_item,
            'segmentasi' => $segmentasi,
        ];

        event(new DaftarUlangChecklistUpdated(
            $siswa->id,
            $checklistPayload,
            $statsPayload
        ));

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
                'skor_kelengkapan' => $checklist->skor_kelengkapan,
                'nama_kelompok' => $checklist->nama_kelompok,
                'kurang_item' => $checklist->kurang_item,
                'segmentasi' => $segmentasi,
            ],
            'stats' => [
                'total' => $totalSiswa,
                'lengkap' => $jumlahLengkap,
                'belum' => $jumlahBelumLengkap,
                'persen' => $progressPersen,
                'kelompok_counts' => $statsGlobal['kelompok'],
                'statistik_kelompok' => $statsGlobal['kelompok'],
                'berkas_kurang' => $statsGlobal['kurang'],
                'ringkasan_kurang' => $statsGlobal['kurang'],
                // Detail per tingkat
                'statistik_kelompok_xi' => $statsXI['kelompok'],
                'ringkasan_kurang_xi' => $statsXI['kurang'],
                'statistik_kelompok_xii' => $statsXII['kelompok'],
                'ringkasan_kurang_xii' => $statsXII['kurang'],
            ]
        ]);
    }

    /**
     * Reset semua data siswa daftar ulang dan data checklist-nya.
     */
    public function reset(Request $request)
    {
        // Proteksi role: super_admin, admin, dan operator
        if (!Auth::user() || !in_array(Auth::user()->role, ['super_admin', 'admin', 'operator'])) {
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

        $totalSiswa = \App\Models\DaftarUlangSiswa::count();
        $totalSiswaXI = \App\Models\DaftarUlangSiswa::where('kelas_tujuan', 'XI')->count();
        $totalSiswaXII = \App\Models\DaftarUlangSiswa::where('kelas_tujuan', 'XII')->count();

        $kelompokCounts = [
            'lengkap' => 0,
            'hampir_lengkap' => 0,
            'setengah_lengkap' => 0,
            'baru_memulai' => 0,
            'belum_kumpul' => $totalSiswa,
        ];

        $berkasKurangSummary = [
            'raport' => $totalSiswa,
            'kartu_keluarga' => $totalSiswa,
            'akte_kelahiran' => $totalSiswa,
            'ijazah' => $totalSiswa,
        ];

        $kelompokCountsXI = [
            'lengkap' => 0,
            'hampir_lengkap' => 0,
            'setengah_lengkap' => 0,
            'baru_memulai' => 0,
            'belum_kumpul' => $totalSiswaXI,
        ];
        $kelompokCountsXII = [
            'lengkap' => 0,
            'hampir_lengkap' => 0,
            'setengah_lengkap' => 0,
            'baru_memulai' => 0,
            'belum_kumpul' => $totalSiswaXII,
        ];
        $berkasKurangSummaryXI = [
            'raport' => $totalSiswaXI,
            'kartu_keluarga' => $totalSiswaXI,
            'akte_kelahiran' => $totalSiswaXI,
            'ijazah' => $totalSiswaXI,
        ];
        $berkasKurangSummaryXII = [
            'raport' => $totalSiswaXII,
            'kartu_keluarga' => $totalSiswaXII,
            'akte_kelahiran' => $totalSiswaXII,
            'ijazah' => $totalSiswaXII,
        ];

        $statsPayload = [
            'total' => $totalSiswa,
            'lengkap' => 0,
            'belum' => $totalSiswa,
            'persen' => 0,
            
            // Class XI
            'total_xi' => $totalSiswaXI,
            'lengkap_xi' => 0,
            'belum_xi' => $totalSiswaXI,
            'persen_xi' => 0,
            
            // Class XII
            'total_xii' => $totalSiswaXII,
            'lengkap_xii' => 0,
            'belum_xii' => $totalSiswaXII,
            'persen_xii' => 0,

            // Kelompok counts
            'kelompok_counts' => $kelompokCounts,
            'statistik_kelompok' => $kelompokCounts,
            'berkas_kurang' => $berkasKurangSummary,
            'ringkasan_kurang' => $berkasKurangSummary,

            // Detail per tingkat
            'statistik_kelompok_xi' => $kelompokCountsXI,
            'ringkasan_kurang_xi' => $berkasKurangSummaryXI,
            'statistik_kelompok_xii' => $kelompokCountsXII,
            'ringkasan_kurang_xii' => $berkasKurangSummaryXII,
        ];

        event(new DaftarUlangChecklistUpdated(
            null, // reset: tidak terkait siswa spesifik
            [
                'raport' => false,
                'kartu_keluarga' => false,
                'akte_kelahiran' => false,
                'ijazah' => false,
                'status' => 'belum_lengkap',
                'verified_by_name' => null,
                'verified_at' => null,
                'skor_kelengkapan' => 0,
                'nama_kelompok' => 'Belum Kumpul',
                'kurang_item' => ['Raport', 'Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'],
                'segmentasi' => [
                    'skor_kelengkapan' => 0,
                    'nama_kelompok' => 'Belum Kumpul',
                    'kurang_item' => ['Raport', 'Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'],
                ],
            ],
            $statsPayload
        ));

        return redirect()->route('admin.daftar-ulang.index')->with('success', 'Semua log verifikasi dokumen berhasil direset ke awal.');
    }

    /**
     * Helper privat untuk mengambil statistik kelompok kelengkapan berkas dan ringkasan berkas kurang
     * per kelas target (XI atau XII), atau secara global (jika null).
     */
    private function getKelompokAndKurangStats($kelas = null)
    {
        $statistikKelompok = [
            'lengkap' => 0,
            'hampir_lengkap' => 0,
            'setengah_lengkap' => 0,
            'baru_memulai' => 0,
            'belum_kumpul' => 0,
        ];

        // Query checklist scores
        $scoreQuery = DaftarUlangChecklist::selectRaw('(raport + kartu_keluarga + akte_kelahiran + ijazah) as score, count(*) as total');
        if ($kelas) {
            $scoreQuery->whereHas('siswa', function ($q) use ($kelas) {
                $q->where('kelas_tujuan', $kelas);
            });
        }
        $checklistScores = $scoreQuery->groupBy('score')->get();

        // Query total siswa tanpa checklist
        $tanpaQuery = DaftarUlangSiswa::doesntHave('checklist');
        if ($kelas) {
            $tanpaQuery->where('kelas_tujuan', $kelas);
        }
        $siswaTanpaChecklist = $tanpaQuery->count();
        $statistikKelompok['belum_kumpul'] += $siswaTanpaChecklist;

        foreach ($checklistScores as $cs) {
            $score = (int) $cs->score;
            $total = (int) $cs->total;
            switch ($score) {
                case 4:
                    $statistikKelompok['lengkap'] += $total;
                    break;
                case 3:
                    $statistikKelompok['hampir_lengkap'] += $total;
                    break;
                case 2:
                    $statistikKelompok['setengah_lengkap'] += $total;
                    break;
                case 1:
                    $statistikKelompok['baru_memulai'] += $total;
                    break;
                default:
                    $statistikKelompok['belum_kumpul'] += $total;
                    break;
            }
        }

        // Hitung berkas kurang
        $raportQuery = DaftarUlangChecklist::where('raport', false);
        $kkQuery = DaftarUlangChecklist::where('kartu_keluarga', false);
        $akteQuery = DaftarUlangChecklist::where('akte_kelahiran', false);
        $ijazahQuery = DaftarUlangChecklist::where('ijazah', false);

        if ($kelas) {
            $raportQuery->whereHas('siswa', function ($q) use ($kelas) { $q->where('kelas_tujuan', $kelas); });
            $kkQuery->whereHas('siswa', function ($q) use ($kelas) { $q->where('kelas_tujuan', $kelas); });
            $akteQuery->whereHas('siswa', function ($q) use ($kelas) { $q->where('kelas_tujuan', $kelas); });
            $ijazahQuery->whereHas('siswa', function ($q) use ($kelas) { $q->where('kelas_tujuan', $kelas); });
        }

        $kurangRaport = $raportQuery->count() + $siswaTanpaChecklist;
        $kurangKK = $kkQuery->count() + $siswaTanpaChecklist;
        $kurangAkte = $akteQuery->count() + $siswaTanpaChecklist;
        $kurangIjazah = $ijazahQuery->count() + $siswaTanpaChecklist;

        $ringkasanKurang = [
            'raport' => $kurangRaport,
            'kartu_keluarga' => $kurangKK,
            'akte_kelahiran' => $kurangAkte,
            'ijazah' => $kurangIjazah,
        ];

        return [
            'kelompok' => $statistikKelompok,
            'kurang' => $ringkasanKurang,
        ];
    }
}
