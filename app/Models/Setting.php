<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Helper to get value by key
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper to set value
    public static function setValue(string $key, $value, string $group = 'app')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    // Get all settings as key-value array
    public static function getAll($group = null)
    {
        $query = self::query();
        if ($group) {
            $query->where('group', $group);
        }
        return $query->pluck('value', 'key')->toArray();
    }
}