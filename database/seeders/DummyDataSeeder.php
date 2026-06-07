<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNews();
        $this->seedExtracurriculars();
        $this->seedAchievements();
        $this->seedGalleries();
    }

    private function seedNews(): void
    {
        $items = [
            [
                'title' => 'Peringatan Maulid Nabi Muhammad SAW 1446 H',
                'category' => 'Acara',
                'author' => 'Ust. Ahmad Fauzi',
                'published_at' => now()->subDays(1),
                'is_published' => true,
            ],
            [
                'title' => 'Jadwal Ujian Semester Ganjil Tahun Pelajaran 2025/2026',
                'category' => 'Akademik',
                'author' => 'Wakasek Kuri',
                'published_at' => now()->subDays(2),
                'is_published' => true,
            ],
            [
                'title' => 'Siswa MAN 1 Bandung Raih Emas Olimpiade Matematika Nasional',
                'category' => 'Prestasi',
                'author' => 'Tim Humas',
                'published_at' => now()->subDays(3),
                'is_published' => true,
            ],
            [
                'title' => 'Penerimaan Peserta Didik Baru (PPDB) 2026 Telah Dibuka',
                'category' => 'Informasi',
                'author' => 'Tim PPDB',
                'published_at' => now()->subDays(0),
                'is_published' => true,
            ],
            [
                'title' => 'Workshop Literasi Digital bagi Guru dan Karyawan',
                'category' => 'Acara',
                'author' => 'Kepala Perpustakaan',
                'published_at' => now()->subDays(4),
                'is_published' => true,
            ],
            [
                'title' => 'Pembukaan MPLS 2025: Semangat Baru, Generasi Qurani',
                'category' => 'Acara',
                'author' => 'Tim OSIS',
                'published_at' => now()->subDays(5),
                'is_published' => true,
            ],
            [
                'title' => 'Pemberitahuan Libur Semester Ganjil 2025',
                'category' => 'Akademik',
                'author' => 'Tata Usaha',
                'published_at' => now()->subDays(6),
                'is_published' => true,
            ],
            [
                'title' => 'Tim Basket MAN 1 Bandung Juara Turnamen Antar Madrasah',
                'category' => 'Prestasi',
                'author' => 'Pembina Basket',
                'published_at' => now()->subDays(2),
                'is_published' => true,
            ],
            [
                'title' => 'Kegiatan Pramuka Penggalang di Cibodas Lembang',
                'category' => 'Acara',
                'author' => 'Kak Andika',
                'published_at' => now()->subDays(0),
                'is_published' => true,
            ],
            [
                'title' => 'Pengumuman Pembagian Raport Semester Ganjil',
                'category' => 'Akademik',
                'author' => 'Wakasek Kuri',
                'published_at' => null,
                'is_published' => false,
            ],
            [
                'title' => 'Siswa Berprestasi dalam Kompetisi Debat Bahasa Arab',
                'category' => 'Prestasi',
                'author' => 'Guru Bahasa Arab',
                'published_at' => now()->subDays(3),
                'is_published' => true,
            ],
            [
                'title' => 'Rapat Koordinasi Orang Tua-Wali Murid Kelas XII',
                'category' => 'Informasi',
                'author' => 'Wali Kelas XII',
                'published_at' => null,
                'is_published' => false,
            ],
            [
                'title' => 'Donor Darah Sukarela dalam Rangka HUT RI ke-81',
                'category' => 'Acara',
                'author' => 'PMR MAN 1',
                'published_at' => now()->subDays(4),
                'is_published' => true,
            ],
            [
                'title' => 'Persiapan UN dan Ujian Madrasah 2026',
                'category' => 'Akademik',
                'author' => 'Tim Kurikulum',
                'published_at' => now()->subDays(1),
                'is_published' => true,
            ],
            [
                'title' => 'Kunjungan Industri ke Startup Teknologi Bandung',
                'category' => 'Informasi',
                'author' => 'Guru TIK',
                'published_at' => null,
                'is_published' => false,
            ],
        ];

        foreach ($items as $item) {
            News::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                array_merge($item, [
                    'content' => $this->generateLoremIpsum(rand(3, 6)),
                    'image' => 'news/default-' . rand(1, 5) . '.jpg',
                ])
            );
        }

        $this->command->info('Seeded ' . count($items) . ' dummy news.');
    }

    private function seedExtracurriculars(): void
    {
        $items = [
            [
                'name' => 'Pramuka',
                'category' => 'Keorganisasian',
                'coach' => 'Kak Andika Wijaya, S.Pd.',
                'schedule' => 'Sabtu, 07.30 - 10.00 WIB',
                'image' => 'extracurriculars/pramuka.jpg',
            ],
            [
                'name' => 'OSIS',
                'category' => 'Keorganisasian',
                'coach' => 'Ust. Budi Santoso, M.Pd.I.',
                'schedule' => 'Rabu, 15.30 - 17.00 WIB',
                'image' => 'extracurriculars/osis.jpg',
            ],
            [
                'name' => 'Rohis (Rohani Islam)',
                'category' => 'Keagamaan',
                'coach' => 'Ust. Dedi Irawan, Lc.',
                'schedule' => 'Jumat, 15.30 - 17.00 WIB',
                'image' => 'extracurriculars/rohis.jpg',
            ],
            [
                'name' => 'Paskibra',
                'category' => 'Keorganisasian',
                'coach' => 'Andi Firmansyah, S.Pd.',
                'schedule' => 'Senin & Kamis, 15.30 - 17.30 WIB',
                'image' => 'extracurriculars/paskibra.jpg',
            ],
            [
                'name' => 'Futsal',
                'category' => 'Olahraga',
                'coach' => 'Ridwan Firmansyah, S.Or.',
                'schedule' => 'Selasa & Jumat, 15.30 - 17.30 WIB',
                'image' => 'extracurriculars/futsal.jpg',
            ],
            [
                'name' => 'Basket',
                'category' => 'Olahraga',
                'coach' => 'Yusuf Maulana, S.Or.',
                'schedule' => 'Senin & Kamis, 15.30 - 17.30 WIB',
                'image' => 'extracurriculars/basket.jpg',
            ],
            [
                'name' => 'Paduan Suara',
                'category' => 'Seni',
                'coach' => 'Dewi Kusuma, S.Pd.',
                'schedule' => 'Selasa & Kamis, 15.30 - 17.30 WIB',
                'image' => 'extracurriculars/padus.jpg',
            ],
            [
                'name' => 'Teater',
                'category' => 'Seni',
                'coach' => 'Ahmad Rizki, S.Sn.',
                'schedule' => 'Rabu & Sabtu, 15.30 - 17.30 WIB',
                'image' => 'extracurriculars/teater.jpg',
            ],
        ];

        foreach ($items as $item) {
            Extracurricular::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                array_merge($item, [
                    'description' => $this->generateLoremIpsum(rand(2, 4)),
                ])
            );
        }

        $this->command->info('Seeded ' . count($items) . ' dummy extracurriculars.');
    }

    private function seedAchievements(): void
    {
        $items = [
            [
                'title' => 'Juara 1 Olimpiade Matematika SMA/MA Se-Jawa Barat',
                'student_name' => 'Muhammad Rizky Aditya',
                'category' => 'Akademik',
                'level' => 'Provinsi',
                'achievement_date' => now()->subMonths(2),
                'image' => 'achievements/math-olympiad.jpg',
            ],
            [
                'title' => 'Medali Emas Festival Bahasa Arab Nasional',
                'student_name' => 'Aisyah Putri Ramadhani',
                'category' => 'Bahasa',
                'level' => 'Nasional',
                'achievement_date' => now()->subMonths(3),
                'image' => 'achievements/arabic-festival.jpg',
            ],
            [
                'title' => 'Juara 2 Lomba Debat Bahasa Inggris Tingkat Kota Bandung',
                'student_name' => 'Fathan Abdul Aziz',
                'category' => 'Bahasa',
                'level' => 'Kota',
                'achievement_date' => now()->subMonths(1),
                'image' => 'achievements/debate-english.jpg',
            ],
            [
                'title' => 'Juara 1 Turnamen Futsal Antar Madrasah Se-Kota Bandung',
                'student_name' => 'Tim Futsal MAN 1 Bandung',
                'category' => 'Olahraga',
                'level' => 'Kota',
                'achievement_date' => now()->subMonths(4),
                'image' => 'achievements/futsal-champion.jpg',
            ],
            [
                'title' => 'Medali Perak Olimpiade Fisika Tingkat Nasional',
                'student_name' => 'Nadia Salsabila',
                'category' => 'Akademik',
                'level' => 'Nasional',
                'achievement_date' => now()->subMonths(5),
                'image' => 'achievements/physics-silver.jpg',
            ],
            [
                'title' => 'Juara 1 Kompetisi Pidato Bahasa Indonesia Se-Jawa Barat',
                'student_name' => 'Reza Pratama',
                'category' => 'Bahasa',
                'level' => 'Provinsi',
                'achievement_date' => now()->subMonths(1),
                'image' => 'achievements/speech-champion.jpg',
            ],
            [
                'title' => 'Best Performance Festival Band Antar SMA Se-Bandung Raya',
                'student_name' => 'Tim Marching Band MAN 1',
                'category' => 'Seni',
                'level' => 'Regional',
                'achievement_date' => now()->subMonths(6),
                'image' => 'achievements/marching-band.jpg',
            ],
            [
                'title' => 'Juara 3 Lomba Cerdas Cermat Keagamaan Tingkat Provinsi',
                'student_name' => 'Tim Cerdas Cermat MAN 1',
                'category' => 'Keagamaan',
                'level' => 'Provinsi',
                'achievement_date' => now()->subMonths(2),
                'image' => 'achievements/cc-keagamaan.jpg',
            ],
            [
                'title' => 'Medali Emas Kejuaraan Taekwondo Piala Gubernur',
                'student_name' => 'Kevin Alfarizi',
                'category' => 'Olahraga',
                'level' => 'Provinsi',
                'achievement_date' => now()->subMonths(3),
                'image' => 'achievements/taekwondo-gold.jpg',
            ],
            [
                'title' => 'Juara Harapan 1 Lomba Karya Tulis Ilmiah Remaja',
                'student_name' => 'Sarah Amelia Putri',
                'category' => 'Riset',
                'level' => 'Nasional',
                'achievement_date' => now()->subMonths(1),
                'image' => 'achievements/lkti-remaja.jpg',
            ],
        ];

        foreach ($items as $item) {
            Achievement::updateOrCreate(
                [
                    'title' => $item['title'],
                    'student_name' => $item['student_name'],
                ],
                array_merge($item, [
                    'description' => $this->generateLoremIpsum(rand(2, 3)),
                ])
            );
        }

        $this->command->info('Seeded ' . count($items) . ' dummy achievements.');
    }

    private function seedGalleries(): void
    {
        $items = [
            [
                'title' => 'Upacara Bendera Hari Senin',
                'category' => 'Kegiatan Rutin',
                'image' => 'galleries/upacara-senin.jpg',
            ],
            [
                'title' => 'Kegiatan MPLS 2025 Hari Pertama',
                'category' => 'Kegiatan Sekolah',
                'image' => 'galleries/mpls-2025-1.jpg',
            ],
            [
                'title' => 'Workshop Literasi Digital',
                'category' => 'Workshop',
                'image' => 'galleries/literasi-digital.jpg',
            ],
            [
                'title' => 'Peringatan Maulid Nabi 1446 H',
                'category' => 'Keagamaan',
                'image' => 'galleries/maulid-1446.jpg',
            ],
            [
                'title' => 'Kegiatan Pramuka Penggalang',
                'category' => 'Pramuka',
                'image' => 'galleries/pramuka-cibodas.jpg',
            ],
            [
                'title' => 'Donor Darah Sukarela 2025',
                'category' => 'Sosial',
                'image' => 'galleries/donor-darah.jpg',
            ],
            [
                'title' => 'Turnamen Futsal Antar Kelas',
                'category' => 'Olahraga',
                'image' => 'galleries/futsal-antarkelas.jpg',
            ],
            [
                'title' => 'Kunjungan Museum Geologi Bandung',
                'category' => 'Study Tour',
                'image' => 'galleries/museum-geologi.jpg',
            ],
            [
                'title' => 'Pelepasan Kelas XII Tahun 2025',
                'category' => 'Kegiatan Sekolah',
                'image' => 'galleries/pelepasan-xii.jpg',
            ],
            [
                'title' => 'Latihan Paskibra Persiapan 17 Agustus',
                'category' => 'Paskibra',
                'image' => 'galleries/latihan-paskibra.jpg',
            ],
        ];

        foreach ($items as $item) {
            Gallery::updateOrCreate(
                ['title' => $item['title']],
                array_merge($item, [
                    'description' => $this->generateLoremIpsum(rand(1, 2)),
                ])
            );
        }

        $this->command->info('Seeded ' . count($items) . ' dummy galleries.');
    }

    private function generateLoremIpsum(int $paragraphs = 3): string
    {
        $paragraphsList = [
            'MAN 1 Kota Bandung terus berkomitmen untuk memberikan pendidikan berkualitas dengan integrasi nilai-nilai keislaman dan kemoderenan. Kegiatan ini merupakan bagian dari upaya meningkatkan kualitas sumber daya manusia yang unggul dan berakhlak mulia.',
            'Dalam pelaksanaan kegiatan ini, pihak sekolah bekerja sama dengan berbagai stakeholder termasuk orang tua, alumni, dan mitra industri untuk menciptakan lingkungan pembelajaran yang kondusif dan inspiratif bagi seluruh peserta didik.',
            'Kegiatan ini diikuti oleh seluruh siswa dan guru dengan antusias yang tinggi. Harapannya, melalui kegiatan ini dapat menumbuhkan semangat kebersamaan, kedisiplinan, dan rasa tanggung jawab sebagai generasi penerus bangsa.',
            'Prestasi yang diraih ini tidak lepas dari dukungan penuh dari pihak sekolah, orang tua, dan tentunya kerja keras dari siswa bersangkutan. Semoga prestasi ini dapat menjadi motivasi bagi siswa lainnya untuk terus berprestasi.',
            'Dengan adanya kegiatan ini, diharapkan siswa dapat mengembangkan potensi diri masing-masing baik dalam bidang akademik maupun non-akademik sesuai dengan minat dan bakat yang dimiliki.',
            'Sekolah berharap agar kegiatan serupa dapat terus dilaksanakan secara rutin guna mendukung tercapainya visi dan misi MAN 1 Kota Bandung dalam mencetak generasi Qurani yang berprestasi.',
            'Jumlah peserta yang mengikuti kegiatan ini mencapai ratusan siswa dari berbagai kelas. Suasana kegiatan berlangsung dengan sangat meriah dan penuh semangat kebersamaan.',
            'Para peserta menunjukkan antusiasme yang luar biasa dalam mengikuti setiap rangkaian kegiatan. Hal ini menunjukkan bahwa siswa MAN 1 Kota Bandung memiliki semangat yang tinggi dalam mengembangkan diri.',
        ];

        $result = [];
        for ($i = 0; $i < $paragraphs; $i++) {
            $result[] = $paragraphsList[array_rand($paragraphsList)];
        }

        return '<p>' . implode('</p><p>', $result) . '</p>';
    }
}
