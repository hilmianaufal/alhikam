<?php

namespace App\Helpers;

class Menu
{
    public static function active(string|array $routes, string $activeClass = 'bg-emerald-700 text-white shadow-sm', string $inactiveClass = 'text-emerald-50 hover:bg-emerald-700/70'): string
    {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) {
                return $activeClass;
            }
        }

        return $inactiveClass;
    }
}
