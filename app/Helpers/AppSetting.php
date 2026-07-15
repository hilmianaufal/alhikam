<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

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
        return self::get('app_name', 'Al Ishlah Pay');
    }

    public static function pondokName(): string
    {
        return self::get('pondok_name', 'Ponpes Al Ishlah Jatireja - Subang');
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
        return self::get('footer_text', '© Al Ishlah Pay');
    }

    public static function logo(): ?string
    {
        $logo = self::get('logo');

        return $logo ? asset('storage/' . $logo) : null;
    }

    public static function favicon(): ?string
    {
        $favicon = self::get('favicon');

        return $favicon ? asset('storage/' . $favicon) : null;
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
