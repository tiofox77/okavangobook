<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(
        string $key, 
        mixed $value, 
        string $group = 'general', 
        string $type = 'string', 
        string $description = '', 
        bool $isPublic = true
    ): void {
        try {
            $setting = static::updateOrCreate(
                ['key' => $key],
                [
                    'value' => static::prepareValue($value, $type),
                    'group' => $group,
                    'type' => $type,
                    'description' => $description,
                    'is_public' => $isPublic,
                ]
            );

            // Clear cache for this setting
            Cache::forget("setting_{$key}");
            
            Log::info("Setting updated: {$key}", [
                'old_value' => $setting->getOriginal('value'),
                'new_value' => $setting->value,
                'group' => $group,
                'type' => $type
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to set setting: {$key}", [
                'error' => $e->getMessage(),
                'value' => $value,
                'group' => $group,
                'type' => $type
            ]);
            throw $e;
        }
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = "settings_group_{$group}";
        
        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return static::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => static::castValue($setting->value, $setting->type)];
                })
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
        Log::info('Settings cache cleared');
    }

    /**
     * Cast value to proper type
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'array' => is_string($value) ? json_decode($value, true) : $value,
            'json' => is_string($value) ? json_decode($value, true) : $value,
            default => $value,
        };
    }

    /**
     * Prepare value for storage
     */
    protected static function prepareValue(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'array', 'json' => is_array($value) ? json_encode($value) : $value,
            default => (string) $value,
        };
    }

    /**
     * Get public settings for frontend
     */
    public static function getPublicSettings(): array
    {
        $cacheKey = 'public_settings';
        
        return Cache::remember($cacheKey, 3600, function () {
            return static::where('is_public', true)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => static::castValue($setting->value, $setting->type)];
                })
                ->toArray();
        });
    }
}
