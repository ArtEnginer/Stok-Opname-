<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\JastipDetailModel;
use App\Models\JastipModel;
use App\Models\StatusModel;

class JastipController extends BaseApi
{
    protected $modelName = JastipModel::class;


    public function validateCreate(&$request)
    {
        return $this->validate([
            'nama_penerima' => 'required',
            'alamat_penerima' => 'required',
            'no_telp_penerima' => 'required',
            'biaya' => 'required|numeric',
            'bobot' => 'required|numeric',
            'keterangan' => 'permit_empty|max_length[255]',
            'catatan' => 'permit_empty|max_length[255]',
        ]);
    }

    public function afterUpdate(&$data)
    {
        // create status history
        $status = new StatusModel();
        $status->jastip_id = $data->id;
        $status->status = $data->status;
        $status->save();
    }


    public function trackPackage($resi)
    {
        $jastip = JastipModel::with('statusHistory')->where('nomor_resi', $resi)->first();
        if (!$jastip) {
            return $this->failNotFound('Nomor resi tidak ditemukan');
        }
        return $this->respond($jastip);
    }
}
