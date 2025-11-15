<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\AppSettingModel;

class CheckMidtrans extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'midtrans:check';
    protected $description = 'Check Midtrans configuration settings';

    public function run(array $params)
    {
        CLI::write('=== Midtrans Configuration Check ===', 'green');
        CLI::newLine();

        $db = \Config\Database::connect();
        $builder = $db->table('app_settings');
        $settings = $builder->whereIn('setting_key', [
            'midtrans_server_key',
            'midtrans_client_key',
            'midtrans_merchant_id',
            'midtrans_is_production',
            'midtrans_is_sanitized',
            'midtrans_is_3ds'
        ])->get()->getResultArray();

        if (empty($settings)) {
            CLI::error('No Midtrans settings found in database!');
            CLI::write('Run: php spark db:seed AppSettingsSeeder', 'yellow');
            return;
        }

        $hasError = false;

        foreach ($settings as $setting) {
            $key = $setting['setting_key'];
            $value = $setting['setting_value'];

            // Check if empty
            if (empty($value) && !in_array($key, ['midtrans_merchant_id'])) {
                CLI::write(str_pad($key, 30) . ': EMPTY', 'red');
                $hasError = true;
                continue;
            }

            // Mask sensitive keys
            if (strpos($key, 'key') !== false && !empty($value)) {
                $displayValue = substr($value, 0, 15) . '...' . substr($value, -5);

                // Validate format
                if ($key === 'midtrans_server_key') {
                    $isProduction = ($setting['setting_value'] ?? '0') === '1';
                    $expectedPrefix = $isProduction ? 'Mid-server-' : 'SB-Mid-server-';

                    if (!str_starts_with($value, $expectedPrefix) && !str_starts_with($value, 'Mid-server-')) {
                        CLI::write(str_pad($key, 30) . ': ' . $displayValue . ' (INVALID FORMAT)', 'red');
                        $hasError = true;
                        continue;
                    }
                }

                if ($key === 'midtrans_client_key') {
                    if (!str_starts_with($value, 'SB-Mid-client-') && !str_starts_with($value, 'Mid-client-')) {
                        CLI::write(str_pad($key, 30) . ': ' . $displayValue . ' (INVALID FORMAT)', 'red');
                        $hasError = true;
                        continue;
                    }
                }
            } else {
                $displayValue = $value ?: '(empty)';
            }

            CLI::write(str_pad($key, 30) . ': ' . $displayValue, 'green');
        }

        CLI::newLine();

        if ($hasError) {
            CLI::error('⚠ Some settings are missing or invalid!');
            CLI::write('Please configure Midtrans settings at: http://localhost:8080/admin/settings-page', 'yellow');
            CLI::newLine();
            CLI::write('Get your credentials from:', 'white');
            CLI::write('  Sandbox: https://dashboard.sandbox.midtrans.com/', 'cyan');
            CLI::write('  Production: https://dashboard.midtrans.com/', 'cyan');
        } else {
            CLI::write('✓ All settings configured correctly!', 'green');
        }

        CLI::newLine();
    }
}
