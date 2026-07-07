<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DaftarUlangSiswa;

class SyncNisFromPtsp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daftar-ulang:sync-nis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data NIS siswa dari database eksternal PTSP';

    /**
     * Pengecualian Pemetaan Manual (Manual Mapping)
     *
     * @var array
     */
    protected $manualMapping = [
        "M.HASAN.MA'RUF" => "MUHAMAD HASAN MA`RUF",
        "MOCH.FAJAR ASIDIK" => "MOCHAMAD FAJAR ASIDIK",
        "NADINE OLVIA" => "NADINE OLIVIA",
        "RAZAN HADZIQ NAILAR" => "RAZAN HADZIQ NAILAR RIDHO",
        "ADITIA MUHAMMAD RIZKI" => "ADITIA MUHAMAD RIZKI",
        "DZAKWAN M AWWAAB" => "DZAKWAN MUHADZDZIB AWWAAB",
        "RIDHO NUR ZAMAN" => "RIDO NUR ZAMAN",
        "ALVIN ARRAHAMAN" => "ALVIN ARRAHMAN",
        "DAFASA SYAMIL AHMAD ALHAFIZH" => "DAFASA SYAMIL AHMAD AL-HAFIDZ",
        "HIKAM KHOIRUL RIZAL" => "HIKAM KHOIRU RIZAL",
        "MUHAMMAD HAIDAR DEZAKWAAN" => "MUHAMMAD HAIDAR DZAKWAAN",
        "SATRIA FAUZAN" => "SATRIA FAUZAN UTAMA",
        "ASYRI SYAKIRA" => "ASYRI SYAKIRA DEWI IRAWAN",
        "KANZA HANIFAH" => "KHANZA HANIFAH RAHMAN",
        "NISWA IFFATUL AZKIYA" => "NISWA IFFATUL AZKIA",
        "RAHMA AULIA OKTAVANI" => "RAHMA AULIA OKTAVIANI"
    ];

    /**
     * Normalisasi Nama (Normalize Helper)
     *
     * @param string $name
     * @return string
     */
    private function normalizeName($name)
    {
        $name = strtoupper($name);
        // Ganti singkatan Muhammad / Mochammad yang umum
        $name = str_replace(["MUHAMMAD", "MUH.", "M.", "MOCHAMMAD", "MOCH.", "MCH."], "M ", $name);
        // Hapus spasi ganda, tanda baca, simbol dsb (sisakan huruf A-Z saja)
        return preg_replace('/[^A-Z]/', '', $name);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai proses sinkronisasi NIS dari database PTSP...");

        // 1. Cek koneksi ke database ptsp
        try {
            DB::connection('ptsp')->getPdo();
            $this->info("Koneksi ke database PTSP berhasil.");
        } catch (\Exception $e) {
            $this->error("Koneksi ke database PTSP gagal: " . $e->getMessage());
            return 1;
        }

        // 2. Ambil data siswa dari database eksternal ptsp
        try {
            $ptspSiswa = DB::connection('ptsp')->table('siswa')
                ->select('nis', 'nama_lengkap')
                ->whereNotNull('nis')
                ->where('nis', '<>', '')
                ->get();
            
            $this->info("Ditemukan " . $ptspSiswa->count() . " data siswa di database PTSP.");
        } catch (\Exception $e) {
            $this->error("Gagal mengambil data siswa dari database PTSP: " . $e->getMessage());
            return 1;
        }

        // 3. Buat key map exact dan key map normalized
        $exactMap = [];
        $normalizedMap = [];

        foreach ($ptspSiswa as $siswa) {
            $exactKey = strtoupper(trim($siswa->nama_lengkap));
            $exactMap[$exactKey] = $siswa;

            $normKey = $this->normalizeName($siswa->nama_lengkap);
            if (!empty($normKey)) {
                $normalizedMap[$normKey] = $siswa;
            }
        }

        // 4. Tarik semua siswa local di daftar_ulang_siswa
        $localSiswa = DaftarUlangSiswa::all();
        $totalLocal = $localSiswa->count();
        $this->info("Ditemukan " . $totalLocal . " data siswa lokal.");

        if ($totalLocal === 0) {
            $this->warn("Tidak ada data siswa lokal untuk disinkronkan.");
            return 0;
        }

        $successCount = 0;
        $failedCount = 0;
        $noMatchCount = 0;
        $alreadyHasNisCount = 0;

        $bar = $this->output->createProgressBar($totalLocal);
        $bar->start();

        // Gunakan database transaction agar aman
        DB::beginTransaction();

        try {
            foreach ($localSiswa as $siswaLocal) {
                $namaLocal = trim($siswaLocal->nama_lengkap);
                $namaLocalUpper = strtoupper($namaLocal);
                
                // Cari kecocokan
                $matchedSiswa = null;

                // A. Check manual mapping first
                if (isset($this->manualMapping[$namaLocalUpper])) {
                    $mappedName = $this->manualMapping[$namaLocalUpper];
                    $mappedNameUpper = strtoupper(trim($mappedName));
                    if (isset($exactMap[$mappedNameUpper])) {
                        $matchedSiswa = $exactMap[$mappedNameUpper];
                    }
                }

                // B. Check exact match
                if (!$matchedSiswa && isset($exactMap[$namaLocalUpper])) {
                    $matchedSiswa = $exactMap[$namaLocalUpper];
                }

                // C. Check normalized match
                if (!$matchedSiswa) {
                    $normalizedLocalName = $this->normalizeName($namaLocal);
                    if (isset($normalizedMap[$normalizedLocalName])) {
                        $matchedSiswa = $normalizedMap[$normalizedLocalName];
                    }
                }

                // Update jika ketemu kecocokan
                if ($matchedSiswa) {
                    $newNis = trim($matchedSiswa->nis);
                    if (!empty($newNis)) {
                        if ($siswaLocal->nis === $newNis) {
                            $alreadyHasNisCount++;
                        } else {
                            $siswaLocal->nis = $newNis;
                            $siswaLocal->save();
                            $successCount++;
                        }
                    } else {
                        $failedCount++;
                    }
                } else {
                    $noMatchCount++;
                }

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);

            $this->info("Sinkronisasi selesai!");
            $this->table(
                ['Kategori', 'Jumlah Siswa'],
                [
                    ['NIS Berhasil Diupdate', $successCount],
                    ['NIS Sudah Sesuai (No Change)', $alreadyHasNisCount],
                    ['Gagal (NIS Remote Kosong)', $failedCount],
                    ['Tidak Ada Kecocokan Nama', $noMatchCount],
                    ['Total Siswa Lokal', $totalLocal]
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $bar->finish();
            $this->newLine(2);
            $this->error("Terjadi kesalahan saat menyimpan data: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
