<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->longText('content')->change();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->longText('content')->change();
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
        });

        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->longText('description')->change();
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->text('content')->change();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->text('content')->change();
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->text('description')->change();
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }
};
