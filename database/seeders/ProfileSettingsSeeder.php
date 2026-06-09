<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ProfileSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Data Siswa
            'student_male'       => '1.215',
            'student_female'     => '1.730',
            'total_rombel'       => '36',
            'majors'             => 'IPA, IPS, Bahasa, Keagamaan',

            // Data Guru & Tendik
            'teacher_male'       => '47',
            'teacher_female'     => '48',
            'staff_count'        => '19',
            'total_personnel'    => '114',
            'teacher_pns'        => '65',
            'teacher_non_pns'    => '30',

            // Data Pokok Sekolah
            'nsm'                => '131132730001',
            'npsn'               => '20277069',
            'school_status'      => 'Negeri',
            'accreditation'      => 'A (Unggul)',
            'accreditation_sk'   => '458/BAN-SM/SK/2020 (22 Juni 2020)',
            'land_area'          => '26.070 m²',

            // Sejarah
            'sejarah'            => 'MAN 1 Kota Bandung didirikan pada tanggal 27 Januari 1992 berdasarkan Surat Keputusan Menteri Agama Nomor 42 tahun 1992. Berstatus sebagai Madrasah Aliyah Negeri, madrasah ini hadir di bawah naungan Kementerian Agama dengan komitmen mencetak generasi muda yang beriman, berilmu, dan berakhlak mulia.
Terletak di Jalan Haji Alpi Cijeerah, Kelurahan Cibuntu, Kecamatan Bandung Kulon, Kota Bandung, madrasah ini berdiri di atas lahan seluas 26.070 meter persegi — menjadikannya salah satu madrasah dengan lingkungan terluas dan paling representatif di Kota Bandung. Dengan akreditasi A yang diraih pada 22 Juni 2020 (SK BAN-SM Nomor 458/BAN-SM/SK/2020), MAN 1 Kota Bandung terus berbenah menjadi lembaga pendidikan Islam yang kompetitif dan berkualitas.
Hingga saat ini, MAN 1 Kota Bandung terus berkembang menjadi madrasah unggulan dengan lebih dari 1.200 siswa aktif dan 84 tenaga pendidik profesional. Madrasah ini tidak hanya fokus pada prestasi akademik, tetapi juga pengembangan karakter dan keterampilan siswa melalui berbagai kegiatan ekstrakurikuler dan pembiasaan positif. Dengan tiga jurusan unggulan — IPA, IPS, dan Agama — MAN 1 Kota Bandung siap melahirkan lulusan yang berdaya saing global.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->command->info('Data default profil sekolah berhasil di-seed!');
    }
}
