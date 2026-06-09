<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'PTSP',             'icon' => 'building-arch',  'url' => '#', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'ESurat',           'icon' => 'file-text',      'url' => '#', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Presensi Online',  'icon' => 'user-check',     'url' => '#', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Ujian Online',     'icon' => 'edit',           'url' => '#', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'RDM',              'icon' => 'book',           'url' => '#', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Lokal EMIS',       'icon' => 'database',       'url' => '#', 'sort_order' => 6, 'is_active' => true],
        ];

        foreach ($services as $svc) {
            Service::updateOrCreate(
                ['slug' => Str::slug($svc['name'])],
                $svc
            );
        }
    }
}
