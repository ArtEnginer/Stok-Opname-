<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class PublicTrackingController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Tracking Jastip'
        ];

        return view('pages/tracking/public', $data);
    }
}
