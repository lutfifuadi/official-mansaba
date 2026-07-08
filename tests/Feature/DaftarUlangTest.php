<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DaftarUlangPeriode;
use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangChecklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class DaftarUlangTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $admin;
    protected User $operator;
    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\Event::fake([
            \App\Events\DaftarUlangChecklistUpdated::class,
        ]);

        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->operator = User::factory()->create(['role' => 'operator']);
        $this->editor = User::factory()->create(['role' => 'editor']);
    }

    public function test_super_admin_can_manage_periods(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.daftar-ulang-periode.store'), [
                'tahun_ajaran' => '2026/2027',
                'kelas_target' => 'XI',
                'tanggal_buka' => '2026-06-01',
                'tanggal_tutup' => '2026-08-31',
                'is_active' => true,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('daftar_ulang_periode', [
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'is_active' => true,
        ]);
    }

    public function test_activating_period_deactivates_other_periods_of_same_class_target(): void
    {
        // 1. Buat satu periode kelas 'XI' yang aktif
        $firstPeriod = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2025/2026',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2025-06-01',
            'tanggal_tutup' => '2025-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        // 2. Jalankan request store untuk membuat periode kelas 'XI' baru yang aktif
        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.daftar-ulang-periode.store'), [
                'tahun_ajaran' => '2026/2027',
                'kelas_target' => 'XI',
                'tanggal_buka' => '2026-06-01',
                'tanggal_tutup' => '2026-08-31',
                'is_active' => true,
            ]);

        $response->assertRedirect();

        // 3. Pastikan di database, periode kelas 'XI' yang pertama otomatis berubah menjadi is_active = false
        $this->assertDatabaseHas('daftar_ulang_periode', [
            'id' => $firstPeriod->id,
            'is_active' => false,
        ]);

        // Dan periode baru aktif
        $this->assertDatabaseHas('daftar_ulang_periode', [
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'is_active' => true,
        ]);
    }

    public function test_editor_cannot_manage_periods(): void
    {
        $response = $this->actingAs($this->editor)
            ->post(route('admin.daftar-ulang-periode.store'), [
                'tahun_ajaran' => '2026/2027',
                'kelas_target' => 'XI',
                'tanggal_buka' => '2026-06-01',
                'tanggal_tutup' => '2026-08-31',
                'is_active' => true,
            ]);

        $response->assertStatus(403);
    }

    public function test_creating_siswa_automatically_creates_checklist(): void
    {
        $periode = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.daftar-ulang-siswa.store'), [
                'periode_id' => $periode->id,
                'nis' => '12345',
                'nama_lengkap' => 'John Doe',
                'kelas_asal' => 'X',
                'kelas_tujuan' => 'XI',
                'jurusan' => 'IPA',
            ]);

        $response->assertRedirect(route('admin.daftar-ulang-siswa.index'));
        $this->assertDatabaseHas('daftar_ulang_siswa', [
            'nis' => '12345',
            'nama_lengkap' => 'John Doe',
        ]);

        $siswa = DaftarUlangSiswa::where('nis', '12345')->first();
        $this->assertNotNull($siswa);
        $this->assertDatabaseHas('daftar_ulang_checklist', [
            'siswa_id' => $siswa->id,
            'status' => 'belum_lengkap',
        ]);
    }

    public function test_updating_checklist_respects_active_period(): void
    {
        // 1. Set today's date context for testing.
        Carbon::setTestNow(Carbon::create(2026, 7, 6));

        // Period is active
        $periode = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $siswa = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '12345',
            'nama_lengkap' => 'John Doe',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        $checklist = DaftarUlangChecklist::create([
            'siswa_id' => $siswa->id,
            'raport' => false,
            'kartu_keluarga' => false,
            'akte_kelahiran' => false,
            'ijazah' => false,
            'status' => 'belum_lengkap',
        ]);

        // Request update checklist
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.daftar-ulang.update-checklist', $siswa->id), [
                'raport' => true,
                'kartu_keluarga' => true,
                'akte_kelahiran' => true,
                'ijazah' => true,
            ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'lengkap');

        $this->assertDatabaseHas('daftar_ulang_checklist', [
            'siswa_id' => $siswa->id,
            'status' => 'lengkap',
            'verified_by' => $this->admin->id,
        ]);

        // Period is inactive (out of range date)
        Carbon::setTestNow(Carbon::create(2026, 9, 1));
        
        $response2 = $this->actingAs($this->admin)
            ->postJson(route('admin.daftar-ulang.update-checklist', $siswa->id), [
                'raport' => false,
                'kartu_keluarga' => false,
                'akte_kelahiran' => false,
                'ijazah' => false,
            ]);

        $response2->assertStatus(422);

        // Clear test now
        Carbon::setTestNow();
    }

    public function test_live_search_returns_json_response_when_ajax_requested(): void
    {
        $periode = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $siswaA = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '123456',
            'nama_lengkap' => 'Muhammad Budi',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        $siswaB = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '654321',
            'nama_lengkap' => 'Andi Susanto',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPS',
        ]);

        DaftarUlangChecklist::create([
            'siswa_id' => $siswaA->id,
            'raport' => true,
            'kartu_keluarga' => true,
            'akte_kelahiran' => true,
            'ijazah' => true,
            'status' => 'lengkap',
            'verified_by' => $this->admin->id,
            'verified_at' => now(),
        ]);

        // 1. Test search with AJAX headers
        $response = $this->actingAs($this->admin)
            ->get(route('admin.daftar-ulang.index', [
                'search' => 'Budi',
                'kelas' => 'XI'
            ]), [
                'X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertOk();
        $response->assertJsonStructure([
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
                        ],
                        'verified_by_name',
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]
        ]);

        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('Muhammad Budi', $data[0]['nama_lengkap']);
        $this->assertEquals($this->admin->name, $data[0]['verified_by_name']);

        // 2. Test search with character count < 3 is ignored (returns both)
        $responseShortSearch = $this->actingAs($this->admin)
            ->get(route('admin.daftar-ulang.index', [
                'search' => 'Bu',
                'kelas' => 'XI'
            ]), [
                'X-Requested-With' => 'XMLHttpRequest'
            ]);

        $responseShortSearch->assertOk();
        $this->assertCount(2, $responseShortSearch->json('data.data'));

        // 3. Test filter status
        $responseStatus = $this->actingAs($this->admin)
            ->get(route('admin.daftar-ulang.index', [
                'status' => 'lengkap',
                'kelas' => 'XI'
            ]), [
                'X-Requested-With' => 'XMLHttpRequest'
            ]);

        $responseStatus->assertOk();
        $this->assertCount(1, $responseStatus->json('data.data'));
        $this->assertEquals('Muhammad Budi', $responseStatus->json('data.data.0.nama_lengkap'));
    }

    public function test_super_admin_can_reset_all_daftar_ulang_data(): void
    {
        $periode = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $siswa = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '123456',
            'nama_lengkap' => 'Muhammad Budi',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        DaftarUlangChecklist::create([
            'siswa_id' => $siswa->id,
            'raport' => true,
            'kartu_keluarga' => true,
            'akte_kelahiran' => true,
            'ijazah' => true,
            'status' => 'lengkap',
            'verified_by' => $this->admin->id,
            'verified_at' => now(),
        ]);

        $this->assertDatabaseCount('daftar_ulang_siswa', 1);
        $this->assertDatabaseCount('daftar_ulang_checklist', 1);

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.daftar-ulang.reset'));

        $response->assertRedirect(route('admin.daftar-ulang.index'));
        
        $this->assertDatabaseHas('daftar_ulang_siswa', ['id' => $siswa->id]);
        $this->assertDatabaseHas('daftar_ulang_checklist', [
            'siswa_id' => $siswa->id,
            'raport' => false,
            'status' => 'belum_lengkap',
            'verified_by' => null
        ]);
    }

    public function test_editor_cannot_reset_all_daftar_ulang_data(): void
    {
        $periode = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $siswa = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '123456',
            'nama_lengkap' => 'Muhammad Budi',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        DaftarUlangChecklist::create([
            'siswa_id' => $siswa->id,
            'raport' => true,
            'kartu_keluarga' => true,
            'akte_kelahiran' => true,
            'ijazah' => true,
            'status' => 'lengkap',
            'verified_by' => $this->admin->id,
            'verified_at' => now(),
        ]);

        $this->assertDatabaseCount('daftar_ulang_siswa', 1);
        $this->assertDatabaseCount('daftar_ulang_checklist', 1);

        // Try as editor
        $responseEditor = $this->actingAs($this->editor)
            ->post(route('admin.daftar-ulang.reset'));

        $responseEditor->assertStatus(403);
        $this->assertDatabaseCount('daftar_ulang_siswa', 1);
        $this->assertDatabaseHas('daftar_ulang_checklist', [
            'siswa_id' => $siswa->id,
            'raport' => true,
            'status' => 'lengkap',
            'verified_by' => $this->admin->id,
        ]);
    }

    public function test_admin_and_operator_can_manage_periods_reset_data_and_delete_siswa(): void
    {
        $periode = DaftarUlangPeriode::create([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => '2026-06-01',
            'tanggal_tutup' => '2026-08-31',
            'is_active' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $siswa = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '123456',
            'nama_lengkap' => 'Muhammad Budi',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);

        DaftarUlangChecklist::create([
            'siswa_id' => $siswa->id,
            'raport' => true,
            'kartu_keluarga' => true,
            'akte_kelahiran' => true,
            'ijazah' => true,
            'status' => 'lengkap',
            'verified_by' => $this->admin->id,
            'verified_at' => now(),
        ]);

        // 1. Admin can store a period
        $responseAdminPeriod = $this->actingAs($this->admin)
            ->post(route('admin.daftar-ulang-periode.store'), [
                'tahun_ajaran' => '2027/2028',
                'kelas_target' => 'XI',
                'tanggal_buka' => '2027-06-01',
                'tanggal_tutup' => '2027-08-31',
                'is_active' => true,
            ]);
        $responseAdminPeriod->assertRedirect();
        $this->assertDatabaseHas('daftar_ulang_periode', ['tahun_ajaran' => '2027/2028']);

        // 2. Operator can store a period
        $responseOperatorPeriod = $this->actingAs($this->operator)
            ->post(route('admin.daftar-ulang-periode.store'), [
                'tahun_ajaran' => '2028/2029',
                'kelas_target' => 'XI',
                'tanggal_buka' => '2028-06-01',
                'tanggal_tutup' => '2028-08-31',
                'is_active' => true,
            ]);
        $responseOperatorPeriod->assertRedirect();
        $this->assertDatabaseHas('daftar_ulang_periode', ['tahun_ajaran' => '2028/2029']);

        // 3. Admin can reset data
        $responseAdminReset = $this->actingAs($this->admin)
            ->post(route('admin.daftar-ulang.reset'));
        $responseAdminReset->assertRedirect(route('admin.daftar-ulang.index'));
        $this->assertDatabaseHas('daftar_ulang_checklist', [
            'siswa_id' => $siswa->id,
            'raport' => false,
            'status' => 'belum_lengkap'
        ]);

        // 4. Operator can reset data
        // Set checklist to true again first
        DaftarUlangChecklist::where('siswa_id', $siswa->id)->update([
            'raport' => true,
            'status' => 'lengkap'
        ]);
        $responseOperatorReset = $this->actingAs($this->operator)
            ->post(route('admin.daftar-ulang.reset'));
        $responseOperatorReset->assertRedirect(route('admin.daftar-ulang.index'));
        $this->assertDatabaseHas('daftar_ulang_checklist', [
            'siswa_id' => $siswa->id,
            'raport' => false,
            'status' => 'belum_lengkap'
        ]);

        // 5. Admin can delete a student
        $siswa2 = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '11111',
            'nama_lengkap' => 'Siswa Admin Delete',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);
        $responseAdminDelete = $this->actingAs($this->admin)
            ->delete(route('admin.daftar-ulang-siswa.destroy', $siswa2->id));
        $responseAdminDelete->assertRedirect(route('admin.daftar-ulang-siswa.index'));
        $this->assertDatabaseMissing('daftar_ulang_siswa', ['id' => $siswa2->id]);

        // 6. Operator can delete a student
        $siswa3 = DaftarUlangSiswa::create([
            'periode_id' => $periode->id,
            'nis' => '22222',
            'nama_lengkap' => 'Siswa Operator Delete',
            'kelas_asal' => 'X',
            'kelas_tujuan' => 'XI',
            'jurusan' => 'IPA',
        ]);
        $responseOperatorDelete = $this->actingAs($this->operator)
            ->delete(route('admin.daftar-ulang-siswa.destroy', $siswa3->id));
        $responseOperatorDelete->assertRedirect(route('admin.daftar-ulang-siswa.index'));
        $this->assertDatabaseMissing('daftar_ulang_siswa', ['id' => $siswa3->id]);
    }
}
