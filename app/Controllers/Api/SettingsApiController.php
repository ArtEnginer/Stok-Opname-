<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AppSettingModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class SettingsApiController extends BaseController
{
    use ResponseTrait;

    /**
     * Get public settings (for frontend)
     * GET /api/settings/public
     */
    public function getPublicSettings()
    {
        try {
            $publicSettings = [
                'app_name' => AppSettingModel::get('app_name'),
                'app_description' => AppSettingModel::get('app_description'),
                'app_logo' => $this->getFileUrl(AppSettingModel::get('app_logo')),
                'app_favicon' => $this->getFileUrl(AppSettingModel::get('app_favicon')),
                'app_email' => AppSettingModel::get('app_email'),
                'app_phone' => AppSettingModel::get('app_phone'),
                'app_address' => AppSettingModel::get('app_address'),
                'social_facebook' => AppSettingModel::get('social_facebook'),
                'social_twitter' => AppSettingModel::get('social_twitter'),
                'social_instagram' => AppSettingModel::get('social_instagram'),
                'social_linkedin' => AppSettingModel::get('social_linkedin'),
                'social_youtube' => AppSettingModel::get('social_youtube'),
                'seo_meta_title' => AppSettingModel::get('seo_meta_title'),
                'seo_meta_description' => AppSettingModel::get('seo_meta_description'),
                'seo_meta_keywords' => AppSettingModel::get('seo_meta_keywords'),
                'midtrans_client_key' => AppSettingModel::get('midtrans_client_key'),
                'midtrans_is_production' => (bool) AppSettingModel::get('midtrans_is_production'),
                'maintenance_mode' => (bool) AppSettingModel::get('maintenance_mode'),
                'enable_registration' => (bool) AppSettingModel::get('enable_registration'),
            ];

            return $this->respond([
                'status' => 'success',
                'data' => $publicSettings
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve public settings',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get application info
     * GET /api/settings/app-info
     */
    public function getAppInfo()
    {
        try {
            $appInfo = [
                'name' => AppSettingModel::get('app_name', 'Platform Donasi'),
                'description' => AppSettingModel::get('app_description'),
                'logo' => $this->getFileUrl(AppSettingModel::get('app_logo')),
                'favicon' => $this->getFileUrl(AppSettingModel::get('app_favicon')),
                'email' => AppSettingModel::get('app_email'),
                'phone' => AppSettingModel::get('app_phone'),
                'address' => AppSettingModel::get('app_address'),
            ];

            return $this->respond([
                'status' => 'success',
                'data' => $appInfo
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve app info',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Midtrans public configuration
     * GET /api/settings/midtrans-config
     */
    public function getMidtransConfig()
    {
        try {
            $config = [
                'client_key' => AppSettingModel::get('midtrans_client_key', ''),
                'is_production' => (bool) AppSettingModel::get('midtrans_is_production', false),
                'snap_url' => (bool) AppSettingModel::get('midtrans_is_production', false)
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js',
            ];

            return $this->respond([
                'status' => 'success',
                'data' => $config
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve Midtrans config',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Helper to get file URL
     */
    protected function getFileUrl($filename)
    {
        if ($filename) {
            return base_url('uploads/settings/' . $filename);
        }
        return null;
    }
}
