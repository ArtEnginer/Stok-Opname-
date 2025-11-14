<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class TrackingController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Tracking Jastip'
        ];

        return view('pages/panel/admin/tracking', $data);
    }
}
