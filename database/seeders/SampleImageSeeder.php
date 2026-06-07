<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SampleImageSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Generating sample images...');

        $newsImages = $this->generateBatch('news', 5, ['#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed']);
        $galleryImages = $this->generateBatch('galleries', 10, ['#0891b2', '#4f46e5', '#16a34a', '#ca8a04', '#db2777', '#65a30d', '#0d9488', '#9333ea', '#ea580c', '#1d4ed8']);
        $achievementImages = $this->generateBatch('achievements', 10, ['#b45309', '#0369a1', '#854d0e', '#15803d', '#6d28d9', '#c2410c', '#0e7490', '#4d7c0f', '#be185d', '#1e40af']);
        $extracurricularImages = $this->generateBatch('extracurriculars', 8, ['#0f766e', '#4338ca', '#b91c1c', '#a16207', '#2563eb', '#059669', '#d97706', '#7c3aed']);

        $this->updateNews($newsImages);
        $this->updateGalleries($galleryImages);
        $this->updateAchievements($achievementImages);
        $this->updateExtracurriculars($extracurricularImages);

        $this->command->info('Sample images seeded successfully.');
    }

    private function generateBatch(string $dir, int $count, array $colors): array
    {
        $paths = [];
        for ($i = 1; $i <= $count; $i++) {
            $color = $colors[($i - 1) % count($colors)];
            $path = "{$dir}/sample-{$i}.jpg";
            $this->createPlaceholderImage($color, $path);
            $paths[] = $path;
        }
        return $paths;
    }

    private function createPlaceholderImage(string $color, string $path): void
    {
        $img = imagecreatetruecolor(800, 500);
        $rgb = sscanf($color, '#%02x%02x%02x');
        $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagefill($img, 0, 0, $bg);

        $white = imagecolorallocate($img, 255, 255, 255);
        $label = pathinfo($path, PATHINFO_FILENAME);
        $text = str_replace(['sample-', '-'], ['', ' '], $label);

        $fontSize = 30;
        $x = 400 - (imagefontwidth($fontSize) * strlen($text) * 0.55);
        $y = 250;
        imagestring($img, $fontSize, (int)$x, (int)$y, $text, $white);

        ob_start();
        imagejpeg($img, null, 80);
        $contents = ob_get_clean();
        imagedestroy($img);

        Storage::disk('s3')->put($path, $contents, 'public');
    }

    private function updateNews(array $images): void
    {
        $news = News::all();
        foreach ($news as $i => $item) {
            $item->update(['image' => $images[$i % count($images)]]);
        }
    }

    private function updateGalleries(array $images): void
    {
        $galleries = Gallery::all();
        foreach ($galleries as $i => $item) {
            $item->update(['image' => $images[$i % count($images)]]);
        }
    }

    private function updateAchievements(array $images): void
    {
        $achievements = Achievement::all();
        foreach ($achievements as $i => $item) {
            $item->update(['image' => $images[$i % count($images)]]);
        }
    }

    private function updateExtracurriculars(array $images): void
    {
        $extracurriculars = Extracurricular::all();
        foreach ($extracurriculars as $i => $item) {
            $item->update(['image' => $images[$i % count($images)]]);
        }
    }
}
