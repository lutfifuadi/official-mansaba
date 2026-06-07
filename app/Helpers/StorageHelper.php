<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function disk(): string
    {
        if (config('filesystems.disks.s3.key') && config('filesystems.disks.s3.secret')) {
            return 's3';
        }
        return 'public';
    }

    public static function putFile(string $path, $file): string
    {
        $disk = self::disk();
        try {
            return $file->store($path, $disk);
        } catch (\Exception $e) {
            if ($disk === 's3') {
                return $file->store($path, 'public');
            }
            throw $e;
        }
    }

    public static function deleteFile(?string $path): void
    {
        if (!$path) return;
        foreach (['s3', 'public'] as $disk) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function url(?string $path): string
    {
        if (!$path) return '';
        $disk = self::disk();
        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->url($path);
            }
        } catch (\Exception $e) {
        }
        try {
            $fallback = $disk === 's3' ? 'public' : 's3';
            if (Storage::disk($fallback)->exists($path)) {
                return Storage::disk($fallback)->url($path);
            }
        } catch (\Exception $e) {
        }
        return '';
    }
}
