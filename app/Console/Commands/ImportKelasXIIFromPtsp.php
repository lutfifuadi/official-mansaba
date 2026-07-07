<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangChecklist;
use Illuminate\Support\Str;

class ImportKelasXIIFromPtsp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daftar-ulang:import-kelas-xii';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data siswa kelas XI dari PTSP eksternal ke kelas XII daftar ulang di local';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai proses pembersihan data dummy kelas XII...");

        // 1. Bersihkan Data Dummy Kelas XII Local
        // Karena relasinya cascade di migration/database (atau jika tidak, kita bisa menanganinya, tapi instruksi menyebutkan "Karena relasinya cascade, ini otomatis menghapus data di tabel daftar_ulang_checklist terkait")
        try {
            DB::transaction(function () {
                // Pastikan menghapus data daftar_ulang_siswa yang kelas_tujuan = XII
                // Relasi cascade akan otomatis menghapus checklist.
                DaftarUlangSiswa::where('kelas_tujuan', 'XII')->delete();
            });
            $this->info("Berhasil menghapus data dummy kelas XII.");
        } catch (\Exception $e) {
            $this->error("Gagal menghapus data dummy kelas XII: " . $e->getMessage());
            return self::FAILURE;
        }

        // 2. Koneksi & Ambil Data Kelas XI dari PTSP
        $this->info("Menghubungkan ke database PTSP...");
        try {
            $siswaRemote = DB::connection('ptsp')
                ->table('siswa')
                ->where('kelas', 'XI')
                ->get(['nis', 'nama_lengkap', 'jurusan']);
            
            $countRemote = $siswaRemote->count();
            $this->info("Ditemukan {$countRemote} siswa kelas XI di database PTSP.");
            
            if ($countRemote === 0) {
                $this->warn("Tidak ada data siswa kelas XI yang ditemukan di database PTSP.");
                return self::SUCCESS;
            }
        } catch (\Exception $e) {
            $this->error("Gagal terhubung ke database PTSP atau mengambil data: " . $e->getMessage());
            return self::FAILURE;
        }

        // 3. Import Data ke Local Database dengan Transaction
        $this->info("Memulai import data ke database lokal...");
        $bar = $this->output->createProgressBar($countRemote);
        $bar->start();

        $successCount = 0;
        try {
            DB::transaction(function () use ($siswaRemote, $bar, &$successCount) {
                foreach ($siswaRemote as $siswa) {
                    // Cek NIS, jika kosong buat random / incremental
                    $nis = $siswa->nis;
                    if (empty($nis)) {
                        $nis = 'TMP' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                    }

                    // Tentukan Jurusan. Sesuai instruksi: jika nilainya 'UMUM' atau kosong, simpan apa adanya atau konversi ke null/sesuai kebutuhan.
                    // Di model DaftarUlangSiswa, jurusan adalah string nullable.
                    $jurusan = $siswa->jurusan;
                    if (strtoupper($jurusan) === 'UMUM' || empty($jurusan)) {
                        $jurusan = null; // or keep it as is. Let's make it null if empty or keep it if 'UMUM' depends on DB structure. Since instructions say "konversi ke null/sesuai kebutuhan", let's convert to null if empty or UMUM.
                    }

                    // Insert ke daftar_ulang_siswa
                    $siswaLocal = DaftarUlangSiswa::create([
                        'periode_id' => 2,
                        'nis' => $nis,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'kelas_asal' => 'XI',
                        'kelas_tujuan' => 'XII',
                        'jurusan' => $jurusan,
                    ]);

                    // Buat record checklist terkait
                    DaftarUlangChecklist::create([
                        'siswa_id' => $siswaLocal->id,
                        'raport' => false,
                        'kartu_keluarga' => false,
                        'akte_kelahiran' => false,
                        'ijazah' => false,
                        'status' => 'belum_lengkap',
                    ]);

                    $successCount++;
                    $bar->advance();
                }
            });
            $bar->finish();
            $this->newLine();
            
            // 4. Output Log
            $this->info("Sukses mengimport {$successCount} data siswa kelas XII asli dari database PTSP.");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $bar->finish();
            $this->newLine();
            $this->error("Terjadi error saat memproses transaksi database lokal: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
