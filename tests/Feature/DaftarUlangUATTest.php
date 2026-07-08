<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DaftarUlangPeriode;
use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangChecklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class DaftarUlangUATTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected DaftarUlangPeriode $periodeXI;
    protected DaftarUlangPeriode $periodeXII;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock event broadcasting agar tidak mencoba mengirim sinyal ke Reverb/Pusher lokal yang mati saat running tests
        \Illuminate\Support\Facades\Event::fake([
            \App\Events\DaftarUlangChecklistUpdated::class,
        ]);

        $this->admin = User::factory()->create(['role' => 'admin']);

        // Periode Aktif
        $this->periodeXI = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        $this->periodeXII = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XII',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
    }

    /**
     * 1. Pastikan model DaftarUlangChecklist accessors berjalan dengan benar (skor_kelengkapan, nama_kelompok, kurang_item).
     */
    public function test_daftar_ulang_checklist_accessors(): void
    {
        $siswa = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id,
            'nis' => '11111',
            'nama_lengkap' => 'Siswa XI A',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        // Case 1: 0 items (Belum Kumpul)
        $checklist = DaftarUlangChecklist::create([
            'siswa_id' => $siswa->id,
            'raport' => false,
            'kartu_keluarga' => false,
            'akte_kelahiran' => false,
            'ijazah' => false,
        ]);

        $this->assertEquals(0, $checklist->skor_kelengkapan);
        $this->assertEquals('Belum Kumpul', $checklist->nama_kelompok);
        $this->assertEquals(['Raport', 'Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'], $checklist->kurang_item);

        // Case 2: 1 item (Baru Memulai)
        $checklist->update(['raport' => true]);
        $checklist = $checklist->fresh();
        $this->assertEquals(1, $checklist->skor_kelengkapan);
        $this->assertEquals('Baru Memulai', $checklist->nama_kelompok);
        $this->assertEquals(['Kartu Keluarga', 'Akte Kelahiran', 'Ijazah'], $checklist->kurang_item);

        // Case 3: 2 items (Setengah Lengkap)
        $checklist->update(['kartu_keluarga' => true]);
        $checklist = $checklist->fresh();
        $this->assertEquals(2, $checklist->skor_kelengkapan);
        $this->assertEquals('Setengah Lengkap', $checklist->nama_kelompok);
        $this->assertEquals(['Akte Kelahiran', 'Ijazah'], $checklist->kurang_item);

        // Case 4: 3 items (Hampir Lengkap)
        $checklist->update(['akte_kelahiran' => true]);
        $checklist = $checklist->fresh();
        $this->assertEquals(3, $checklist->skor_kelengkapan);
        $this->assertEquals('Hampir Lengkap', $checklist->nama_kelompok);
        $this->assertEquals(['Ijazah'], $checklist->kurang_item);

        // Case 5: 4 items (Lengkap)
        $checklist->update(['ijazah' => true]);
        $checklist = $checklist->fresh();
        $this->assertEquals(4, $checklist->skor_kelengkapan);
        $this->assertEquals('Lengkap', $checklist->nama_kelompok);
        $this->assertEquals([], $checklist->kurang_item);
    }

    /**
     * 2. Pastikan query scopes di model DaftarUlangSiswa mengembalikan data filter yang tepat.
     */
    public function test_daftar_ulang_siswa_query_scopes(): void
    {
        // Siswa 1: Lengkap (4)
        $siswa1 = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id, 'nis' => '10001', 'nama_lengkap' => 'Siswa Lengkap', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'
        ]);
        DaftarUlangChecklist::create([
            'siswa_id' => $siswa1->id, 'raport' => true, 'kartu_keluarga' => true, 'akte_kelahiran' => true, 'ijazah' => true
        ]);

        // Siswa 2: Hampir Lengkap (3) - kurang Ijazah
        $siswa2 = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id, 'nis' => '10002', 'nama_lengkap' => 'Siswa Hampir Lengkap', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'
        ]);
        DaftarUlangChecklist::create([
            'siswa_id' => $siswa2->id, 'raport' => true, 'kartu_keluarga' => true, 'akte_kelahiran' => true, 'ijazah' => false
        ]);

        // Siswa 3: Setengah Lengkap (2) - kurang Akte Kelahiran, Ijazah
        $siswa3 = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id, 'nis' => '10003', 'nama_lengkap' => 'Siswa Setengah Lengkap', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'
        ]);
        DaftarUlangChecklist::create([
            'siswa_id' => $siswa3->id, 'raport' => true, 'kartu_keluarga' => true, 'akte_kelahiran' => false, 'ijazah' => false
        ]);

        // Siswa 4: Baru Memulai (1) - kurang KK, Akte Kelahiran, Ijazah
        $siswa4 = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id, 'nis' => '10004', 'nama_lengkap' => 'Siswa Baru Memulai', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'
        ]);
        DaftarUlangChecklist::create([
            'siswa_id' => $siswa4->id, 'raport' => true, 'kartu_keluarga' => false, 'akte_kelahiran' => false, 'ijazah' => false
        ]);

        // Siswa 5: Belum Kumpul (0)
        $siswa5 = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id, 'nis' => '10005', 'nama_lengkap' => 'Siswa Belum Kumpul', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'
        ]);
        DaftarUlangChecklist::create([
            'siswa_id' => $siswa5->id, 'raport' => false, 'kartu_keluarga' => false, 'akte_kelahiran' => false, 'ijazah' => false
        ]);

        // Test scopeWhereKelompokKelengkapan
        $this->assertEquals(1, DaftarUlangSiswa::whereKelompokKelengkapan('lengkap')->count());
        $this->assertEquals(1, DaftarUlangSiswa::whereKelompokKelengkapan('hampir_lengkap')->count());
        $this->assertEquals(1, DaftarUlangSiswa::whereKelompokKelengkapan('setengah_lengkap')->count());
        $this->assertEquals(1, DaftarUlangSiswa::whereKelompokKelengkapan('baru_memulai')->count());
        $this->assertEquals(1, DaftarUlangSiswa::whereKelompokKelengkapan('belum_kumpul')->count());

        // Test scopeWhereKurangBerkas
        // Ijazah kurang di Siswa 2, 3, 4, 5 (Total 4 siswa)
        $this->assertEquals(4, DaftarUlangSiswa::whereKurangBerkas('ijazah')->count());
        // Akte Kelahiran kurang di Siswa 3, 4, 5 (Total 3 siswa)
        $this->assertEquals(3, DaftarUlangSiswa::whereKurangBerkas('akte_kelahiran')->count());
        // Kartu Keluarga kurang di Siswa 4, 5 (Total 2 siswa)
        $this->assertEquals(2, DaftarUlangSiswa::whereKurangBerkas('kartu_keluarga')->count());
        // Raport kurang di Siswa 5 (Total 1 siswa)
        $this->assertEquals(1, DaftarUlangSiswa::whereKurangBerkas('raport')->count());
    }

    /**
     * 3. Pastikan controller DaftarUlangController memberikan response yang sesuai dengan format PRD-005 saat memanggil stats(), index(), dan updateChecklist() via API/JSON.
     */
    public function test_controller_json_responses(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 7, 8));

        // Create initial data
        $siswa = DaftarUlangSiswa::create([
            'periode_id' => $this->periodeXI->id,
            'nis' => '12345',
            'nama_lengkap' => 'Muhammad Budi',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        $checklist = DaftarUlangChecklist::create([
            'siswa_id' => $siswa->id,
            'raport' => true,
            'kartu_keluarga' => true,
            'akte_kelahiran' => false,
            'ijazah' => false,
        ]);

        // TEST INDEX API/JSON
        $responseIndex = $this->actingAs($this->admin)
            ->getJson(route('admin.daftar-ulang.index', ['kelas' => 'XI']));

        $responseIndex->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'nis',
                            'nama_lengkap',
                            'kelas_asal',
                            'kelas_tujuan',
                            'jurusan',
                            'checklist' => [
                                'raport',
                                'kartu_keluarga',
                                'akte_kelahiran',
                                'ijazah',
                                'status',
                                'verified_at',
                                'skor_kelengkapan',
                                'nama_kelompok',
                                'kurang_item',
                            ],
                            'verified_by_name',
                        ]
                    ],
                    'total',
                ],
                'stats' => [
                    'total',
                    'lengkap',
                    'belum',
                    'persen',
                    'kelompok_counts' => [
                        'lengkap',
                        'hampir_lengkap',
                        'setengah_lengkap',
                        'baru_memulai',
                        'belum_kumpul',
                    ],
                    'berkas_kurang' => [
                        'raport',
                        'kartu_keluarga',
                        'akte_kelahiran',
                        'ijazah',
                    ],
                ]
            ]);

        // TEST STATS API/JSON
        $responseStats = $this->actingAs($this->admin)
            ->getJson(route('admin.daftar-ulang.stats'));

        $responseStats->assertOk()
            ->assertJsonStructure([
                'success',
                'stats' => [
                    'total',
                    'lengkap',
                    'belum',
                    'persen',
                    'total_xi',
                    'lengkap_xi',
                    'belum_xi',
                    'persen_xi',
                    'total_xii',
                    'lengkap_xii',
                    'belum_xii',
                    'persen_xii',
                    'kelompok_counts',
                    'statistik_kelompok',
                    'berkas_kurang',
                    'ringkasan_kurang',
                ]
            ]);

        // TEST UPDATE CHECKLIST API/JSON
        $responseUpdate = $this->actingAs($this->admin)
            ->postJson(route('admin.daftar-ulang.update-checklist', $siswa->id), [
                'raport' => true,
                'kartu_keluarga' => true,
                'akte_kelahiran' => true,
                'ijazah' => true,
            ]);

        $responseUpdate->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'siswa_id',
                    'raport',
                    'kartu_keluarga',
                    'akte_kelahiran',
                    'ijazah',
                    'status',
                    'verified_by_name',
                    'verified_at',
                    'skor_kelengkapan',
                    'nama_kelompok',
                    'kurang_item',
                    'segmentasi' => [
                        'skor_kelengkapan',
                        'nama_kelompok',
                        'kurang_item',
                    ]
                ],
                'stats' => [
                    'total',
                    'lengkap',
                    'belum',
                    'persen',
                    'kelompok_counts',
                    'statistik_kelompok',
                    'berkas_kurang',
                    'ringkasan_kurang',
                ]
            ]);

        Carbon::setTestNow();
    }
}
