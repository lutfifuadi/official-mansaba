<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah enum kelas_target pada daftar_ulang_periode
        DB::statement("ALTER TABLE daftar_ulang_periode MODIFY COLUMN kelas_target ENUM('X', 'XI', 'XII') NOT NULL");

        // 2. Ubah kelas_asal dan kelas_tujuan pada daftar_ulang_siswa
        DB::statement("ALTER TABLE daftar_ulang_siswa MODIFY COLUMN kelas_asal VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE daftar_ulang_siswa MODIFY COLUMN kelas_tujuan ENUM('X', 'XI', 'XII') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE daftar_ulang_periode MODIFY COLUMN kelas_target ENUM('XI', 'XII') NOT NULL");
        DB::statement("ALTER TABLE daftar_ulang_siswa MODIFY COLUMN kelas_asal ENUM('X', 'XI') NOT NULL");
        DB::statement("ALTER TABLE daftar_ulang_siswa MODIFY COLUMN kelas_tujuan ENUM('XI', 'XII') NOT NULL");
    }
};