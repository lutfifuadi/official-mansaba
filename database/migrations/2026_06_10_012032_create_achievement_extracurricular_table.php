<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievement_extracurricular', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('extracurricular_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['achievement_id', 'extracurricular_id'], 'achv_ekskul_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievement_extracurricular');
    }
};
