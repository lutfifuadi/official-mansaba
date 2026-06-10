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
            [
                'name' => 'Layanan PTSP',
                'slug' => 'layanan-ptsp',
                'icon' => 'building-arch',
                'url' => 'https://ptsp.man1kotabandung.sch.id',
                'category' => 'Administrasi',
                'sort_order' => 1,
                'is_active' => true,
                'description' => 'Pelayanan Terpadu Satu Pintu untuk administrasi madrasah',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan E-Surat',
                'slug' => 'layanan-e-surat',
                'icon' => 'file-text',
                'url' => 'https://esurat.man1kotabandung.sch.id',
                'category' => 'Administrasi',
                'sort_order' => 2,
                'is_active' => true,
                'description' => 'Surat menyurat digital madrasah',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan Lokal EMIS',
                'slug' => 'layanan-lokal-emis',
                'icon' => 'database',
                'url' => 'https://emis.man1kotabandung.sch.id',
                'category' => 'Akademik',
                'sort_order' => 3,
                'is_active' => true,
                'description' => 'Education Management Information System madrasah',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan Presensi Online',
                'slug' => 'layanan-presensi-online',
                'icon' => 'clipboard-check',
                'url' => 'https://presensi.man1kotabandung.sch.id',
                'category' => 'Akademik',
                'sort_order' => 4,
                'is_active' => true,
                'description' => 'Presensi kehadiran online',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan Raport Digital Madrasah',
                'slug' => 'layanan-raport-digital',
                'icon' => 'certificate',
                'url' => 'https://rdm.man1kotabandung.sch.id',
                'category' => 'Akademik',
                'sort_order' => 5,
                'is_active' => true,
                'description' => 'RDM untuk pengelolaan raport digital',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan Sistem Kelulusan',
                'slug' => 'layanan-sistem-kelulusan',
                'icon' => 'user-check',
                'url' => 'https://skl.man1kotabandung.sch.id',
                'category' => 'Kesiswaan',
                'sort_order' => 6,
                'is_active' => true,
                'description' => 'Sistem informasi kelulusan siswa',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan PMBM',
                'slug' => 'layanan-pmbm',
                'icon' => 'user-plus',
                'url' => 'https://pmbm.man1kotabandung.sch.id',
                'category' => 'Kesiswaan',
                'sort_order' => 7,
                'is_active' => true,
                'description' => 'Penerimaan Murid Baru Madrasah (PPDB)',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
            [
                'name' => 'Layanan Kesiswaan',
                'slug' => 'layanan-kesiswaan',
                'icon' => 'users',
                'url' => 'https://kesiswaan.man1kotabandung.sch.id',
                'category' => 'Kesiswaan',
                'sort_order' => 8,
                'is_active' => true,
                'description' => 'Layanan informasi kesiswaan',
                'contact_person' => null,
                'procedures' => null,
                'requirements' => null,
                'icon_color' => null,
            ],
        ];

        foreach ($services as $svc) {
            Service::updateOrCreate(
                ['slug' => $svc['slug']],
                $svc
            );
        }
    }
}
