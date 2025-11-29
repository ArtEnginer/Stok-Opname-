<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LocationModel;

class LocationController extends BaseController
{
    protected $locationModel;
    protected $validation;

    public function __construct()
    {
        $this->locationModel = new LocationModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Display location list
     */
    public function index()
    {
        $data = [
            'title' => 'Master Lokasi',
            'locations' => $this->locationModel->orderBy('kode_lokasi', 'ASC')->findAll()
        ];

        return view('admin/location/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Lokasi',
            'validation' => $this->validation
        ];

        return view('admin/location/create', $data);
    }

    /**
     * Store new location
     */
    public function store()
    {
        $rules = [
            'kode_lokasi' => 'required|max_length[50]|is_unique[locations.kode_lokasi]',
            'nama_lokasi' => 'required|max_length[255]',
            'departemen'  => 'permit_empty|max_length[100]',
            'status'      => 'required|in_list[aktif,tidak_aktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode_lokasi' => strtoupper($this->request->getPost('kode_lokasi')),
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'departemen'  => $this->request->getPost('departemen'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'status'      => $this->request->getPost('status')
        ];

        if ($this->locationModel->insert($data)) {
            return redirect()->to('/admin/location')->with('success', 'Lokasi berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan lokasi');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $location = $this->locationModel->find($id);

        if (!$location) {
            return redirect()->to('/admin/location')->with('error', 'Lokasi tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Lokasi',
            'location' => $location,
            'validation' => $this->validation
        ];

        return view('admin/location/edit', $data);
    }

    /**
     * Update location
     */
    public function update($id)
    {
        $location = $this->locationModel->find($id);

        if (!$location) {
            return redirect()->to('/admin/location')->with('error', 'Lokasi tidak ditemukan');
        }

        $rules = [
            'kode_lokasi' => "required|max_length[50]|is_unique[locations.kode_lokasi,id,{$id}]",
            'nama_lokasi' => 'required|max_length[255]',
            'departemen'  => 'permit_empty|max_length[100]',
            'status'      => 'required|in_list[aktif,tidak_aktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode_lokasi' => strtoupper($this->request->getPost('kode_lokasi')),
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'departemen'  => $this->request->getPost('departemen'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'status'      => $this->request->getPost('status')
        ];

        if ($this->locationModel->update($id, $data)) {
            return redirect()->to('/admin/location')->with('success', 'Lokasi berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui lokasi');
        }
    }

    /**
     * Delete location
     */
    public function delete($id)
    {
        $location = $this->locationModel->find($id);

        if (!$location) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan'
            ]);
        }

        // Check if location is used in stock opname
        $db = \Config\Database::connect();
        $builder = $db->table('stock_opname_items');
        $count = $builder->where('location_id', $id)->countAllResults();

        if ($count > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Lokasi tidak dapat dihapus karena sudah digunakan dalam stock opname'
            ]);
        }

        if ($this->locationModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus lokasi'
            ]);
        }
    }

    /**
     * Get location data for datatables
     */
    public function getData()
    {
        $request = service('request');
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $length = $request->getPost('length');
        $searchValue = $request->getPost('search')['value'];

        $builder = $this->locationModel->builder();

        // Total records
        $totalRecords = $builder->countAllResults(false);

        // Search
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('kode_lokasi', $searchValue)
                ->orLike('nama_lokasi', $searchValue)
                ->orLike('departemen', $searchValue)
                ->groupEnd();
        }

        // Filtered records
        $filteredRecords = $builder->countAllResults(false);

        // Get data
        $builder->orderBy('kode_lokasi', 'ASC');
        $locations = $builder->limit($length, $start)->get()->getResultArray();

        $data = [];
        $no = $start + 1;
        foreach ($locations as $location) {
            $statusBadge = $location['status'] == 'aktif'
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Tidak Aktif</span>';

            $row = [
                'no' => $no++,
                'kode_lokasi' => $location['kode_lokasi'],
                'nama_lokasi' => $location['nama_lokasi'],
                'departemen' => $location['departemen'] ?? '-',
                'status' => $statusBadge,
                'action' => '
                    <a href="' . base_url('admin/location/edit/' . $location['id']) . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $location['id'] . '">
                        <i class="fas fa-trash"></i>
                    </button>
                '
            ];

            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get location options for select2
     */
    public function getOptions()
    {
        $search = $this->request->getGet('search');
        $departemen = $this->request->getGet('departemen');

        $builder = $this->locationModel->builder();
        $builder->select('id, kode_lokasi, nama_lokasi, departemen');
        $builder->where('status', 'aktif');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('kode_lokasi', $search)
                ->orLike('nama_lokasi', $search)
                ->groupEnd();
        }

        if (!empty($departemen)) {
            $builder->where('departemen', $departemen);
        }

        $builder->orderBy('kode_lokasi', 'ASC');
        $locations = $builder->get()->getResultArray();

        $data = [];
        foreach ($locations as $location) {
            $data[] = [
                'id' => $location['id'],
                'text' => $location['kode_lokasi'] . ' - ' . $location['nama_lokasi'] .
                    ($location['departemen'] ? ' (' . $location['departemen'] . ')' : '')
            ];
        }

        return $this->response->setJSON($data);
    }

    /**
     * Get location status for SO
     */
    public function getSOStatus($soHeaderId = null)
    {
        if ($soHeaderId) {
            $locations = $this->locationModel->getLocationsWithSOStatus($soHeaderId);
        } else {
            $locations = $this->locationModel->getLocationsWithSOStatus();
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Export location to Excel/CSV
     */
    public function export()
    {
        $locations = $this->locationModel->orderBy('kode_lokasi', 'ASC')->findAll();

        $data = [
            'title' => 'Data Lokasi',
            'locations' => $locations
        ];

        return view('admin/location/export', $data);
    }

    /**
     * API: Get locations list with pagination and filters
     */
    public function apiList()
    {
        $search = $this->request->getGet('search');
        $departemen = $this->request->getGet('departemen');
        $status = $this->request->getGet('status');
        $page = max(1, intval($this->request->getGet('page')));
        $perPage = 10;

        $builder = $this->locationModel->builder();

        // Apply filters
        if (!empty($search)) {
            $builder->groupStart()
                ->like('kode_lokasi', $search)
                ->orLike('nama_lokasi', $search)
                ->orLike('departemen', $search)
                ->groupEnd();
        }

        if (!empty($departemen)) {
            $builder->where('departemen', $departemen);
        }

        if (!empty($status)) {
            $builder->where('status', $status);
        }

        // Get totals
        $totalRecords = $builder->countAllResults(false);

        // Get paginated data
        $offset = ($page - 1) * $perPage;
        $locations = $builder->orderBy('kode_lokasi', 'ASC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        // Get stats
        $stats = [
            'total' => $this->locationModel->countAll(),
            'active' => $this->locationModel->where('status', 'aktif')->countAllResults(false),
            'inactive' => $this->locationModel->where('status', 'tidak_aktif')->countAllResults(false),
            'departments' => $this->locationModel->distinct()->select('departemen')->where('departemen IS NOT NULL')->where('departemen !=', '')->countAllResults()
        ];

        // Get department list
        $departments = $this->locationModel->distinct()
            ->select('departemen')
            ->where('departemen IS NOT NULL')
            ->where('departemen !=', '')
            ->orderBy('departemen', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $locations,
            'stats' => $stats,
            'departments' => array_column($departments, 'departemen'),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalRecords,
                'total_pages' => ceil($totalRecords / $perPage)
            ]
        ]);
    }

    /**
     * API: Search locations with debouncing support
     */
    public function apiSearch()
    {
        $query = $this->request->getGet('q');
        $limit = max(1, min(50, intval($this->request->getGet('limit') ?? 20)));

        $builder = $this->locationModel->builder();
        $builder->select('id, kode_lokasi, nama_lokasi, departemen')
            ->where('status', 'aktif');

        if (!empty($query)) {
            $builder->groupStart()
                ->like('kode_lokasi', $query)
                ->orLike('nama_lokasi', $query)
                ->groupEnd();
        }

        $locations = $builder->orderBy('kode_lokasi', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $results = array_map(function ($loc) {
            return [
                'id' => $loc['id'],
                'code' => $loc['kode_lokasi'],
                'name' => $loc['nama_lokasi'],
                'department' => $loc['departemen'],
                'label' => $loc['kode_lokasi'] . ' - ' . $loc['nama_lokasi']
            ];
        }, $locations);

        return $this->response->setJSON([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Show import form
     */
    public function import()
    {
        $data = [
            'title' => 'Import Lokasi'
        ];

        return view('admin/location/import', $data);
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_lokasi.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Add BOM for Excel UTF-8 support
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Headers
        fputcsv($output, ['kode_lokasi', 'nama_lokasi', 'departemen', 'keterangan', 'status']);

        // Example data
        fputcsv($output, ['RAK-A-01', 'Rack A Floor 1', 'Warehouse', 'Left side main warehouse', 'aktif']);
        fputcsv($output, ['RAK-A-02', 'Rack A Floor 2', 'Warehouse', 'Left side level 2', 'aktif']);
        fputcsv($output, ['SHOW-01', 'Showroom Display 1', 'Showroom', 'Front display area', 'aktif']);

        fclose($output);
        exit;
    }

    /**
     * Process import file
     */
    public function processImport()
    {
        $file = $this->request->getFile('import_file');
        $skipDuplicates = $this->request->getPost('skip_duplicates');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        $ext = $file->getExtension();
        if (!in_array($ext, ['csv', 'xlsx', 'xls'])) {
            return redirect()->back()->with('error', 'Format file harus CSV atau Excel');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        try {
            if ($ext === 'csv') {
                $handle = fopen($file->getTempName(), 'r');

                // Skip header
                fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 2) continue;

                    $kode = strtoupper(trim($row[0]));
                    $nama = trim($row[1]);
                    $departemen = isset($row[2]) ? trim($row[2]) : null;
                    $keterangan = isset($row[3]) ? trim($row[3]) : null;
                    $status = isset($row[4]) ? trim($row[4]) : 'aktif';

                    if (empty($kode) || empty($nama)) {
                        $errors[] = "Baris dilewati: kode atau nama kosong";
                        continue;
                    }

                    // Check duplicate
                    $existing = $this->locationModel->where('kode_lokasi', $kode)->first();

                    if ($existing) {
                        if ($skipDuplicates) {
                            $skipped++;
                            continue;
                        } else {
                            // Update existing
                            $this->locationModel->update($existing['id'], [
                                'nama_lokasi' => $nama,
                                'departemen' => $departemen,
                                'keterangan' => $keterangan,
                                'status' => $status
                            ]);
                            $imported++;
                        }
                    } else {
                        // Insert new
                        $this->locationModel->insert([
                            'kode_lokasi' => $kode,
                            'nama_lokasi' => $nama,
                            'departemen' => $departemen,
                            'keterangan' => $keterangan,
                            'status' => $status
                        ]);
                        $imported++;
                    }
                }

                fclose($handle);
            } else {
                // For Excel files, would need PHPSpreadsheet library
                return redirect()->back()->with('error', 'Format Excel belum didukung. Gunakan CSV.');
            }

            $message = "Import selesai: {$imported} data berhasil";
            if ($skipped > 0) {
                $message .= ", {$skipped} duplikat dilewati";
            }
            if (count($errors) > 0) {
                $message .= ". " . count($errors) . " baris error.";
            }

            return redirect()->to('/admin/location')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
