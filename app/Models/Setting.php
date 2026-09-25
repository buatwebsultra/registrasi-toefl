<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @return static
     */
    public static function set(string $key, $value, string $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    /**
     * Check if a setting key exists and is not null.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return static::where('key', $key)->whereNotNull('value')->exists();
    }

    /**
     * Get the absolute server path for the active certificate signature image.
     * Checks custom uploaded signature in storage first, then falls back to public/signature.png.
     *
     * @return string|null
     */
    public static function getSignaturePath(): ?string
    {
        $customPath = static::get('certificate_signature_path');
        if ($customPath && Storage::disk('public')->exists($customPath)) {
            return storage_path('app/public/' . $customPath);
        }

        if (file_exists(public_path('signature.png'))) {
            return public_path('signature.png');
        }

        return null;
    }

    /**
     * Get the web URL for the active certificate signature image.
     *
     * @return string|null
     */
    public static function getSignatureUrl(): ?string
    {
        $customPath = static::get('certificate_signature_path');
        if ($customPath && Storage::disk('public')->exists($customPath)) {
            return asset('storage/' . $customPath);
        }

        if (file_exists(public_path('signature.png'))) {
            return asset('signature.png');
        }

        return null;
    }

    /**
     * Check whether a custom signature image has been uploaded.
     *
     * @return bool
     */
    public static function isCustomSignature(): bool
    {
        $customPath = static::get('certificate_signature_path');
        return $customPath && Storage::disk('public')->exists($customPath);
    }
}
