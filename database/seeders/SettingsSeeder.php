<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'visi'               => 'Terwujudnya peserta didik yang beriman, bertakwa, berilmu, berkarakter, berbudaya, dan berdaya saing global.',
            'misi'               => 'Menyelenggarakan pendidikan yang berbasis iman dan takwa.|Mengembangkan potensi akademik dan non-akademik peserta didik secara optimal.|Membentuk karakter peserta didik yang Islami, disiplin, dan bertanggung jawab.|Menciptakan lingkungan madrasah yang bersih, nyaman, dan kondusif.|Membangun kerjasama dalam rangka peningkatan mutu pendidikan.',
            'motto'              => 'Taqwa, Cerdas, Mandiri',
            'site_name'          => 'MAN 1 Kota Bandung',
            'site_description'   => 'Madrasah Aliyah Negeri 1 Kota Bandung — mewujudkan generasi yang beriman, berilmu, berkarakter, dan berdaya saing global.',
            'address'            => 'JL. HAJI ALPI CIJERAH, Kelurahan Cibuntu, Kec. Bandung Kulon, Kota Bandung, Jawa Barat',
            'phone'              => '(022) 12345678',
            'email'              => 'info@man1bandung.sch.id',
            'school_logo'        => '',
            'student_count'      => '1.248',
            'teacher_count'      => '84',
            'founded_year'       => '1990',
            'operational_hours'  => 'Senin – Jumat: 07.00 – 15.30 WIB',
            'facebook'           => '#',
            'instagram'          => '#',
            'youtube'            => '#',
            'twitter'            => '#',
            'tiktok'              => '#',
            'headmaster_name'    => 'Yayan Ristaman Jaya, S.Pd, S.E, M.M.',
            'headmaster_photo'   => '',
            'headmaster_message' => 'Assalamu\'alaikum warahmatullahi wabarakatuh. Puji syukur kehadirat Allah SWT atas segala limpahan rahmat dan karunia-Nya. MAN 1 Kota Bandung senantiasa berkomitmen mencetak generasi yang unggul dalam prestasi, berkarakter islami, dan berakhlak mulia. Kami percaya setiap siswa memiliki potensi luar biasa yang perlu dikembangkan melalui pendidikan holistik berbasis iman dan taqwa.',
            'announcement'         => '',
            'announcement_active'  => '0',
            'hero_title'           => 'Berprestasi, Berkarakter',
            'hero_highlight'       => 'Berakhlak Mulia',
            'hero_subtitle'        => 'Mewujudkan generasi Islami yang unggul dalam ilmu, iman, dan amal untuk kejayaan umat dan bangsa.',
            'stats_label_1'        => 'Siswa Aktif',
            'stats_label_2'        => 'Tenaga Pendidik',
            'stats_label_3'        => 'Tahun Berdiri',
            'stats_label_4'        => 'Prestasi Diraih',
            'section_news_label'   => 'Informasi & Kegiatan',
            'section_news_title'   => 'Berita Terbaru',
            'section_news_desc'    => 'Ikuti perkembangan terkini dari lingkungan MAN 1 Kota Bandung',
            'section_achievement_label' => 'Keunggulan & Pencapaian',
            'section_achievement_title' => 'Prestasi Membanggakan',
            'section_achievement_desc'  => 'Prestasi yang telah diraih siswa-siswi MAN 1 Kota Bandung',
            'section_gallery_label'     => 'Dokumentasi',
            'section_gallery_title'     => 'Galeri Kegiatan',
            'section_gallery_desc'      => 'Momen kegiatan dan keseharian di MAN 1 Kota Bandung',
            'section_extracurricular_label' => 'Pengembangan Diri',
            'section_extracurricular_title' => 'Kegiatan Ekstrakurikuler',
            'section_extracurricular_desc'  => 'Sarana pengembangan bakat dan minat siswa di luar akademik',
            'headmaster_label'      => 'Pimpinan',
            'headmaster_title'      => 'Sambutan Kepala Sekolah',
            'contact_label_address' => 'Alamat',
            'contact_label_phone'   => 'Telepon',
            'contact_label_email'   => 'Email',

            'registration_enabled'  => '1',
            'coming_soon_mode'     => '0',
            'coming_soon_date'     => '2026-08-17 08:00:00',
            'maintenance_mode'     => '0',
            'maintenance_est_time' => 'Beberapa jam ke depan',
            'meta_title_berita'    => '',
            'meta_title_galeri'    => '',
            'meta_title_prestasi'  => '',
            'meta_title_ekstrakurikuler' => '',
            'meta_title_profil'    => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
