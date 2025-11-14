<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppSettingModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class SettingsController extends BaseController
{
    use ResponseTrait;

    protected $appSettingModel;

    public function __construct()
    {
        $this->appSettingModel = new AppSettingModel();
    }

    /**
     * Get all settings
     * GET /admin/settings
     */
    public function index()
    {
        try {
            $group = $this->request->getGet('group');

            if ($group) {
                $settings = $this->appSettingModel
                    ->where('setting_group', $group)
                    ->get();
            } else {
                $settings = $this->appSettingModel->all();
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Settings retrieved successfully',
                'data' => $settings
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve settings',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get settings grouped by group
     * GET /admin/settings/grouped
     */
    public function getGrouped()
    {
        try {
            $settings = AppSettingModel::getAllGrouped();

            return $this->respond([
                'status' => 'success',
                'message' => 'Grouped settings retrieved successfully',
                'data' => $settings
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve grouped settings',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get single setting by key
     * GET /admin/settings/{key}
     */
    public function show($key)
    {
        try {
            $setting = $this->appSettingModel
                ->where('setting_key', $key)
                ->first();

            if (!$setting) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Setting not found'
                ], ResponseInterface::HTTP_NOT_FOUND);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Setting retrieved successfully',
                'data' => $setting
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve setting',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update single setting
     * PUT /admin/settings/{key}
     */
    public function update($key = null)
    {
        try {
            $data = $this->request->getJSON(true);

            if (!$key && !isset($data['setting_key'])) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Setting key is required'
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            $settingKey = $key ?? $data['setting_key'];

            $setting = $this->appSettingModel
                ->where('setting_key', $settingKey)
                ->first();

            if (!$setting) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Setting not found'
                ], ResponseInterface::HTTP_NOT_FOUND);
            }

            // Handle file upload for file type settings
            if ($setting->setting_type === 'file' && $this->request->getFile('file')) {
                $file = $this->request->getFile('file');

                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/settings', $newName);

                    // Delete old file if exists
                    if ($setting->setting_value && file_exists(WRITEPATH . 'uploads/settings/' . $setting->setting_value)) {
                        unlink(WRITEPATH . 'uploads/settings/' . $setting->setting_value);
                    }

                    $setting->setting_value = $newName;
                }
            } elseif (isset($data['setting_value'])) {
                // For JSON type, encode array to JSON string
                if ($setting->setting_type === 'json' && is_array($data['setting_value'])) {
                    $setting->setting_value = json_encode($data['setting_value']);
                } else {
                    $setting->setting_value = $data['setting_value'];
                }
            }

            if (isset($data['description'])) {
                $setting->description = $data['description'];
            }

            $setting->save();

            return $this->respond([
                'status' => 'success',
                'message' => 'Setting updated successfully',
                'data' => $setting
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update setting',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update multiple settings at once
     * PUT /admin/settings/batch
     */
    public function updateBatch()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!isset($data['settings']) || !is_array($data['settings'])) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Settings array is required'
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            $updated = [];
            $errors = [];

            foreach ($data['settings'] as $item) {
                if (!isset($item['setting_key']) || !isset($item['setting_value'])) {
                    continue;
                }

                try {
                    $setting = $this->appSettingModel
                        ->where('setting_key', $item['setting_key'])
                        ->first();

                    if ($setting) {
                        // For JSON type, encode array to JSON string
                        if ($setting->setting_type === 'json' && is_array($item['setting_value'])) {
                            $setting->setting_value = json_encode($item['setting_value']);
                        } else {
                            $setting->setting_value = $item['setting_value'];
                        }

                        $setting->save();
                        $updated[] = $setting;
                    }
                } catch (\Exception $e) {
                    $errors[] = [
                        'key' => $item['setting_key'],
                        'error' => $e->getMessage()
                    ];
                }
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Settings updated successfully',
                'data' => [
                    'updated' => $updated,
                    'errors' => $errors
                ]
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update settings',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create new setting
     * POST /admin/settings
     */
    public function create()
    {
        try {
            $data = $this->request->getJSON(true);

            $rules = [
                'setting_key' => 'required|is_unique[app_settings.setting_key]',
                'setting_value' => 'permit_empty',
                'setting_type' => 'required|in_list[string,text,number,boolean,json,file]',
                'setting_group' => 'required',
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            $setting = new AppSettingModel();
            $setting->fill($data);
            $setting->save();

            return $this->respond([
                'status' => 'success',
                'message' => 'Setting created successfully',
                'data' => $setting
            ], ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to create setting',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete setting
     * DELETE /admin/settings/{key}
     */
    public function delete($key)
    {
        try {
            $setting = $this->appSettingModel
                ->where('setting_key', $key)
                ->first();

            if (!$setting) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Setting not found'
                ], ResponseInterface::HTTP_NOT_FOUND);
            }

            // Delete file if it's a file type setting
            if ($setting->setting_type === 'file' && $setting->setting_value) {
                $filePath = WRITEPATH . 'uploads/settings/' . $setting->setting_value;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $setting->delete();

            return $this->respond([
                'status' => 'success',
                'message' => 'Setting deleted successfully'
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to delete setting',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload file for setting (logo, favicon, etc)
     * POST /admin/settings/{key}/upload
     */
    public function uploadFile($key)
    {
        try {
            $setting = $this->appSettingModel
                ->where('setting_key', $key)
                ->first();

            if (!$setting) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Setting not found'
                ], ResponseInterface::HTTP_NOT_FOUND);
            }

            if ($setting->setting_type !== 'file') {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'This setting does not accept file uploads'
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            $file = $this->request->getFile('file');

            if (!$file || !$file->isValid()) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'No valid file uploaded'
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            // Validate file type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/x-icon'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only images are allowed.'
                ], ResponseInterface::HTTP_BAD_REQUEST);
            }

            // Create directory if not exists
            $uploadPath = WRITEPATH . 'uploads/settings';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Delete old file if exists
            if ($setting->setting_value) {
                $oldFile = $uploadPath . '/' . $setting->setting_value;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $setting->setting_value = $newName;
            $setting->save();

            return $this->respond([
                'status' => 'success',
                'message' => 'File uploaded successfully',
                'data' => [
                    'filename' => $newName,
                    'url' => base_url('writable/uploads/settings/' . $newName)
                ]
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to upload file',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Midtrans settings
     * GET /admin/settings/payment/midtrans
     */
    public function getMidtransSettings()
    {
        try {
            $settings = AppSettingModel::getByGroup('payment');

            return $this->respond([
                'status' => 'success',
                'message' => 'Midtrans settings retrieved successfully',
                'data' => $settings
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to retrieve Midtrans settings',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Midtrans settings
     * PUT /admin/settings/payment/midtrans
     */
    public function updateMidtransSettings()
    {
        try {
            $data = $this->request->getJSON(true);

            $allowedKeys = [
                'midtrans_server_key',
                'midtrans_client_key',
                'midtrans_merchant_id',
                'midtrans_is_production',
                'midtrans_is_sanitized',
                'midtrans_is_3ds',
            ];

            $updated = [];

            foreach ($allowedKeys as $key) {
                if (isset($data[$key])) {
                    AppSettingModel::set($key, $data[$key]);
                    $updated[$key] = $data[$key];
                }
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Midtrans settings updated successfully',
                'data' => $updated
            ], ResponseInterface::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update Midtrans settings',
                'error' => $e->getMessage()
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
