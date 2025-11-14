<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\ShipmentModel;
use App\Models\ShipmentDetailModel;
use App\Models\JastipModel;
use App\Models\StatusModel;

class ShipmentController extends BaseApi
{
    protected $modelName = ShipmentModel::class;

    /**
     * Get all shipments
     */
    public function index()
    {
        $shipments = ShipmentModel::with('packages')->orderBy('created_at', 'desc')->get();
        return $this->respond($shipments);
    }

    /**
     * Get single shipment with packages
     */
    public function show($id = null)
    {
        $shipment = ShipmentModel::with(['packages'])->find($id);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        return $this->respond($shipment);
    }

    /**
     * Create new shipment
     */
    public function create()
    {
        $validation = $this->validate([
            'nomor_kontainer' => 'required|is_unique[shipments.nomor_kontainer]',
            'nama_kontainer' => 'permit_empty|max_length[255]',
            'tanggal_pengiriman' => 'required|valid_date',
            'estimasi_sampai' => 'permit_empty|valid_date',
            'keterangan' => 'permit_empty',
        ]);

        if (!$validation) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'nomor_kontainer' => $this->request->getPost('nomor_kontainer'),
            'nama_kontainer' => $this->request->getPost('nama_kontainer'),
            'tanggal_pengiriman' => $this->request->getPost('tanggal_pengiriman'),
            'estimasi_sampai' => $this->request->getPost('estimasi_sampai'),
            'status_pengiriman' => 'Persiapan',
            'keterangan' => $this->request->getPost('keterangan'),
            'total_paket' => 0,
            'total_bobot' => 0,
        ];

        $shipment = ShipmentModel::create($data);

        return $this->respondCreated([
            'messages' => [
                'success' => 'Pengiriman berhasil dibuat'
            ],
            'data' => $shipment
        ]);
    }

    /**
     * Update shipment
     */
    public function update($id = null)
    {
        $shipment = ShipmentModel::find($id);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        $validation = $this->validate([
            'nomor_kontainer' => "permit_empty|is_unique[shipments.nomor_kontainer,id,{$id}]",
            'nama_kontainer' => 'permit_empty|max_length[255]',
            'tanggal_pengiriman' => 'permit_empty|valid_date',
            'estimasi_sampai' => 'permit_empty|valid_date',
            'status_pengiriman' => 'permit_empty|in_list[Persiapan,Dalam Perjalanan,Sampai Tujuan,Selesai]',
            'keterangan' => 'permit_empty',
        ]);

        if (!$validation) {
            return $this->fail($this->validator->getErrors());
        }

        $data = $this->request->getPost();
        unset($data['id']);

        // Check if estimasi_sampai is being updated
        $estimasiSampaiBaru = $this->request->getPost('estimasi_sampai');
        $updateEstimasiJastip = false;

        if ($estimasiSampaiBaru && $estimasiSampaiBaru != $shipment->estimasi_sampai) {
            $updateEstimasiJastip = true;
        }

        $shipment->fill($data);
        $shipment->save();

        // Update estimasi_sampai for all packages in this shipment
        if ($updateEstimasiJastip) {
            JastipModel::where('shipment_id', $id)->update([
                'estimasi_sampai' => $estimasiSampaiBaru
            ]);
        }

        return $this->respond([
            'messages' => [
                'success' => 'Pengiriman berhasil diupdate'
            ],
            'data' => $shipment
        ]);
    }

    /**
     * Delete shipment
     */
    public function delete($id = null)
    {
        $shipment = ShipmentModel::find($id);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        // Remove shipment_id from related packages
        JastipModel::where('shipment_id', $id)->update([
            'shipment_id' => null,
            'estimasi_sampai' => null,
        ]);

        $shipment->delete();

        return $this->respondDeleted([
            'messages' => [
                'success' => 'Pengiriman berhasil dihapus'
            ]
        ]);
    }

    /**
     * Add packages to shipment
     */
    public function addPackages($id = null)
    {
        $shipment = ShipmentModel::find($id);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        $packageIds = $this->request->getPost('package_ids'); // Array of jastip IDs

        if (empty($packageIds) || !is_array($packageIds)) {
            return $this->fail('Pilih minimal satu paket untuk ditambahkan');
        }

        $estimasiSampai = $shipment->estimasi_sampai;
        $totalBobot = $shipment->total_bobot;
        $totalPaket = $shipment->total_paket;

        foreach ($packageIds as $jastipId) {
            // Check if package already in shipment
            $exists = ShipmentDetailModel::where('shipment_id', $id)
                ->where('jastip_id', $jastipId)
                ->first();

            if (!$exists) {
                // Add to shipment details
                ShipmentDetailModel::create([
                    'shipment_id' => $id,
                    'jastip_id' => $jastipId,
                ]);

                // Update jastip status and estimasi
                $jastip = JastipModel::find($jastipId);
                if ($jastip) {
                    $jastip->shipment_id = $id;
                    $jastip->estimasi_sampai = $estimasiSampai;
                    $jastip->status = 'Proses Pengiriman';
                    $jastip->save();

                    // Add to status history
                    StatusModel::create([
                        'jastip_id' => $jastipId,
                        'status' => 'Proses Pengiriman',
                    ]);

                    // Update totals
                    $totalBobot += $jastip->bobot;
                    $totalPaket++;
                }
            }
        }

        // Update shipment totals
        $shipment->total_bobot = $totalBobot;
        $shipment->total_paket = $totalPaket;
        $shipment->save();

        return $this->respond([
            'messages' => [
                'success' => 'Paket berhasil ditambahkan ke pengiriman'
            ],
            'data' => $shipment
        ]);
    }

    /**
     * Remove package from shipment
     */
    public function removePackage($shipmentId = null, $jastipId = null)
    {
        $shipment = ShipmentModel::find($shipmentId);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        // Remove from shipment details
        $detail = ShipmentDetailModel::where('shipment_id', $shipmentId)
            ->where('jastip_id', $jastipId)
            ->first();

        if ($detail) {
            $detail->delete();

            // Update jastip
            $jastip = JastipModel::find($jastipId);
            if ($jastip) {
                $jastip->shipment_id = null;
                $jastip->estimasi_sampai = null;
                $jastip->status = 'Pending';
                $jastip->save();

                // Update shipment totals
                $shipment->total_bobot -= $jastip->bobot;
                $shipment->total_paket -= 1;
                $shipment->save();

                // Add to status history
                StatusModel::create([
                    'jastip_id' => $jastipId,
                    'status' => 'Pending',
                ]);
            }
        }

        return $this->respond([
            'messages' => [
                'success' => 'Paket berhasil dihapus dari pengiriman'
            ]
        ]);
    }

    /**
     * Process shipment (change status)
     */
    public function processShipment($id = null)
    {
        $shipment = ShipmentModel::find($id);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        $status = $this->request->getPost('status');
        $estimasiSampai = $this->request->getPost('estimasi_sampai');

        if (!$status) {
            return $this->fail('Status pengiriman harus diisi');
        }

        // Update shipment status
        $shipment->status_pengiriman = $status;

        if ($estimasiSampai) {
            $shipment->estimasi_sampai = $estimasiSampai;
        }

        $shipment->save();

        // Update all packages in this shipment
        $packages = JastipModel::where('shipment_id', $id)->get();

        // Map shipment status to jastip status
        $statusMap = [
            'Persiapan' => 'Pending',
            'Dalam Perjalanan' => 'Proses Pengiriman',
            'Sampai Tujuan' => 'Sampai di Tujuan',
            'Selesai' => 'Selesai',
        ];

        $jastipStatus = $statusMap[$status] ?? 'Proses Pengiriman';

        foreach ($packages as $package) {
            $package->status = $jastipStatus;

            if ($estimasiSampai) {
                $package->estimasi_sampai = $estimasiSampai;
            }

            $package->save();

            // Add to status history
            StatusModel::create([
                'jastip_id' => $package->id,
                'status' => $jastipStatus,
            ]);
        }

        return $this->respond([
            'messages' => [
                'success' => 'Status pengiriman berhasil diupdate'
            ],
            'data' => $shipment
        ]);
    }

    /**
     * Get available packages (not yet in any shipment)
     */
    public function availablePackages()
    {
        $packages = JastipModel::whereNull('shipment_id')
            ->whereIn('status', ['Pending', 'Proses Pengiriman'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->respond($packages);
    }

    /**
     * Generate delivery note (surat jalan) data
     */
    public function deliveryNote($id = null)
    {
        $shipment = ShipmentModel::with(['packages'])->find($id);

        if (!$shipment) {
            return $this->failNotFound('Pengiriman tidak ditemukan');
        }

        $data = [
            'shipment' => $shipment,
            'packages' => $shipment->getPackagesWithDetails(),
            'generated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->respond($data);
    }
}
