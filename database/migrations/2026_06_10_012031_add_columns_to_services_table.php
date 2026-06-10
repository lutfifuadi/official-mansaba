<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('url');
            $table->text('description')->nullable()->after('category');
            $table->string('contact_person', 200)->nullable()->after('description');
            $table->text('procedures')->nullable()->after('contact_person');
            $table->text('requirements')->nullable()->after('procedures');
            $table->string('icon_color', 20)->nullable()->after('requirements');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['category', 'description', 'contact_person', 'procedures', 'requirements', 'icon_color']);
        });
    }
};
