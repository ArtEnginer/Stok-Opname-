<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseApi extends BaseController
{
    use ResponseTrait;

    protected $modelName;
    protected $load = [];
    protected $append = [];

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        $data = $this->modelName::all();
        if ($this->load) {
            $data = $data->load($this->load);
        }
        if ($this->append) {
            $data = $data->append($this->append);
        }
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function show($id = null)
    {
        $data = $this->modelName::find($id);
        if (!$data) {
            return $this->failNotFound('Data tidak ditemukan');
        }
        if ($this->load) {
            $data = $data->load($this->load);
        }
        if ($this->append) {
            $data = $data->append($this->append);
        }
        return $this->respond($data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return ResponseInterface|string|void
     */
    public function new()
    {
        return $this->fail(lang('RESTful.notImplemented', ['new']), 501);
    }

    public function beforeCreate(&$data) {}

    public function validateCreate(&$request)
    {
        return true;
    }
    public function validateUpdate(&$request)
    {
        return true;
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return ResponseInterface|string|void
     */
    public function create()
    {
        $data = new $this->modelName;

        $request = (array) $this->request->getVar();
        if ($this->validateCreate($request) == false) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data->fill($request);
        $this->beforeCreate($data);
        $data->save();
        $this->afterCreate($data);

        return $this->respond([
            'messages' => [
                'success' => 'data baru berhasil di tambahkan',
            ],
            'data' => $data,
        ]);
    }

    public function afterCreate(&$data) {}

    /**
     * Return the editable properties of a resource object
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function edit($id = null)
    {
        return $this->fail(lang('RESTful.notImplemented', ['edit']), 501);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function update($id = null)
    {
        $request = (array) $this->request->getVar();
        if ($this->validateUpdate($request) == false) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        if ($data = $this->modelName::find($id)) {
            $data->fill($this->request->getVar());

            $this->beforeUpdate($data);
            $data->save();
            $this->afterUpdate($data);

            return $this->respond([
                'messages' => [
                    'success' => 'data berhasil di simpan',
                ],
                'data' => $data,
            ]);
        }
        return $this->failNotFound('Data tidak ditemukan');
    }

    public function beforeUpdate(&$data) {}
    public function afterUpdate(&$data) {}
    public function beforeDelete(&$data) {}
    public function afterDelete(&$data) {}

    /**
     * Delete the designated resource object from the model
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface|string|void
     */
    public function delete($id = null)
    {
        if ($data = $this->modelName::find($id)) {
            $this->beforeDelete($data);
            $data->delete();
            $this->afterDelete($data);

            return $this->respondDeleted(
                [
                    'messages' => [
                        'success' => 'data berhasil di hapus',
                    ],
                    'data' => $data,
                ]
            );
        }
        return $this->failNotFound('Data tidak ditemukan');
    }

    /**
     * Set/change the expected response representation for returned objects
     *
     * @param string $format json/xml
     *
     * @return void
     */
    public function setFormat(string $format = 'json')
    {
        if (in_array($format, ['json', 'xml'], true)) {
            $this->format = $format;
        }
    }
}
