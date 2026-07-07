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
        Schema::create('daftar_ulang_periode', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 20);
            $table->enum('kelas_target', ['XI', 'XII']);
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tahun_ajaran', 'kelas_target']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_ulang_periode');
    }
};
