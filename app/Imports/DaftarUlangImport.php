<?php

namespace App\Imports;

use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangChecklist;
use App\Models\DaftarUlangPeriode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DaftarUlangImport
{
    /**
     * Periode ID untuk mengaitkan data import.
     */
    protected int $periodeId;

    /**
     * Tahun ajaran (diambil dari periode) untuk generate NIS.
     */
    protected string $tahunAjaran;

    /**
     * Kelas target (diambil dari periode) untuk validasi.
     */
    protected string $kelasTarget;

    /**
     * Counter hasil import.
     */
    public array $results = [
        'success' => 0,
        'skipped' => 0,
        'errors' => [],
        'sheets_processed' => 0,
    ];

    /**
     * Mapping jurusan dari nomor sheet/kelas.
     * XI F1-F6 → IPA, F7-F8 → IPS, F9 → Keagamaan, F10 → TKJ, F11 → TBSM, F12 → TABUS
     */
    protected array $jurusanMapping = [
        1 => 'IPA', 2 => 'IPA', 3 => 'IPA', 4 => 'IPA', 5 => 'IPA', 6 => 'IPA',
        7 => 'IPS', 8 => 'IPS',
        9 => 'Keagamaan',
        10 => 'TKJ',
        11 => 'TBSM',
        12 => 'TABUS',
    ];

    /**
     * @param int $periodeId
     */
    public function __construct(int $periodeId)
    {
        $this->periodeId = $periodeId;

        // Ambil info periode untuk validasi dan generate NIS
        $periode = DaftarUlangPeriode::findOrFail($periodeId);
        $this->tahunAjaran = $periode->tahun_ajaran;
        $this->kelasTarget = $periode->kelas_target;
    }

    /**
     * Import data dari file Excel.
     *
     * @param string $filePath Path ke file Excel
     * @return array Results
     */
    public function import(string $filePath): array
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetNames = $spreadsheet->getSheetNames();

            foreach ($sheetNames as $sheetIndex => $sheetName) {
                $sheet = $spreadsheet->getSheet($sheetIndex);
                $this->processSheet($sheetName, $sheet);
            }

            $spreadsheet->disconnectWorksheets();
        } catch (\Exception $e) {
            $this->results['errors'][] = 'Gagal membaca file Excel: ' . $e->getMessage();
            Log::error('Gagal import Excel: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $this->results;
    }

    /**
     * Process a single sheet.
     * Format sheet:
     *   Row 1: Nama kelas (e.g., "KELAS XI F1")
     *   Row 2: Jurusan (e.g., "SAINS TEKNIK 1 (MAT, FIS, KIM, BIO)")
     *   Row 3: Info jumlah L/P
     *   Row 4: Header: NO | NAMA | KELAS X | KELAS XI | JK
     *   Row 5: Separator (----)
     *   Row 6+: Data siswa
     *
     * @param string $sheetName
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    protected function processSheet(string $sheetName, $sheet): void
    {
        $sheetName = trim($sheetName);

        // Ekstrak nomor sheet dari nama sheet (e.g., "XI F1" → 1, "XI F12" → 12)
        $sheetNumber = $this->extractSheetNumber($sheetName);
        if ($sheetNumber === null || !isset($this->jurusanMapping[$sheetNumber])) {
            $this->results['errors'][] = "Sheet '{$sheetName}' tidak dikenali, dilewati.";
            return;
        }

        $jurusan = $this->jurusanMapping[$sheetNumber];
        $highestRow = $sheet->getHighestRow();

        if ($highestRow < 6) {
            $this->results['errors'][] = "Sheet '{$sheetName}' tidak memiliki data siswa, dilewati.";
            return;
        }

        $this->results['sheets_processed']++;

        DB::beginTransaction();
        try {
            // Data dimulai dari row 6
            $rowIndex = 0; // index dalam sheet untuk generate NIS
            for ($row = 6; $row <= $highestRow; $row++) {
                $nama = $this->getCellValue($sheet, $row, 'B');
                $kelasX = $this->getCellValue($sheet, $row, 'C');
                $kelasXI = $this->getCellValue($sheet, $row, 'D');

                // Skip baris kosong
                if (empty($nama) || empty($kelasX)) {
                    continue;
                }

                // Bersihkan nama
                $nama = trim($nama);

                // Ekstrak kelas_asal dari "X E-2" → "X"
                $kelasAsal = $this->extractKelasAsal($kelasX);

                // Validasi kelas asal
                if (!in_array($kelasAsal, ['X', 'XI'])) {
                    $this->results['errors'][] = "Sheet '{$sheetName}', siswa '{$nama}': kelas asal '{$kelasAsal}' tidak valid.";
                    $this->results['skipped']++;
                    continue;
                }

                // Validasi kelas tujuan harus XI (karena data Excel untuk pendaftaran ke XI)
                if ($this->kelasTarget !== 'XI') {
                    $this->results['errors'][] = "Sheet '{$sheetName}', siswa '{$nama}': kelas target tidak sesuai periode.";
                    $this->results['skipped']++;
                    continue;
                }

                // Generate NIS
                $nis = $this->generateNis($sheetNumber, $rowIndex);

                // Cek duplikat NIS dalam periode yang sama — SKIP jika sudah ada
                $exists = DaftarUlangSiswa::where('nis', $nis)
                    ->where('periode_id', $this->periodeId)
                    ->exists();

                if ($exists) {
                    // Data sudah ada di periode ini, skip (idempoten)
                    $this->results['skipped']++;
                    $rowIndex++;
                    continue;
                }

                // Buat record siswa
                $siswa = DaftarUlangSiswa::create([
                    'periode_id' => $this->periodeId,
                    'nis' => $nis,
                    'nama_lengkap' => $nama,
                    'kelas_asal' => $kelasAsal,
                    'kelas_tujuan' => $this->kelasTarget,
                    'jurusan' => $jurusan,
                ]);

                // Buat checklist default
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

                $this->results['success']++;
                $rowIndex++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->results['errors'][] = "Sheet '{$sheetName}' gagal diproses: " . $e->getMessage();
            Log::error("Gagal import sheet {$sheetName}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Ekstrak nomor sheet dari nama sheet seperti "XI F1" → 1, "XI F12" → 12.
     */
    protected function extractSheetNumber(string $sheetName): ?int
    {
        // Pattern: "XI F" diikuti angka (satu atau dua digit)
        if (preg_match('/F(\d+)$/i', $sheetName, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    /**
     * Ekstrak kelas asal dari string seperti "X E-2" → "X".
     */
    protected function extractKelasAsal(string $kelasX): string
    {
        $kelasX = trim($kelasX);
        // Ambil bagian pertama (X atau XI)
        if (preg_match('/^(X[I]?)/', $kelasX, $matches)) {
            return $matches[1];
        }
        return 'X'; // default
    }

    /**
     * Reset / hapus semua data siswa dan checklist untuk periode tertentu.
     * Digunakan saat mode --force: import ulang dari awal.
     */
    public function resetPeriodeData(): int
    {
        $siswaIds = DaftarUlangSiswa::where('periode_id', $this->periodeId)->pluck('id');

        $deletedChecklist = DaftarUlangChecklist::whereIn('siswa_id', $siswaIds)->delete();
        $deletedSiswa = DaftarUlangSiswa::where('periode_id', $this->periodeId)->delete();

        Log::info("Reset data periode {$this->periodeId}: {$deletedSiswa} siswa + {$deletedChecklist} checklist dihapus.");
        return $deletedSiswa;
    }

    /**
     * Hapus data duplikat — siswa dengan NIS+periode_id yang sama.
     * Hanya menyisakan 1 record (yang pertama / ID terkecil).
     *
     * @return int Jumlah duplikat yang dihapus
     */
    public function cleanupDuplicates(): int
    {
        $deletedCount = 0;

        // Cari duplikat: NIS + periode_id muncul lebih dari 1 kali
        $duplicates = DaftarUlangSiswa::select('nis', 'periode_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('nis', 'periode_id')
            ->having('cnt', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            // Ambil semua record, urutkan by ID ascending
            $records = DaftarUlangSiswa::where('nis', $dup->nis)
                ->where('periode_id', $dup->periode_id)
                ->orderBy('id')
                ->get();

            // Sisakan record pertama, hapus sisanya
            $keep = $records->shift();
            foreach ($records as $record) {
                // Hapus checklist terkait dulu
                DaftarUlangChecklist::where('siswa_id', $record->id)->delete();
                $record->delete();
                $deletedCount++;
            }

            Log::info("Cleanup: duplikat NIS {$dup->nis} periode {$dup->periode_id}: menyisakan ID {$keep->id}, hapus {$records->count()} duplikat.");
        }

        return $deletedCount;
    }

    /**
     * Generate NIS otomatis.
     * Format: {tahun_ajaran_awal}{kelas_tujuan}F{sheet_no2digit}{no_urut3digit}
     * Contoh: 2026XIF01001
     */
    protected function generateNis(int $sheetNumber, int $rowIndex): string
    {
        // Ambil tahun awal dari tahun_ajaran (e.g., "2026/2027" → "2026")
        $tahunAwal = substr($this->tahunAjaran, 0, 4);

        $sheetNum = str_pad($sheetNumber, 2, '0', STR_PAD_LEFT);

        // Nomor urut dalam sheet (1-based, 3 digit)
        $urutan = $rowIndex + 1;
        $noUrut = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return "{$tahunAwal}{$this->kelasTarget}F{$sheetNum}{$noUrut}";
    }

    /**
     * Ambil nilai cell dengan aman.
     */
    protected function getCellValue($sheet, int $row, string $column): string
    {
        try {
            $cell = $sheet->getCell($column . $row);
            $value = $cell->getCalculatedValue();
            if ($value === null) {
                return '';
            }
            return trim((string) $value);
        } catch (\Exception $e) {
            return '';
        }
    }
}
