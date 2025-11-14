<?php

if (!function_exists('upload_url')) {
    /**
     * Generate URL untuk file yang ada di writable/uploads
     * 
     * @param string $folder - subfolder dalam uploads (campaigns, receipts, updates, etc)
     * @param string|null $filename - nama file
     * @return string
     */
    function upload_url(string $folder, ?string $filename = null): string
    {
        if (!$filename) {
            return '';
        }

        return base_url("uploads/{$folder}/{$filename}");
    }
}

if (!function_exists('campaign_image_url')) {
    /**
     * Generate URL untuk gambar campaign
     * 
     * @param string|null $filename
     * @return string
     */
    function campaign_image_url(?string $filename = null): string
    {
        if (!$filename) {
            return base_url('assets/images/no-image.png'); // default image jika tidak ada
        }

        return upload_url('campaigns', $filename);
    }
}

if (!function_exists('receipt_url')) {
    /**
     * Generate URL untuk bukti pembayaran
     * 
     * @param string|null $filename
     * @return string
     */
    function receipt_url(?string $filename = null): string
    {
        if (!$filename) {
            return '';
        }

        return upload_url('receipts', $filename);
    }
}

if (!function_exists('update_image_url')) {
    /**
     * Generate URL untuk gambar campaign update
     * 
     * @param string|null $filename
     * @return string
     */
    function update_image_url(?string $filename = null): string
    {
        if (!$filename) {
            return '';
        }

        return upload_url('updates', $filename);
    }
}

if (!function_exists('download_url')) {
    /**
     * Generate URL untuk download file
     * 
     * @param string $folder
     * @param string|null $filename
     * @return string
     */
    function download_url(string $folder, ?string $filename = null): string
    {
        if (!$filename) {
            return '';
        }

        return base_url("files/download/{$folder}/{$filename}");
    }
}

if (!function_exists('check_upload_exists')) {
    /**
     * Cek apakah file upload ada
     * 
     * @param string $folder
     * @param string $filename
     * @return bool
     */
    function check_upload_exists(string $folder, string $filename): bool
    {
        $filePath = WRITEPATH . 'uploads/' . $folder . '/' . $filename;
        return file_exists($filePath) && is_file($filePath);
    }
}
