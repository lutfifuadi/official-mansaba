<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daftar_ulang_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('daftar_ulang_periode')->onDelete('cascade');
            $table->string('nis', 20);
            $table->string('nama_lengkap', 100);
            $table->enum('kelas_asal', ['X', 'XI']);
            $table->enum('kelas_tujuan', ['XI', 'XII']);
            $table->string('jurusan', 50)->nullable();
            $table->timestamps();

            $table->unique(['nis', 'periode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_ulang_siswa');
    }
};
