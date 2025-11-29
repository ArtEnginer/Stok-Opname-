<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ProductModel;

class TransactionController extends BaseController
{
    protected $transactionModel;
    protected $productModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Display list of transactions
     */
    public function index()
    {
        $data = [
            'title' => 'Transactions',
            'transactions' => $this->transactionModel
                ->select('transactions.*, products.code, products.name')
                ->join('products', 'products.id = transactions.product_id')
                ->orderBy('transaction_date', 'DESC')
                ->paginate(50),
            'pager' => $this->transactionModel->pager
        ];

        return view('transactions/index', $data);
    }

    /**
     * Show form to create new transaction
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Transaction',
            'products' => $this->productModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('transactions/create', $data);
    }

    /**
     * Store new transaction
     */
    public function store()
    {
        $rules = [
            'product_id' => 'required|numeric',
            'type' => 'required|in_list[purchase,sale]',
            'qty' => 'required|decimal',
            'price' => 'required|decimal',
            'transaction_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'product_id' => $this->request->getPost('product_id'),
            'type' => $this->request->getPost('type'),
            'qty' => $this->request->getPost('qty'),
            'price' => $this->request->getPost('price'),
            'reference_no' => $this->request->getPost('reference_no'),
            'notes' => $this->request->getPost('notes'),
            'transaction_date' => $this->request->getPost('transaction_date'),
        ];

        if ($this->transactionModel->insert($data)) {
            return redirect()->to('/transactions')->with('success', 'Transaction added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add transaction');
    }

    /**
     * Delete transaction
     */
    public function delete($id)
    {
        if ($this->transactionModel->delete($id)) {
            return redirect()->to('/transactions')->with('success', 'Transaction deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete transaction');
    }

    /**
     * Show import form
     */
    public function import()
    {
        $data = [
            'title' => 'Import Transactions',
        ];

        return view('transactions/import', $data);
    }

    /**
     * Process import file
     */
    public function processImport()
    {
        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid file');
        }

        $extension = $file->getClientExtension();
        if (!in_array($extension, ['csv', 'xlsx', 'xls'])) {
            return redirect()->back()->with('error', 'Only CSV, XLS, or XLSX files are allowed');
        }

        try {
            $filepath = $file->getTempName();
            $data = $this->parseFile($filepath, $extension);

            if (empty($data)) {
                return redirect()->back()->with('error', 'No valid data found in file');
            }

            // Store in session for preview
            session()->set('import_data', $data);
            return redirect()->to('/transactions/import-preview');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    /**
     * Parse uploaded file
     */
    private function parseFile($filepath, $extension)
    {
        $data = [];

        if ($extension === 'csv') {
            $handle = fopen($filepath, 'r');
            $header = fgetcsv($handle); // Skip header

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 5) {
                    $data[] = [
                        'product_code' => trim($row[0]),
                        'type' => trim($row[1]),
                        'qty' => trim($row[2]),
                        'price' => trim($row[3]),
                        'transaction_date' => trim($row[4]),
                        'reference_no' => trim($row[5] ?? ''),
                        'notes' => trim($row[6] ?? ''),
                    ];
                }
            }
            fclose($handle);
        } else {
            // Parse Excel file using PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) continue;
                if (count($row) < 5) continue;

                // Handle date properly from Excel
                $date = $row[4];
                if (is_numeric($date)) {
                    // Excel date serial number
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
                }

                $data[] = [
                    'product_code' => trim($row[0] ?? ''),
                    'type' => trim($row[1] ?? ''),
                    'qty' => trim($row[2] ?? ''),
                    'price' => trim($row[3] ?? ''),
                    'transaction_date' => trim($date),
                    'reference_no' => isset($row[5]) ? trim($row[5]) : '',
                    'notes' => isset($row[6]) ? trim($row[6]) : ''
                ];
            }
        }

        return $data;
    }

    /**
     * Show import preview
     */
    public function importPreview()
    {
        $importData = session()->get('import_data');

        if (!$importData) {
            return redirect()->to('/transactions/import')->with('error', 'No import data found');
        }

        // Validate and enrich data
        $validData = [];
        $errors = [];

        foreach ($importData as $index => $row) {
            $rowNum = $index + 2; // +2 because index starts at 0 and we skip header

            // Find product
            $product = $this->productModel->where('code', $row['product_code'])->first();

            if (!$product) {
                $errors[] = [
                    'row' => $rowNum,
                    'product_code' => $row['product_code'],
                    'type' => $row['type'] ?? '-',
                    'error' => "Product code '{$row['product_code']}' not found"
                ];
                continue;
            }

            // Validate type
            if (!in_array(strtolower($row['type']), ['purchase', 'sale', 'pembelian', 'penjualan'])) {
                $errors[] = [
                    'row' => $rowNum,
                    'product_code' => $row['product_code'],
                    'type' => $row['type'] ?? '-',
                    'error' => "Invalid type '{$row['type']}'. Must be: purchase, sale, pembelian, or penjualan"
                ];
                continue;
            }

            // Normalize type
            $type = strtolower($row['type']);
            if ($type === 'pembelian') $type = 'purchase';
            if ($type === 'penjualan') $type = 'sale';

            // Validate qty
            if (!is_numeric($row['qty']) || $row['qty'] <= 0) {
                $errors[] = [
                    'row' => $rowNum,
                    'product_code' => $row['product_code'],
                    'type' => $row['type'] ?? '-',
                    'error' => "Invalid quantity '{$row['qty']}'"
                ];
                continue;
            }

            // Validate price
            if (!is_numeric($row['price']) || $row['price'] < 0) {
                $errors[] = [
                    'row' => $rowNum,
                    'product_code' => $row['product_code'],
                    'type' => $row['type'] ?? '-',
                    'error' => "Invalid price '{$row['price']}'"
                ];
                continue;
            }

            // Validate date
            $date = date('Y-m-d', strtotime($row['transaction_date']));
            if (!$date || $date === '1970-01-01') {
                $errors[] = [
                    'row' => $rowNum,
                    'product_code' => $row['product_code'],
                    'type' => $row['type'] ?? '-',
                    'error' => "Invalid date '{$row['transaction_date']}'"
                ];
                continue;
            }

            $validData[] = [
                'product_id' => $product['id'],
                'product_code' => $product['code'],
                'product_name' => $product['name'],
                'type' => $type,
                'qty' => (float)$row['qty'],
                'price' => (float)$row['price'],
                'transaction_date' => $date,
                'reference_no' => $row['reference_no'],
                'notes' => $row['notes'],
            ];
        }

        $data = [
            'title' => 'Import Preview',
            'validData' => $validData,
            'errors' => $errors,
        ];

        return view('transactions/import_preview', $data);
    }

    /**
     * Confirm and save import (optimized with batch insert)
     */
    public function confirmImport()
    {
        $importData = session()->get('import_data');

        if (!$importData) {
            return redirect()->to('/transactions/import')->with('error', 'No import data found');
        }

        $db = \Config\Database::connect();

        try {
            $db->transStart();

            // Prepare batch data
            $batchData = [];
            $errorCount = 0;

            foreach ($importData as $row) {
                // Find product
                $product = $this->productModel->where('code', $row['product_code'])->first();

                if (!$product) {
                    $errorCount++;
                    log_message('error', 'Product not found: ' . $row['product_code']);
                    continue;
                }

                // Normalize type
                $type = strtolower($row['type']);
                if ($type === 'pembelian') $type = 'purchase';
                if ($type === 'penjualan') $type = 'sale';

                if (!in_array($type, ['purchase', 'sale'])) {
                    $errorCount++;
                    log_message('error', 'Invalid type: ' . $row['type']);
                    continue;
                }

                // Validate and prepare data
                if (!is_numeric($row['qty']) || $row['qty'] <= 0) {
                    $errorCount++;
                    log_message('error', 'Invalid qty: ' . $row['qty']);
                    continue;
                }

                $date = date('Y-m-d', strtotime($row['transaction_date']));
                if (!$date || $date === '1970-01-01') {
                    $errorCount++;
                    log_message('error', 'Invalid date: ' . $row['transaction_date']);
                    continue;
                }

                $batchData[] = [
                    'product_id' => $product['id'],
                    'type' => $type,
                    'qty' => (float)$row['qty'],
                    'price' => (float)$row['price'],
                    'transaction_date' => $date,
                    'reference_no' => $row['reference_no'] ?? '',
                    'notes' => $row['notes'] ?? '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            // Batch insert in chunks of 500
            $successCount = 0;
            if (!empty($batchData)) {
                $chunks = array_chunk($batchData, 500);
                foreach ($chunks as $chunkIndex => $chunk) {
                    try {
                        $inserted = $this->transactionModel->insertBatch($chunk);

                        // Check if insertBatch failed
                        if ($inserted === false) {
                            $error = $db->error();
                            log_message('error', 'Transaction batch insert failed at chunk ' . $chunkIndex . ': ' . json_encode($error));
                            throw new \Exception('Database error: ' . ($error['message'] ?? 'Unknown error'));
                        }

                        $successCount += count($chunk);
                    } catch (\Exception $e) {
                        log_message('error', 'Transaction import chunk ' . $chunkIndex . ' failed: ' . $e->getMessage());
                        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                        throw $e;
                    }
                }
            }

            $db->transComplete();

            // Check transaction status
            if ($db->transStatus() === false) {
                $error = $db->error();
                log_message('error', 'Transaction failed with error: ' . json_encode($error));
                $db->transRollback();

                return redirect()->to('/transactions/import')
                    ->with('error', 'Failed to import: ' . ($error['message'] ?? 'Transaction failed'));
            }

            // Clear session
            session()->remove('import_data');

            if ($errorCount > 0) {
                $message = "Import completed. Success: $successCount, Failed: $errorCount";
                return redirect()->to('/transactions')->with('warning', $message);
            }

            return redirect()->to('/transactions')->with('success', "Successfully imported $successCount transactions");
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Transaction import failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return redirect()->to('/transactions/import')
                ->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Product Code*', 'Type*', 'Quantity*', 'Price*', 'Date*', 'Reference No', 'Notes'];
        $sheet->fromArray($headers, null, 'A1');

        // Add sample data
        $sampleData = [
            ['BRG001', 'purchase', 10, 50000, date('Y-m-d'), 'PO-001', 'Sample purchase transaction'],
            ['BRG002', 'sale', 5, 75000, date('Y-m-d'), 'SO-001', 'Sample sale transaction'],
            ['BRG001', 'pembelian', 20, 48000, date('Y-m-d'), 'PO-002', 'Indonesian version - purchase'],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="transaction_import_template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
