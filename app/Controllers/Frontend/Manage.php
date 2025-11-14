<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\PenggunaModel;
use App\Models\WisataModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Manage extends BaseController
{
    protected PenggunaModel $user;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger,
    ) {
        parent::initController($request, $response, $logger);
        $this->user = PenggunaModel::find(auth()->user()->id);
        $this->view->setData([
            "user" => $this->user,
        ]);
    }
    public function index()
    {
        $this->view->setData([
            "page" => "dashboard",
        ]);

        if (auth()->user()->inGroup('user')) {
            return redirect()->to(route_to('panel'));
        }
        return $this->view->render("pages/panel/admin/index");
    }
    public function jastip(): string
    {
        $this->view->setData([
            "page" => "jastip",


        ]);
        return $this->view->render("pages/panel/admin/jastip");
    }
    // tracking page
    public function tracking(): string
    {
        $this->view->setData([
            "page" => "tracking",
        ]);
        return $this->view->render("pages/panel/admin/tracking");
    }

    public function pengaturan(): string
    {
        $this->view->setData([
            "page" => "pengaturan",
        ]);
        return $this->view->render("pages/panel/admin/pengaturan");
    }

    public function user(): string
    {
        $this->view->setData([
            "page" => "user",
        ]);
        return $this->view->render("pages/panel/admin/user");
    }

    public function shipment(): string
    {
        $this->view->setData([
            "page" => "shipment",
        ]);
        return $this->view->render("pages/panel/admin/shipment");
    }
}
