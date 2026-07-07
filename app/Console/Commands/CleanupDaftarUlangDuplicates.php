<?php

namespace App\Console\Commands;

use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangChecklist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupDaftarUlangDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daftar-ulang:cleanup-duplicates
                            {--periode= : ID periode tertentu (kosongkan untuk semua periode)}
                            {--dry-run : Hanya tampilkan duplikat tanpa menghapus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan data duplikat siswa daftar ulang (NIS+periode_id yang sama)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== CLEANUP DUPLIKAT DAFTAR ULANG ===');
        $this->newLine();

        // Bangun query untuk cari duplikat
        $query = DaftarUlangSiswa::select('nis', 'periode_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('nis', 'periode_id')
            ->having('cnt', '>', 1);

        if ($periodeId = $this->option('periode')) {
            $query->where('periode_id', (int) $periodeId);
            $this->line("Filter periode ID: {$periodeId}");
        }

        $duplicates = $query->get();

        if ($duplicates->isEmpty()) {
            $this->info('✓ Tidak ditemukan data duplikat.');
            return 0;
        }

        $this->warn("Ditemukan {$duplicates->count()} kelompok data duplikat.");
        $this->newLine();

        $totalDeleted = 0;
        $dryRun = $this->option('dry-run');

        foreach ($duplicates as $dup) {
            // Ambil semua record duplikat, urutkan by ID ascending
            $records = DaftarUlangSiswa::where('nis', $dup->nis)
                ->where('periode_id', $dup->periode_id)
                ->orderBy('id')
                ->get();

            $keep = $records->shift(); // record pertama (ID terkecil) dipertahankan
            $deleteCount = $records->count();

            $this->line("  NIS: {$dup->nis} | Periode: {$dup->periode_id} | Pertahankan ID: {$keep->id} | Hapus {$deleteCount} duplikat");

            if (!$dryRun) {
                foreach ($records as $record) {
                    // Hapus checklist terkait dulu
                    DaftarUlangChecklist::where('siswa_id', $record->id)->delete();
                    $record->delete();
                    $totalDeleted++;
                }

                Log::info("Cleanup: duplikat NIS {$dup->nis} periode {$dup->periode_id}: menyisakan ID {$keep->id}, hapus {$deleteCount} duplikat.");
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info("✓ Dry-run selesai. {$duplicates->count()} kelompok duplikat ditemukan, belum ada yang dihapus.");
            $this->info("  Jalankan tanpa --dry-run untuk benar-benar menghapus.");
        } else {
            $this->info("✓ Selesai! {$totalDeleted} data duplikat berhasil dihapus.");
        }

        return 0;
    }
}
