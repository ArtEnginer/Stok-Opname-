<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettingModel extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'setting_group',
        'description',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get setting by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->setting_value, $setting->setting_type);
    }

    /**
     * Set setting value
     */
    public static function set($key, $value)
    {
        $setting = self::where('setting_key', $key)->first();

        if ($setting) {
            $setting->setting_value = $value;
            $setting->save();
            return $setting;
        }

        return self::create([
            'setting_key' => $key,
            'setting_value' => $value,
            'setting_type' => self::detectType($value),
            'setting_group' => 'custom',
        ]);
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        $settings = self::where('setting_group', $group)->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->setting_key] = self::castValue(
                $setting->setting_value,
                $setting->setting_type
            );
        }

        return $result;
    }

    /**
     * Get all settings as associative array
     */
    public static function getAll()
    {
        $settings = self::all();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->setting_key] = self::castValue(
                $setting->setting_value,
                $setting->setting_type
            );
        }

        return $result;
    }

    /**
     * Get all settings grouped by group
     */
    public static function getAllGrouped()
    {
        $settings = self::all();
        $result = [];

        foreach ($settings as $setting) {
            if (!isset($result[$setting->setting_group])) {
                $result[$setting->setting_group] = [];
            }

            $result[$setting->setting_group][$setting->setting_key] = [
                'value' => self::castValue($setting->setting_value, $setting->setting_type),
                'type' => $setting->setting_type,
                'description' => $setting->description,
            ];
        }

        return $result;
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, $type)
    {
        if ($value === null) {
            return null;
        }

        switch ($type) {
            case 'boolean':
                return (bool) $value || $value === '1' || $value === 'true';
            case 'number':
                return is_numeric($value) ? (strpos($value, '.') !== false ? (float) $value : (int) $value) : $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Detect type of value
     */
    protected static function detectType($value)
    {
        if (is_bool($value)) {
            return 'boolean';
        } elseif (is_numeric($value)) {
            return 'number';
        } elseif (is_array($value)) {
            return 'json';
        } else {
            return strlen($value) > 255 ? 'text' : 'string';
        }
    }

    /**
     * Update multiple settings at once
     */
    public static function updateMultiple(array $settings)
    {
        $updated = [];

        foreach ($settings as $key => $value) {
            $setting = self::set($key, $value);
            $updated[$key] = $setting;
        }

        return $updated;
    }

    /**
     * Delete setting by key
     */
    public static function remove($key)
    {
        return self::where('setting_key', $key)->delete();
    }

    /**
     * Check if setting exists
     */
    public static function has($key)
    {
        return self::where('setting_key', $key)->exists();
    }
}
