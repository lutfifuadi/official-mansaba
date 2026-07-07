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
        Schema::create('daftar_ulang_checklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->unique()->constrained('daftar_ulang_siswa')->onDelete('cascade');
            $table->boolean('raport')->default(false);
            $table->boolean('kartu_keluarga')->default(false);
            $table->boolean('akte_kelahiran')->default(false);
            $table->boolean('ijazah')->default(false);
            $table->enum('status', ['lengkap', 'belum_lengkap'])->default('belum_lengkap');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_ulang_checklist');
    }
};
