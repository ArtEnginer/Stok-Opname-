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
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'kode_lokasi');
        $sheet->setCellValue('B1', 'nama_lokasi');
        $sheet->setCellValue('C1', 'departemen');
        $sheet->setCellValue('D1', 'keterangan');
        $sheet->setCellValue('E1', 'status');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Example data
        $sheet->setCellValue('A2', 'RAK-A-01');
        $sheet->setCellValue('B2', 'Rack A Floor 1');
        $sheet->setCellValue('C2', 'Warehouse');
        $sheet->setCellValue('D2', 'Left side main warehouse');
        $sheet->setCellValue('E2', 'aktif');

        $sheet->setCellValue('A3', 'RAK-A-02');
        $sheet->setCellValue('B3', 'Rack A Floor 2');
        $sheet->setCellValue('C3', 'Warehouse');
        $sheet->setCellValue('D3', 'Left side level 2');
        $sheet->setCellValue('E3', 'aktif');

        $sheet->setCellValue('A4', 'SHOW-01');
        $sheet->setCellValue('B4', 'Showroom Display 1');
        $sheet->setCellValue('C4', 'Showroom');
        $sheet->setCellValue('D4', 'Front display area');
        $sheet->setCellValue('E4', 'aktif');

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'template_import_lokasi_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
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
        if ($ext !== 'xlsx') {
            return redirect()->back()->with('error', 'Format file harus Excel (.xlsx)');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        try {
            // Load Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 because we skipped header and arrays are 0-indexed

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $kode = isset($row[0]) ? strtoupper(trim($row[0])) : '';
                $nama = isset($row[1]) ? trim($row[1]) : '';
                $departemen = isset($row[2]) ? trim($row[2]) : null;
                $keterangan = isset($row[3]) ? trim($row[3]) : null;
                $status = isset($row[4]) ? trim($row[4]) : 'aktif';

                // Validate required fields
                if (empty($kode) || empty($nama)) {
                    $errors[] = "Baris {$rowNum}: kode_lokasi dan nama_lokasi harus diisi";
                    continue;
                }

                // Validate status
                if (!in_array($status, ['aktif', 'tidak_aktif'])) {
                    $status = 'aktif';
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

            $message = "Import selesai: {$imported} data berhasil diimport";
            if ($skipped > 0) {
                $message .= ", {$skipped} duplikat dilewati";
            }
            if (count($errors) > 0) {
                $message .= ". " . count($errors) . " baris error";
            }

            return redirect()->to('/admin/location')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
