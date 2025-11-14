<?php

use App\Models\AppSettingModel;

if (!function_exists('setting')) {
    /**
     * Get or set application setting
     *
     * @param string|null $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function setting(?string $key = null, $default = null)
    {
        if ($key === null) {
            return AppSettingModel::getAll();
        }

        return AppSettingModel::get($key, $default);
    }
}

if (!function_exists('settings_by_group')) {
    /**
     * Get settings by group
     *
     * @param string $group Setting group
     * @return array
     */
    function settings_by_group(string $group): array
    {
        return AppSettingModel::getByGroup($group);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set application setting
     *
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return mixed
     */
    function set_setting(string $key, $value)
    {
        return AppSettingModel::set($key, $value);
    }
}

if (!function_exists('app_name')) {
    /**
     * Get application name
     *
     * @return string
     */
    function app_name(): string
    {
        return setting('app_name', 'Platform Donasi');
    }
}

if (!function_exists('app_logo')) {
    /**
     * Get application logo URL
     *
     * @return string|null
     */
    function app_logo(): ?string
    {
        $logo = setting('app_logo');
        if ($logo) {
            return base_url('writable/uploads/settings/' . $logo);
        }
        return null;
    }
}

if (!function_exists('app_favicon')) {
    /**
     * Get application favicon URL
     *
     * @return string|null
     */
    function app_favicon(): ?string
    {
        $favicon = setting('app_favicon');
        if ($favicon) {
            return base_url('writable/uploads/settings/' . $favicon);
        }
        return null;
    }
}

if (!function_exists('midtrans_config')) {
    /**
     * Get Midtrans configuration
     *
     * @return array
     */
    function midtrans_config(): array
    {
        return [
            'server_key' => setting('midtrans_server_key', ''),
            'client_key' => setting('midtrans_client_key', ''),
            'merchant_id' => setting('midtrans_merchant_id', ''),
            'is_production' => (bool) setting('midtrans_is_production', false),
            'is_sanitized' => (bool) setting('midtrans_is_sanitized', true),
            'is_3ds' => (bool) setting('midtrans_is_3ds', true),
        ];
    }
}

if (!function_exists('is_maintenance_mode')) {
    /**
     * Check if maintenance mode is active
     *
     * @return bool
     */
    function is_maintenance_mode(): bool
    {
        return (bool) setting('maintenance_mode', false);
    }
}

if (!function_exists('is_registration_enabled')) {
    /**
     * Check if user registration is enabled
     *
     * @return bool
     */
    function is_registration_enabled(): bool
    {
        return (bool) setting('enable_registration', true);
    }
}
