<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\Files\Exceptions\FileException;
use CodeIgniter\Files\File;

class SourceController extends BaseController
{
    public function storage($path = '')
    {

        $cleanPath = realpath(WRITEPATH . 'uploads/' . $path);
        $basePath = realpath(WRITEPATH . 'uploads');
        if (!$cleanPath || strpos($cleanPath, $basePath) !== 0 || !is_file($cleanPath)) {
            throw new FileException('Filepath tidak valid');
        }
        $file = new File($cleanPath);
        return $this->response
            ->setHeader('Content-Type', $file->getMimeType())
            ->setBody(file_get_contents($cleanPath));
    }
}
