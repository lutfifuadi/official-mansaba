<?php

namespace App\Console\Commands;

use App\Imports\DaftarUlangImport;
use App\Models\DaftarUlangPeriode;
use Illuminate\Console\Command;

class ImportDaftarUlangFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daftar-ulang:import-excel
                            {--file= : Path lengkap ke file Excel}
                            {--periode= : ID periode daftar ulang}
                            {--tahun=2026/2027 : Tahun ajaran untuk mencari periode}
                            {--target=XI : Kelas target (XI atau XII)}
                            {--force : Hapus data periode yang sudah ada sebelum import ulang}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data siswa daftar ulang dari file Excel (format PENEMPATAN-KELAS)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Tentukan file Excel
        $filePath = $this->option('file');
        if (empty($filePath)) {
            $filePath = 'D:\Project\Website\MAN 1 Kota Bandung\Tahun Pelajaran 2026-2027\PENEMPATAN-KELAS-XI-2026-2027.xlsx';
            $this->warn("Menggunakan file default: {$filePath}");
        }

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        // Validasi ekstensi file
        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            $this->error("Ekstensi file tidak valid. File harus berformat: " . implode(', ', $allowedExtensions));
            $this->error("Ekstensi file yang diberikan: .{$extension}");
            return 1;
        }

        // 2. Tentukan periode
        $periodeId = $this->option('periode');
        if (empty($periodeId)) {
            $tahunAjaran = $this->option('tahun');
            $kelasTarget = $this->option('target');
            
            $periode = DaftarUlangPeriode::where('tahun_ajaran', $tahunAjaran)
                ->where('kelas_target', $kelasTarget)
                ->first();
            
            if (!$periode) {
                $this->error("Periode dengan tahun ajaran '{$tahunAjaran}' dan kelas target '{$kelasTarget}' tidak ditemukan.");
                $this->info("Jalankan seeding dulu: php artisan db:seed --class=DaftarUlangSeeder");
                return 1;
            }
            
            $periodeId = $periode->id;
            $this->info("Menggunakan periode: {$periode->tahun_ajaran} - {$periode->kelas_target} (ID: {$periodeId})");
        }

        // 3. Jalankan import
        $this->newLine();
        $this->info('=== IMPORT DATA DAFTAR ULANG DARI EXCEL ===');
        $this->line("File   : {$filePath}");
        $this->line("Periode ID: {$periodeId}");
        $this->newLine();

        $bar = $this->output->createProgressBar(1);
        $bar->start();

        try {
            $import = new DaftarUlangImport((int) $periodeId);

            // Mode --force: reset data periode sebelum import ulang
            if ($this->option('force')) {
                $this->warn("Mode --force: Menghapus data periode {$periodeId} yang sudah ada...");
                $deleted = $import->resetPeriodeData();
                $this->info("✓ {$deleted} data siswa dan checklist dihapus untuk periode ini.");
                $this->newLine();
            }

            $results = $import->import($filePath);
            
            $bar->finish();
            $this->newLine(2);

            $this->line("Sheet diproses : {$results['sheets_processed']}");
            $this->line("Berhasil       : {$results['success']} siswa");
            $this->line("Dilewati       : {$results['skipped']} siswa");

            if (!empty($results['errors'])) {
                $this->newLine();
                $this->warn('Error yang ditemukan (' . count($results['errors']) . '):');
                foreach ($results['errors'] as $error) {
                    $this->error("  - {$error}");
                }
            }

            $this->newLine();
            $this->info("✓ Import selesai!");
            
            return 0;
        } catch (\Exception $e) {
            $bar->finish();
            $this->newLine(2);
            $this->error("Gagal mengimpor data: " . $e->getMessage());
            return 1;
        }
    }
}
