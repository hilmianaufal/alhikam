<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppSetting
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            return Setting::getValue($key, $default);
        });
    }

    public static function appName(): string
    {
        return (string) self::get('app_name', 'Al Ishlah Pay');
    }

    public static function pondokName(): string
    {
        return (string) self::get(
            'pondok_name',
            'Ponpes Al Ishlah Jatireja - Subang'
        );
    }

    public static function address(): ?string
    {
        return self::get('address');
    }

    public static function phone(): ?string
    {
        return self::get('phone');
    }

    public static function email(): ?string
    {
        return self::get('email');
    }

    public static function footerText(): string
    {
        return (string) self::get('footer_text', '© Al Ishlah Pay');
    }

    public static function logo(): ?string
    {
        return self::storageUrl(self::get('logo'));
    }

    public static function favicon(): ?string
    {
        return self::storageUrl(self::get('favicon'));
    }

    public static function storageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = self::normalizeStoragePath($path);

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        $encodedPath = implode(
            '/',
            array_map('rawurlencode', explode('/', $path))
        );

        try {
            $version = Storage::disk('public')->lastModified($path);

            return '/storage/' . $encodedPath . '?v=' . $version;
        } catch (\Throwable) {
            return '/storage/' . $encodedPath;
        }
    }

    public static function normalizeStoragePath(string $path): string
    {
        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');

        while (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path;
    }

    public static function clearCache(): void
    {
        foreach ([
            'app_name',
            'pondok_name',
            'address',
            'phone',
            'email',
            'footer_text',
            'logo',
            'favicon',
        ] as $key) {
            Cache::forget("setting_{$key}");
        }
    }
}
