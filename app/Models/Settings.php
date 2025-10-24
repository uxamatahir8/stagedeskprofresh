<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    //
    protected $fillable = [
        'key',
        'value',
    ];

    public static function loadAll()
    {
        return Cache::rememberForever(
            'settings',
            fn() =>
            self::query()->pluck('value', 'key')->toArray()
        );
    }

    public static function get($key, $default = null)
    {
        $settings = Cache::get('settings');

        return $settings[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $settings = self::updateOrCreate(['key' => $key], ['value' => $value]);
        // refresh cache
        Cache::forget('settings');
        self::loadAll();

        return $settings;
    }
}
