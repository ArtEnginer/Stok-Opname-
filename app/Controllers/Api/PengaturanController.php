<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApi;
use App\Models\PengaturanModel;
use CodeIgniter\HTTP\ResponseInterface;

class PengaturanController extends BaseApi
{
    protected PengaturanModel $model;

    public function __construct()
    {
        $this->model = new PengaturanModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        try {
            $data = $this->model->all();
            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        try {
            $data = $this->model->find($id);
            if (!$data) {
                return $this->failNotFound('Data pengaturan tidak ditemukan');
            }
            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'nominal_per_kg' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Nominal per kg harus diisi',
                    'numeric' => 'Nominal per kg harus berupa angka',
                    'greater_than' => 'Nominal per kg harus lebih dari 0'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $data = [
                'nominal_per_kg' => $this->request->getPost('nominal_per_kg'),
            ];

            $pengaturan = $this->model->create($data);

            return $this->respondCreated([
                'messages' => [
                    'success' => 'Data pengaturan berhasil ditambahkan'
                ],
                'data' => $pengaturan
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $rules = [
            'nominal_per_kg' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Nominal per kg harus diisi',
                    'numeric' => 'Nominal per kg harus berupa angka',
                    'greater_than' => 'Nominal per kg harus lebih dari 0'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $pengaturan = $this->model->find($id);
            if (!$pengaturan) {
                return $this->failNotFound('Data pengaturan tidak ditemukan');
            }

            $data = [
                'nominal_per_kg' => $this->request->getPost('nominal_per_kg'),
            ];

            $pengaturan->update($data);

            return $this->respond([
                'messages' => [
                    'success' => 'Data pengaturan berhasil diperbarui'
                ],
                'data' => $pengaturan
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        try {
            $pengaturan = $this->model->find($id);
            if (!$pengaturan) {
                return $this->failNotFound('Data pengaturan tidak ditemukan');
            }

            $pengaturan->delete();

            return $this->respondDeleted([
                'messages' => [
                    'success' => 'Data pengaturan berhasil dihapus'
                ]
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }
}
