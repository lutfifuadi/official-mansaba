<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DaftarUlangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get an existing user ID to set as creator/verifier (e.g. superadmin or first user)
        $user = DB::table('users')->first();
        $userId = $user ? $user->id : null;

        // Today is Mon Jul 06 2026.
        // Let's set the active period from 2026-06-01 to 2026-08-31 to ensure today falls inside.
        $tanggalBuka = Carbon::create(2026, 6, 1)->toDateString();
        $tanggalTutup = Carbon::create(2026, 8, 31)->toDateString();

        // 1. Create Active Period for Class XI
        $periodeXIId = DB::table('daftar_ulang_periode')->insertGetId([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XI',
            'tanggal_buka' => $tanggalBuka,
            'tanggal_tutup' => $tanggalTutup,
            'is_active' => true,
            'created_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Active Period for Class XII
        $periodeXIIId = DB::table('daftar_ulang_periode')->insertGetId([
            'tahun_ajaran' => '2026/2027',
            'kelas_target' => 'XII',
            'tanggal_buka' => $tanggalBuka,
            'tanggal_tutup' => $tanggalTutup,
            'is_active' => true,
            'created_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create at least 5 students for Class XI (origin: X, target: XI)
        $siswaXI = [
            ['nis' => '1026001', 'nama_lengkap' => 'Ahmad Hidayat', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'],
            ['nis' => '1026002', 'nama_lengkap' => 'Budi Santoso', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPA'],
            ['nis' => '1026003', 'nama_lengkap' => 'Citra Lestari', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPS'],
            ['nis' => '1026004', 'nama_lengkap' => 'Dewi Sartika', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'IPS'],
            ['nis' => '1026005', 'nama_lengkap' => 'Eko Prasetyo', 'kelas_asal' => 'X', 'kelas_tujuan' => 'XI', 'jurusan' => 'Keagamaan'],
        ];

        // 4. Create at least 5 students for Class XII (origin: XI, target: XII)
        $siswaXII = [
            ['nis' => '1025001', 'nama_lengkap' => 'Fahri Hamzah', 'kelas_asal' => 'XI', 'kelas_tujuan' => 'XII', 'jurusan' => 'IPA'],
            ['nis' => '1025002', 'nama_lengkap' => 'Gita Gutawa', 'kelas_asal' => 'XI', 'kelas_tujuan' => 'XII', 'jurusan' => 'IPA'],
            ['nis' => '1025003', 'nama_lengkap' => 'Hendra Wijaya', 'kelas_asal' => 'XI', 'kelas_tujuan' => 'XII', 'jurusan' => 'IPS'],
            ['nis' => '1025004', 'nama_lengkap' => 'Indah Permata', 'kelas_asal' => 'XI', 'kelas_tujuan' => 'XII', 'jurusan' => 'IPS'],
            ['nis' => '1025005', 'nama_lengkap' => 'Joko Widodo', 'kelas_asal' => 'XI', 'kelas_tujuan' => 'XII', 'jurusan' => 'Keagamaan'],
        ];

        $allSiswa = [];

        foreach ($siswaXI as $siswa) {
            $siswaId = DB::table('daftar_ulang_siswa')->insertGetId(array_merge($siswa, [
                'periode_id' => $periodeXIId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $allSiswa[] = [
                'id' => $siswaId,
                'kelas_tujuan' => 'XI',
            ];
        }

        foreach ($siswaXII as $siswa) {
            $siswaId = DB::table('daftar_ulang_siswa')->insertGetId(array_merge($siswa, [
                'periode_id' => $periodeXIIId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            $allSiswa[] = [
                'id' => $siswaId,
                'kelas_tujuan' => 'XII',
            ];
        }

        // 5. Create checklist records. We need:
        // - At least 2 "lengkap" (all documents true, status: lengkap)
        // - Some "belum_lengkap" (some documents empty/false, status: belum_lengkap)
        
        // Let's say:
        // Siswa 0 & 1 -> Lengkap (all true)
        // Siswa 2, 3, 4, 5, 6, 7, 8, 9 -> Belum Lengkap (various combinations of true/false)

        foreach ($allSiswa as $index => $item) {
            if ($index < 2) {
                // Lengkap
                DB::table('daftar_ulang_checklist')->insert([
                    'siswa_id' => $item['id'],
                    'raport' => true,
                    'kartu_keluarga' => true,
                    'akte_kelahiran' => true,
                    'ijazah' => true,
                    'status' => 'lengkap',
                    'verified_by' => $userId,
                    'verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Belum Lengkap - distribute different stages of completion
                $raport = ($index % 2 == 0);
                $kk = ($index % 3 == 0);
                $akte = ($index % 4 == 0);
                $ijazah = false; // keep at least one false to ensure it's not complete

                DB::table('daftar_ulang_checklist')->insert([
                    'siswa_id' => $item['id'],
                    'raport' => $raport,
                    'kartu_keluarga' => $kk,
                    'akte_kelahiran' => $akte,
                    'ijazah' => $ijazah,
                    'status' => 'belum_lengkap',
                    'verified_by' => null,
                    'verified_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
