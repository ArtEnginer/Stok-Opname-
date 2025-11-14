<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class FileController extends Controller
{
    /**
     * Serve files from writable/uploads directory
     * 
     * @param string $folder - subfolder dalam uploads (campaigns, receipts, etc)
     * @param string $filename - nama file yang akan diakses
     * @return ResponseInterface
     */
    public function serve(string $folder = null, string $filename = null): ResponseInterface
    {
        // Validasi parameter
        if (!$folder || !$filename) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        // Sanitize input untuk keamanan
        $folder = $this->sanitizePath($folder);
        $filename = $this->sanitizePath($filename);

        // Build file path
        $filePath = WRITEPATH . 'uploads/' . $folder . '/' . $filename;

        // Check if file exists
        if (!file_exists($filePath) || !is_file($filePath)) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        // Security check - pastikan file ada di dalam writable/uploads
        $realPath = realpath($filePath);
        $uploadsPath = realpath(WRITEPATH . 'uploads');

        if (strpos($realPath, $uploadsPath) !== 0) {
            return $this->response->setStatusCode(403, 'Access denied');
        }

        // Get file info
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->file($filePath);

        // Set headers
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000');
        $this->response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

        // Read and output file
        $this->response->setBody(file_get_contents($filePath));

        return $this->response;
    }

    /**
     * Sanitize path untuk mencegah directory traversal
     * 
     * @param string $path
     * @return string
     */
    private function sanitizePath(string $path): string
    {
        // Remove any directory traversal attempts
        $path = str_replace(['../', '..\\', '...', '\\'], '', $path);
        $path = trim($path, '/\\');

        return $path;
    }

    /**
     * Serve campaign images
     * 
     * @param string $filename
     * @return ResponseInterface
     */
    public function campaigns(string $filename): ResponseInterface
    {
        return $this->serve('campaigns', $filename);
    }

    /**
     * Serve receipt/payment proof images
     * 
     * @param string $filename
     * @return ResponseInterface
     */
    public function receipts(string $filename): ResponseInterface
    {
        return $this->serve('receipts', $filename);
    }

    /**
     * Serve campaign update images
     * 
     * @param string $filename
     * @return ResponseInterface
     */
    public function updates(string $filename): ResponseInterface
    {
        return $this->serve('updates', $filename);
    }

    /**
     * Download file instead of displaying
     * 
     * @param string $folder
     * @param string $filename
     * @return ResponseInterface
     */
    public function download(string $folder = null, string $filename = null): ResponseInterface
    {
        if (!$folder || !$filename) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        $folder = $this->sanitizePath($folder);
        $filename = $this->sanitizePath($filename);
        $filePath = WRITEPATH . 'uploads/' . $folder . '/' . $filename;

        if (!file_exists($filePath) || !is_file($filePath)) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        // Security check
        $realPath = realpath($filePath);
        $uploadsPath = realpath(WRITEPATH . 'uploads');

        if (strpos($realPath, $uploadsPath) !== 0) {
            return $this->response->setStatusCode(403, 'Access denied');
        }

        // Force download
        return $this->response->download($filePath, null);
    }
}
