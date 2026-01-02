<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Display list of products
     */
    public function index()
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'department' => $this->request->getGet('department'),
            'per_page' => $this->request->getGet('per_page') ?? 50,
        ];

        $data = [
            'title' => 'Products',
            'products' => $this->productModel->getProducts($filters),
            'pager' => $this->productModel->pager,
            'filters' => $filters,
            'categories' => $this->productModel->getCategories(),
            'departments' => $this->productModel->getDepartments(),
        ];

        return view('products/index', $data);
    }

    /**
     * Show form to create new product
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Product',
            'categories' => $this->productModel->getCategories(),
            'departments' => $this->productModel->getDepartments(),
        ];

        return view('products/create', $data);
    }

    /**
     * Store new product
     */
    public function store()
    {
        if (!$this->validate($this->productModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code' => $this->request->getPost('code'),
            'plu' => $this->request->getPost('plu'),
            'name' => $this->request->getPost('name'),
            'unit' => $this->request->getPost('unit'),
            'buy_price' => $this->request->getPost('buy_price') ?: 0,
            'sell_price' => $this->request->getPost('sell_price') ?: 0,
            'supplier' => $this->request->getPost('supplier'),
            'stock' => $this->request->getPost('stock') ?: 0,
            'department' => $this->request->getPost('department'),
            'category' => $this->request->getPost('category'),
        ];

        if ($this->productModel->insert($data)) {
            return redirect()->to('/products')->with('success', 'Product added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add product');
    }

    /**
     * Show form to edit product
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Product',
            'product' => $product,
            'categories' => $this->productModel->getCategories(),
            'departments' => $this->productModel->getDepartments(),
        ];

        return view('products/edit', $data);
    }

    /**
     * Update product
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = $this->productModel->getValidationRules();
        $rules['code'] = 'required|is_unique[products.code,id,' . $id . ']';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code' => $this->request->getPost('code'),
            'plu' => $this->request->getPost('plu'),
            'name' => $this->request->getPost('name'),
            'unit' => $this->request->getPost('unit'),
            'buy_price' => $this->request->getPost('buy_price') ?: 0,
            'sell_price' => $this->request->getPost('sell_price') ?: 0,
            'supplier' => $this->request->getPost('supplier'),
            'stock' => $this->request->getPost('stock') ?: 0,
            'department' => $this->request->getPost('department'),
            'category' => $this->request->getPost('category'),
        ];

        if ($this->productModel->update($id, $data)) {
            return redirect()->to('/products')->with('success', 'Product updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update product');
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        if ($this->productModel->delete($id)) {
            return redirect()->to('/products')->with('success', 'Product deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete product');
    }

    /**
     * Show import form
     */
    public function import()
    {
        $data = [
            'title' => 'Import Products',
        ];

        return view('products/import', $data);
    }

    /**
     * Process import file
     */
    public function processImport()
    {
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid file');
        }

        $extension = $file->getExtension();
        $allowedExtensions = ['xlsx', 'xls', 'csv'];

        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->with('error', 'Invalid file format. Only Excel (.xlsx, .xls) or CSV files are allowed');
        }

        try {
            // Load PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            $validData = [];
            $errors = [];
            $rowNumber = 2; // Start from row 2 (after header)

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }

                // Validate required fields
                $code = trim($row[0] ?? '');
                $name = trim($row[2] ?? '');

                if (empty($code)) {
                    $errors[] = "Row {$rowNumber}: Product code is required";
                    $rowNumber++;
                    continue;
                }

                if (empty($name)) {
                    $errors[] = "Row {$rowNumber}: Product name is required";
                    $rowNumber++;
                    continue;
                }

                // Track codes in current import to detect duplicates within file
                static $seenCodes = [];

                // Check if code already exists in database
                if ($this->productModel->where('code', $code)->first()) {
                    $errors[] = "Row {$rowNumber}: Product code '{$code}' already exists in database";
                    $rowNumber++;
                    continue;
                }

                // Check for duplicate within the import file
                if (isset($seenCodes[$code])) {
                    $errors[] = "Row {$rowNumber}: Duplicate product code '{$code}' (first seen at row {$seenCodes[$code]})";
                    $rowNumber++;
                    continue;
                }

                $seenCodes[$code] = $rowNumber;

                $validData[] = [
                    'code' => $code,
                    'plu' => trim($row[1] ?? ''),
                    'name' => $name,
                    'unit' => trim($row[3] ?? 'PCS'),
                    'buy_price' => floatval($row[4] ?? 0),
                    'sell_price' => floatval($row[5] ?? 0),
                    'supplier' => trim($row[6] ?? ''),
                    'stock' => floatval($row[7] ?? 0),
                    'department' => trim($row[8] ?? ''),
                    'category' => trim($row[9] ?? ''),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $rowNumber++;
            }

            // Store data in session for preview
            session()->set('import_data', $validData);
            session()->set('import_errors', $errors);

            return redirect()->to('/products/import-preview');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    /**
     * Show import preview
     */
    public function importPreview()
    {
        $validData = session()->get('import_data') ?? [];
        $errors = session()->get('import_errors') ?? [];

        if (empty($validData) && empty($errors)) {
            return redirect()->to('/products/import')->with('error', 'No data to preview');
        }

        $data = [
            'title' => 'Import Preview',
            'validData' => $validData,
            'errors' => $errors,
        ];

        return view('products/import_preview', $data);
    }

    /**
     * Confirm and execute import (optimized for large datasets)
     */
    public function confirmImport()
    {
        $validData = session()->get('import_data') ?? [];

        if (empty($validData)) {
            return redirect()->to('/products/import')->with('error', 'No data to import');
        }

        // Increase memory and time limits for large imports
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '600'); // 10 minutes

        // Increase MySQL packet size
        $db = \Config\Database::connect();
        $db->query("SET GLOBAL max_allowed_packet=67108864"); // 64MB

        try {
            // Use smaller chunks and commit per chunk instead of one big transaction
            $chunkSize = 50; // Further reduced for stability
            $chunks = array_chunk($validData, $chunkSize);
            $totalInserted = 0;
            $totalChunks = count($chunks);
            $failedChunks = [];

            log_message('info', "Starting import of " . count($validData) . " products in {$totalChunks} chunks");

            // Create import log file for this session
            $importLogFile = WRITEPATH . 'uploads/import_log_' . date('Y-m-d_His') . '.txt';
            $logHandle = fopen($importLogFile, 'w');
            fwrite($logHandle, "=== PRODUCT IMPORT LOG ===\n");
            fwrite($logHandle, "Date: " . date('Y-m-d H:i:s') . "\n");
            fwrite($logHandle, "Total Products to Import: " . count($validData) . "\n");
            fwrite($logHandle, "Total Chunks: {$totalChunks}\n");
            fwrite($logHandle, "=========================\n\n");

            foreach ($chunks as $chunkIndex => $chunk) {
                try {
                    // Log progress every 10 chunks
                    if ($chunkIndex % 10 === 0) {
                        log_message('info', "Processing chunk {$chunkIndex}/{$totalChunks} ({$totalInserted} imported so far)");
                    }

                    // Start transaction per chunk
                    $db->transStart();

                    // Insert batch with duplicate handling
                    $inserted = $this->productModel->insertBatch($chunk);

                    // Check if insertBatch returned false
                    if ($inserted === false) {
                        $error = $db->error();
                        $errorCode = $error['code'] ?? 0;
                        $errorMsg = $error['message'] ?? 'Unknown error';

                        // Log the error
                        log_message('error', "Chunk {$chunkIndex} insertBatch returned false - Code: {$errorCode}, Message: {$errorMsg}");

                        // Write to import log file
                        fwrite($logHandle, "CHUNK {$chunkIndex} FAILED (insertBatch returned false)\n");
                        fwrite($logHandle, "Error Code: {$errorCode}\n");
                        fwrite($logHandle, "Error Message: {$errorMsg}\n");
                        fwrite($logHandle, "Affected Products:\n");
                        foreach ($chunk as $item) {
                            fwrite($logHandle, "  - Code: {$item['code']}, Name: {$item['name']}\n");
                        }
                        fwrite($logHandle, "\n");

                        $db->transRollback();
                        $failedChunks[] = $chunkIndex;
                        continue; // Skip this chunk, try next
                    }

                    // Complete transaction for this chunk
                    $db->transComplete();

                    // Check transaction status
                    if ($db->transStatus() === false) {
                        $error = $db->error();
                        $errorCode = $error['code'] ?? 0;
                        $errorMsg = $error['message'] ?? 'Transaction failed';

                        // Check if it's a duplicate key error (code 1062)
                        if ($errorCode === 1062 || strpos($errorMsg, 'Duplicate entry') !== false) {
                            log_message('warning', "Chunk {$chunkIndex} skipped - contains duplicate entries");

                            // Write duplicate entries to log
                            fwrite($logHandle, "CHUNK {$chunkIndex} SKIPPED - DUPLICATE ENTRIES\n");
                            fwrite($logHandle, "Error: {$errorMsg}\n");
                            fwrite($logHandle, "Products in this chunk:\n");
                            foreach ($chunk as $item) {
                                fwrite($logHandle, "  - Code: {$item['code']}, Name: {$item['name']}\n");
                            }
                            fwrite($logHandle, "\n");
                        } else {
                            log_message('error', "Chunk {$chunkIndex} transaction failed - Code: {$errorCode}, Message: {$errorMsg}");

                            // Write other errors to log
                            fwrite($logHandle, "CHUNK {$chunkIndex} FAILED - TRANSACTION ERROR\n");
                            fwrite($logHandle, "Error Code: {$errorCode}\n");
                            fwrite($logHandle, "Error Message: {$errorMsg}\n");
                            fwrite($logHandle, "Products in this chunk:\n");
                            foreach ($chunk as $item) {
                                fwrite($logHandle, "  - Code: {$item['code']}, Name: {$item['name']}\n");
                            }
                            fwrite($logHandle, "\n");
                        }

                        $failedChunks[] = $chunkIndex;
                        continue;
                    }

                    $totalInserted += count($chunk);

                    // Free up memory every 20 chunks
                    if ($chunkIndex % 20 === 0) {
                        gc_collect_cycles();
                    }
                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();

                    // Check if it's a duplicate entry error
                    if (strpos($errorMsg, 'Duplicate entry') !== false) {
                        log_message('warning', "Chunk {$chunkIndex} skipped - duplicate entry: " . $errorMsg);

                        // Write duplicate to log
                        fwrite($logHandle, "CHUNK {$chunkIndex} EXCEPTION - DUPLICATE ENTRY\n");
                        fwrite($logHandle, "Error: {$errorMsg}\n");
                        fwrite($logHandle, "Products in this chunk:\n");
                        foreach ($chunk as $item) {
                            fwrite($logHandle, "  - Code: {$item['code']}, Name: {$item['name']}\n");
                        }
                        fwrite($logHandle, "\n");
                    } else {
                        log_message('error', "Chunk {$chunkIndex} exception: " . $errorMsg);
                        log_message('error', 'Stack trace: ' . $e->getTraceAsString());

                        // Write exception to log
                        fwrite($logHandle, "CHUNK {$chunkIndex} EXCEPTION\n");
                        fwrite($logHandle, "Error: {$errorMsg}\n");
                        fwrite($logHandle, "Products in this chunk:\n");
                        foreach ($chunk as $item) {
                            fwrite($logHandle, "  - Code: {$item['code']}, Name: {$item['name']}\n");
                        }
                        fwrite($logHandle, "\n");
                    }

                    $db->transRollback();
                    $failedChunks[] = $chunkIndex;
                    continue; // Try next chunk
                }
            }

            // Write summary to log file
            fwrite($logHandle, "\n=========================\n");
            fwrite($logHandle, "IMPORT SUMMARY\n");
            fwrite($logHandle, "=========================\n");
            fwrite($logHandle, "Total Processed: {$totalChunks} chunks\n");
            fwrite($logHandle, "Successfully Imported: {$totalInserted} products\n");
            fwrite($logHandle, "Failed Chunks: " . count($failedChunks) . "\n");
            if (!empty($failedChunks)) {
                fwrite($logHandle, "Failed Chunk Numbers: " . implode(', ', $failedChunks) . "\n");
            }
            fwrite($logHandle, "=========================\n");
            fclose($logHandle);

            // Store log file path in session for user download
            session()->set('import_log_file', $importLogFile);

            // Check if we had any failures
            if (!empty($failedChunks)) {
                $failedCount = count($failedChunks);
                $successCount = $totalInserted;
                log_message('warning', "Import completed with {$failedCount} failed chunks. Successfully imported {$successCount} products.");

                // Clear session data but keep log file path
                session()->remove('import_data');
                session()->remove('import_errors');

                $logFileName = basename($importLogFile);
                return redirect()->to('/products')->with('warning', "Partially imported {$successCount} products. {$failedCount} chunks failed. <a href='/products/download-import-log' class='btn btn-sm btn-info ml-2'>Download Error Log</a>");
            }

            // Clear session
            session()->remove('import_data');
            session()->remove('import_errors');
            session()->remove('import_log_file');

            return redirect()->to('/products')->with('success', "Successfully imported {$totalInserted} products");
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Product import failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'Failed to import: ' . $e->getMessage());
        }
    }

    /**
     * Download import error log
     */
    public function downloadImportLog()
    {
        $logFilePath = session()->get('import_log_file');

        if (!$logFilePath || !file_exists($logFilePath)) {
            return redirect()->to('/products')->with('error', 'Log file not found or has expired.');
        }

        $fileName = basename($logFilePath);

        return $this->response->download($logFilePath, null)
            ->setFileName($fileName);
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Code*', 'PLU', 'Name*', 'Unit', 'Buy Price', 'Sell Price', 'Supplier', 'Stock', 'Department', 'Category'];
        $sheet->fromArray($headers, null, 'A1');

        // Add sample data
        $sampleData = [
            ['BRG001', '1001', 'Sample Product 1', 'PCS', 10000, 15000, 'Supplier A', 100, 'IT', 'Electronics'],
            ['BRG002', '1002', 'Sample Product 2', 'BOX', 50000, 75000, 'Supplier B', 50, 'Office', 'Stationery'],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="product_import_template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * API: Search products by code, PLU, or name (accessible by all logged-in users)
     */
    public function apiSearch()
    {
        $keyword = $this->request->getGet('q');

        if (empty($keyword)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Search keyword required'
            ]);
        }

        // Trim keyword
        $keyword = trim($keyword);

        // Search products by code, PLU, or name (MySQL LIKE is case-insensitive by default)
        $products = $this->productModel
            ->groupStart()
            ->like('code', $keyword)
            ->orLike('plu', $keyword)
            ->orLike('name', $keyword)
            ->groupEnd()
            ->orderBy('code', 'ASC')
            ->limit(20)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $products,
            'count' => count($products)
        ]);
    }

    /**
     * Show import price form
     */
    public function importPrice()
    {
        $data = [
            'title' => 'Import Price Update',
        ];

        return view('products/import_price', $data);
    }

    /**
     * Process import price file
     */
    public function processImportPrice()
    {
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid file');
        }

        $extension = $file->getExtension();
        $allowedExtensions = ['xlsx', 'xls', 'csv'];

        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->with('error', 'Invalid file format. Only Excel (.xlsx, .xls) or CSV files are allowed');
        }

        try {
            // Load PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            $updated = 0;
            $notFound = [];
            $errors = [];
            $rowNumber = 2; // Start from row 2 (after header)

            $db = \Config\Database::connect();
            $db->transStart();

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }

                // Get data from row
                $code = trim($row[0] ?? '');
                $buyPrice = $row[1] ?? null;
                $sellPrice = $row[2] ?? null;

                if (empty($code)) {
                    $errors[] = "Row {$rowNumber}: Product code is required";
                    $rowNumber++;
                    continue;
                }

                // Find product by code
                $product = $this->productModel->where('code', $code)->first();

                if (!$product) {
                    $notFound[] = "Row {$rowNumber}: Product with code '{$code}' not found";
                    $rowNumber++;
                    continue;
                }

                // Prepare update data
                $updateData = [];

                if ($buyPrice !== null && $buyPrice !== '') {
                    $updateData['buy_price'] = floatval($buyPrice);
                }

                if ($sellPrice !== null && $sellPrice !== '') {
                    $updateData['sell_price'] = floatval($sellPrice);
                }

                // Only update if there's data to update
                if (!empty($updateData)) {
                    $updateData['updated_at'] = date('Y-m-d H:i:s');

                    if ($this->productModel->update($product['id'], $updateData)) {
                        $updated++;
                    } else {
                        $errors[] = "Row {$rowNumber}: Failed to update product '{$code}'";
                    }
                }

                $rowNumber++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Transaction failed. No products were updated.');
            }

            // Prepare success message
            $message = "Successfully updated {$updated} product prices";

            if (!empty($notFound)) {
                $message .= ". " . count($notFound) . " products not found";
            }

            if (!empty($errors)) {
                $message .= ". " . count($errors) . " errors occurred";
            }

            // Store details in session for display if needed
            if (!empty($notFound) || !empty($errors)) {
                session()->setFlashdata('import_details', [
                    'updated' => $updated,
                    'not_found' => $notFound,
                    'errors' => $errors
                ]);
            }

            return redirect()->to('/products')->with('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'Price import failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }
    }

    /**
     * Download price import template
     */
    public function downloadPriceTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Product Code*', 'Buy Price', 'Sell Price'];
        $sheet->fromArray($headers, null, 'A1');

        // Add sample data
        $sampleData = [
            ['BRG001', 10000, 15000],
            ['BRG002', 50000, 75000],
            ['BRG003', 25000, 35000],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        // Add notes
        $sheet->setCellValue('A6', 'Notes:');
        $sheet->setCellValue('A7', '1. Product Code is required and must match existing product code in database');
        $sheet->setCellValue('A8', '2. Leave Buy Price or Sell Price empty if you don\'t want to update it');
        $sheet->setCellValue('A9', '3. Both prices can be updated at the same time');
        $sheet->setCellValue('A10', '4. Only existing products will be updated');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

        // Style notes
        $notesStyle = [
            'font' => ['italic' => true, 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFCC']],
        ];
        $sheet->getStyle('A6:A10')->applyFromArray($notesStyle);

        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="price_update_template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
