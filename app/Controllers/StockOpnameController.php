<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\StockOpnameSessionModel;
use App\Models\StockOpnameItemModel;
use App\Models\LocationModel;
use CodeIgniter\HTTP\ResponseInterface;

class StockOpnameController extends BaseController
{
    protected $productModel;
    protected $transactionModel;
    protected $sessionModel;
    protected $itemModel;
    protected $locationModel;
    protected $db;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->transactionModel = new TransactionModel();
        $this->sessionModel = new StockOpnameSessionModel();
        $this->itemModel = new StockOpnameItemModel();
        $this->locationModel = new LocationModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Display list of all SO sessions
     */
    public function index()
    {
        $filters = [
            'status' => $this->request->getGet('status'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
        ];

        $data = [
            'title' => 'Stock Opname Sessions',
            'sessions' => $this->sessionModel->getSessions($filters),
            'filters' => $filters
        ];

        return view('stock_opname/index', $data);
    }

    /**
     * Show form to create new SO session
     */
    public function create()
    {
        $data = [
            'title' => 'Create New Stock Opname Session',
            'suggestedCode' => $this->sessionModel->generateSessionCode(),
        ];

        return view('stock_opname/create', $data);
    }

    /**
     * Store new SO session and generate baseline for all products
     */
    public function store()
    {
        $rules = [
            'session_code' => 'required|is_unique[stock_opname_sessions.session_code]',
            'session_date' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $sessionData = [
            'session_code' => $this->request->getPost('session_code'),
            'session_date' => $this->request->getPost('session_date'),
            'notes' => $this->request->getPost('notes'),
            'status' => 'open',
        ];

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Create session
        $sessionId = $this->sessionModel->insert($sessionData);

        if (!$sessionId) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Failed to create session');
        }

        // Generate baseline for all products
        $this->generateBaseline($sessionId, $sessionData['session_date']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create session');
        }

        return redirect()->to('/stock-opname/' . $sessionId)->with('success', 'Session created successfully');
    }

    /**
     * Generate baseline stock for all products in a session
     * 
     * LOGIC BASELINE DINAMIS:
     * 1. Jika barang SUDAH DIHITUNG di SO terakhir:
     *    Baseline = Stok Fisik SO Terakhir + Mutasi (setelah SO terakhir s/d sekarang)
     * 
     * 2. Jika barang BELUM DIHITUNG di SO terakhir:
     *    Baseline = Stok Sistem + Mutasi (setelah SO terakhir s/d sekarang)
     * 
     * 3. Jika belum ada SO sebelumnya:
     *    Baseline = Stok Sistem saat ini
     * 
     * Mutasi = Total Pembelian - Total Penjualan (dalam periode)
     */
    protected function generateBaseline($sessionId, $sessionDate)
    {
        // Get all products
        $products = $this->productModel->findAll();

        // Get last closed session
        $lastSession = $this->sessionModel->getLastClosedSession();

        $items = [];

        foreach ($products as $product) {
            $baselineStock = 0;

            if ($lastSession) {
                // Ada SO sebelumnya, cek apakah barang ini pernah dihitung
                $lastItem = $this->itemModel->getItemBySessionProduct($lastSession['id'], $product['id']);

                if ($lastItem && $lastItem['is_counted'] && $lastItem['physical_stock'] !== null) {
                    // ✅ SUDAH DIHITUNG di SO terakhir
                    // Baseline = Stok Fisik SO Terakhir + Mutasi
                    $baselineStock = (float)$lastItem['physical_stock'];

                    // Hitung mutasi dari tanggal setelah SO terakhir sampai SO baru
                    // Contoh: SO terakhir tgl 16, SO baru tgl 20
                    // Maka mutasi = transaksi tgl 17-20
                    $mutation = $this->transactionModel->getMutation(
                        $product['id'],
                        $lastSession['session_date'],
                        $sessionDate
                    );

                    $baselineStock += $mutation;
                } else {
                    // ❌ BELUM DIHITUNG di SO terakhir
                    // Baseline = Stok Sistem + Mutasi
                    $baselineStock = (float)$product['stock'];

                    // Mutasi dihitung dari tanggal setelah SO terakhir sampai SO baru
                    $mutation = $this->transactionModel->getMutation(
                        $product['id'],
                        $lastSession['session_date'],
                        $sessionDate
                    );

                    $baselineStock += $mutation;
                }
            } else {
                // Belum ada SO sebelumnya (SO Pertama)
                // Baseline = Stok Sistem saat ini
                $baselineStock = (float)$product['stock'];
            }

            $items[] = [
                'session_id' => $sessionId,
                'product_id' => $product['id'],
                'baseline_stock' => $baselineStock,
                'original_baseline_stock' => $baselineStock, // Simpan baseline asli untuk kalkulasi real-time
                'physical_stock' => null,
                'difference' => 0,
                'is_counted' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Bulk insert all items
        if (!empty($items)) {
            $this->itemModel->bulkInsert($items);
        }
    }

    /**
     * Show SO session detail with all items (optimized with pagination)
     */
    public function show($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filters = [
            'search' => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'department' => $this->request->getGet('department'),
            'is_counted' => $this->request->getGet('is_counted'),
            'per_page' => $this->request->getGet('per_page') ?? 50,
        ];

        // Get paginated items
        $items = $this->itemModel->getItemsBySession($sessionId, $filters);

        // Tentukan tanggal referensi: freeze date atau real-time
        // Fallback untuk backward compatibility jika kolom belum ada
        $isBaselineFrozen = isset($session['is_baseline_frozen']) ? $session['is_baseline_frozen'] : false;
        $baselineFreezeDate = isset($session['baseline_freeze_date']) ? $session['baseline_freeze_date'] : null;

        $referenceDate = $isBaselineFrozen && $baselineFreezeDate
            ? $baselineFreezeDate
            : date('Y-m-d');

        // Update baseline real-time untuk setiap item berdasarkan mutasi
        foreach ($items as &$item) {
            // Hitung mutasi dari tanggal SO sampai reference date
            $mutation = $this->transactionModel->getMutation(
                $item['product_id'],
                $session['session_date'],
                $referenceDate
            );

            // Store mutation info untuk ditampilkan
            $item['current_mutation'] = $mutation;

            // Baseline real-time = original_baseline + mutasi
            $item['baseline_stock'] = (float)$item['original_baseline_stock'] + $mutation;

            // Jika sudah dihitung, hitung mutation detail
            if ($item['is_counted'] && $item['counted_date']) {
                // Mutation dari session_date ke counted_date
                $item['mutation_at_count'] = $this->transactionModel->getMutation(
                    $item['product_id'],
                    $session['session_date'],
                    $item['counted_date']
                );

                // Mutation dari counted_date ke reference_date
                $item['mutation_after_count'] = $this->transactionModel->getMutation(
                    $item['product_id'],
                    $item['counted_date'],
                    $referenceDate
                );

                // Adjusted physical stock
                $item['adjusted_physical'] = (float)$item['physical_stock'] + $item['mutation_after_count'];

                // Recalculate difference dengan baseline real-time
                $item['difference'] = (float)$item['physical_stock'] - $item['baseline_stock'];
            }
        }

        $summary = $this->itemModel->getSessionSummary($sessionId);

        // Tambahkan mutation summary untuk dashboard
        $mutationSummary = $this->getMutationSummary($sessionId);

        // Get location status for this SO
        $locationStatus = $this->locationModel->getLocationsWithSOStatus($sessionId);

        // Get uncounted locations (locations with items not yet counted)
        $uncountedLocations = $this->locationModel->getUncountedLocations($sessionId);

        // Get locations without any counted items
        $locationsWithoutItems = $this->locationModel->getLocationsWithoutItems($sessionId);

        $data = [
            'title' => 'Stock Opname: ' . $session['session_code'],
            'session' => $session,
            'items' => $items,
            'pager' => $this->itemModel->pager,
            'summary' => $summary,
            'mutation_summary' => $mutationSummary,
            'filters' => $filters,
            'categories' => $this->productModel->getCategories(),
            'departments' => $this->productModel->getDepartments(),
            'locations' => $this->locationModel->getActiveLocations(),
            'location_status' => $locationStatus,
            'uncounted_locations' => $uncountedLocations,
            'locations_without_items' => $locationsWithoutItems,
            'reference_date' => $referenceDate,
        ];

        return view('stock_opname/show', $data);
    }

    /**
     * Update physical stock for an item
     * Recalculate baseline jika ada mutasi dari tanggal SO ke tanggal hitung
     */
    public function updateItem($itemId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $physicalStock = $this->request->getPost('physical_stock');
        $notes = $this->request->getPost('notes');
        $countedDate = $this->request->getPost('counted_date') ?: date('Y-m-d');
        $countedBy = $this->request->getPost('counted_by');
        $location = $this->request->getPost('location');
        $locationId = $this->request->getPost('location_id');

        // Convert empty string to null for location_id
        if ($locationId === '' || $locationId === null) {
            $locationId = null;
        }

        if ($physicalStock === null || $physicalStock === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Physical stock is required']);
        }

        // Get item dan session info
        $item = $this->itemModel->find($itemId);
        $session = $this->sessionModel->find($item['session_id']);

        // Recalculate baseline dengan mutasi dari tanggal SO sampai tanggal hitung
        $mutationAtCount = $this->transactionModel->getMutation(
            $item['product_id'],
            $session['session_date'],
            $countedDate
        );

        // Baseline yang sudah adjust dengan mutasi REAL-TIME dari original_baseline
        $adjustedBaseline = (float)$item['original_baseline_stock'] + $mutationAtCount;
        $difference = (float)$physicalStock - $adjustedBaseline;

        // UPDATE BASELINE REAL-TIME di database dengan mutation tracking
        $updateData = [
            'baseline_stock' => $adjustedBaseline,
            'physical_stock' => $physicalStock,
            'difference' => $difference,
            'mutation_at_count' => $mutationAtCount,
            'is_counted' => true,
            'counted_date' => $countedDate,
            'counted_by' => $countedBy,
            'location' => $location,
            'location_id' => $locationId,
            'notes' => $notes
        ];

        $result = $this->itemModel->update($itemId, $updateData);

        if ($result) {
            $item = $this->itemModel->find($itemId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $item,
                'mutation_at_count' => $mutationAtCount,
                'adjusted_baseline' => $adjustedBaseline
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update item']);
    }

    /**
     * Show batch input form for multiple items by location
     */
    public function batchInput($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            return redirect()->to('/stock-opname')->with('error', 'Session not found');
        }

        if ($session['status'] !== 'open') {
            return redirect()->to('/stock-opname/' . $sessionId)->with('error', 'Cannot input on closed session');
        }

        // Get unique locations that already exist
        $existingLocations = $this->itemModel
            ->select('location')
            ->where('session_id', $sessionId)
            ->where('location IS NOT NULL')
            ->where('location !=', '')
            ->groupBy('location')
            ->findAll();

        $data = [
            'title' => 'Batch Input by Location',
            'session' => $session,
            'existingLocations' => array_column($existingLocations, 'location')
        ];

        return view('stock_opname/batch_input', $data);
    }

    /**
     * Search item by code/PLU/name for batch input
     */
    public function searchItem($sessionId)
    {
        // Log untuk debugging
        log_message('info', "searchItem called with sessionId: $sessionId");

        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            log_message('error', "searchItem: Session not found");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session not found'
            ]);
        }

        if ($session['status'] !== 'open') {
            log_message('error', "searchItem: Session is closed");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session is closed'
            ]);
        }

        $keyword = $this->request->getGet('q');
        $uncountedOnly = $this->request->getGet('uncounted_only') ?? '1'; // Default to uncounted only

        log_message('info', "searchItem: keyword='$keyword', uncountedOnly='$uncountedOnly'");

        if (empty($keyword)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Search keyword required'
            ]);
        }

        // Trim keyword
        $keyword = trim($keyword);

        // Search in stock_opname_items
        // MySQL LIKE is case-insensitive by default for most collations
        $builder = $this->db->table('stock_opname_items soi')
            ->select('soi.id, soi.baseline_stock, soi.original_baseline_stock, soi.physical_stock, soi.is_counted, p.id as product_id, p.code, p.plu, p.name, p.unit, p.category')
            ->join('products p', 'p.id = soi.product_id')
            ->where('soi.session_id', $sessionId);

        // Only filter by is_counted if requested
        if ($uncountedOnly === '1') {
            $builder->where('soi.is_counted', 0);
        }

        $builder->groupStart()
            ->like('p.code', $keyword)
            ->orLike('p.plu', $keyword)
            ->orLike('p.name', $keyword)
            ->groupEnd()
            ->limit(20);

        $items = $builder->get()->getResultArray();

        log_message('info', "searchItem: Found " . count($items) . " items");

        return $this->response->setJSON([
            'success' => true,
            'items' => $items,
            'keyword' => $keyword,
            'count' => count($items)
        ]);
    }

    /**
     * Save batch input
     */
    public function saveBatchInput($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session || $session['status'] !== 'open') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid session'
            ]);
        }

        $location = $this->request->getPost('location');
        $locationId = $this->request->getPost('location_id');
        $countedDate = $this->request->getPost('counted_date');
        $countedBy = $this->request->getPost('counted_by');
        $items = $this->request->getPost('items'); // Array of item_id => physical_stock

        // Convert empty string to null for location_id
        if ($locationId === '' || $locationId === null) {
            $locationId = null;
        }

        if (empty($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No items to save'
            ]);
        }

        $successCount = 0;
        $errorCount = 0;
        $mutations = [];

        foreach ($items as $itemId => $physicalStock) {
            // Skip if physical stock is empty
            if ($physicalStock === '' || $physicalStock === null) {
                continue;
            }

            $item = $this->itemModel->find($itemId);
            if (!$item || $item['session_id'] != $sessionId) {
                $errorCount++;
                continue;
            }

            // Calculate mutation dari session_date ke counted_date
            $mutationAtCount = $this->transactionModel->getMutation(
                $item['product_id'],
                $session['session_date'],
                $countedDate
            );

            // Calculate adjusted baseline dari original_baseline
            $adjustedBaseline = (float)$item['original_baseline_stock'] + $mutationAtCount;
            $difference = (float)$physicalStock - $adjustedBaseline;

            // Update item dengan baseline real-time dan mutation tracking
            $updateData = [
                'baseline_stock' => $adjustedBaseline,
                'physical_stock' => $physicalStock,
                'difference' => $difference,
                'mutation_at_count' => $mutationAtCount,
                'is_counted' => 1,
                'counted_date' => $countedDate,
                'counted_by' => $countedBy,
                'location' => $location,
                'location_id' => $locationId,
            ];

            if ($this->itemModel->update($itemId, $updateData)) {
                $successCount++;
                if ($mutationAtCount != 0) {
                    $mutations[] = [
                        'item_id' => $itemId,
                        'mutation' => $mutationAtCount
                    ];
                }
            } else {
                $errorCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Successfully saved {$successCount} items" . ($errorCount > 0 ? ", {$errorCount} failed" : ""),
            'saved_count' => $successCount,
            'error_count' => $errorCount,
            'mutations' => $mutations
        ]);
    }

    /**
     * Close SO session and update system stock
     */
    public function close($id)
    {
        $session = $this->sessionModel->find($id);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found');
        }

        if ($session['status'] === 'closed') {
            return redirect()->back()->with('error', 'Session already closed');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Get all counted items
        $countedItems = $this->itemModel->getCountedItems($id);

        // Get close date (today)
        $closeDate = date('Y-m-d');

        // Update system stock for counted items with mutation adjustment
        foreach ($countedItems as $item) {
            // Calculate mutation from counted_date to close_date untuk physical
            $mutationAfterCount = $this->transactionModel->getMutation(
                $item['product_id'],
                $item['counted_date'] ?: $session['session_date'], // If no counted_date, use session_date
                $closeDate
            );

            // Calculate baseline dari session_date ke close_date (real-time penuh dari original)
            $baselineMutation = $this->transactionModel->getMutation(
                $item['product_id'],
                $session['session_date'],
                $closeDate
            );

            // Adjusted physical stock = physical_stock (saat dihitung) + mutation (dari tgl hitung ke tgl tutup)
            $adjustedPhysicalStock = (float)$item['physical_stock'] + $mutationAfterCount;

            // Adjusted baseline stock = original_baseline + mutation (dari tgl SO ke tgl tutup)
            $adjustedBaselineStock = (float)$item['original_baseline_stock'] + $baselineMutation;

            // Recalculate difference with adjusted values
            $adjustedDifference = $adjustedPhysicalStock - $adjustedBaselineStock;

            // Update stock_opname_items with adjusted values
            $this->itemModel->update($item['id'], [
                'physical_stock' => $adjustedPhysicalStock,
                'baseline_stock' => $adjustedBaselineStock,
                'difference' => $adjustedDifference
            ]);

            // Update product stock with adjusted physical stock
            $this->productModel->update($item['product_id'], [
                'stock' => $adjustedPhysicalStock
            ]);
        }

        // Close session with close date
        $this->sessionModel->update($id, [
            'status' => 'closed',
            'closed_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to close session');
        }

        return redirect()->to('/stock-opname/' . $id)->with('success', 'Session closed successfully. Baseline, physical stock, and system stock updated with mutation adjustments.');
    }

    /**
     * Reopen a closed session (for corrections)
     */
    public function reopen($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found');
        }

        if ($session['status'] === 'open') {
            return redirect()->back()->with('error', 'Session is already open');
        }

        $result = $this->sessionModel->update($sessionId, [
            'status' => 'open',
            'closed_at' => null
        ]);

        if ($result) {
            return redirect()->to('/stock-opname/' . $sessionId)->with('success', 'Session reopened successfully');
        }

        return redirect()->back()->with('error', 'Failed to reopen session');
    }

    /**
     * Export session to Excel/CSV
     */
    public function export($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $items = $this->itemModel->getItemsBySession($sessionId);

        // Simple CSV export
        $filename = 'SO_' . $session['session_code'] . '_' . date('YmdHis') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, [
            'Code',
            'PLU',
            'Name',
            'Unit',
            'Category',
            'Department',
            'Original Baseline',
            'Mutation',
            'Real-Time Baseline',
            'Physical Stock',
            'Difference',
            'Status',
            'Counted Date',
            'Notes'
        ]);

        // Data
        foreach ($items as $item) {
            fputcsv($output, [
                $item['code'],
                $item['plu'],
                $item['name'],
                $item['unit'],
                $item['category'],
                $item['department'],
                $item['original_baseline_stock'],
                $item['mutation_at_count'] ?? 0,
                $item['baseline_stock'],
                $item['physical_stock'],
                $item['difference'],
                $item['is_counted'] ? 'Counted' : 'Not Counted',
                $item['counted_date'] ?? '-',
                $item['notes']
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Freeze baseline at specific date
     */
    public function freezeBaseline($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found');
        }

        if ($session['status'] === 'closed') {
            return redirect()->back()->with('error', 'Cannot freeze closed session');
        }

        $freezeDate = $this->request->getPost('freeze_date') ?: date('Y-m-d');

        $result = $this->sessionModel->update($sessionId, [
            'baseline_freeze_date' => $freezeDate,
            'is_baseline_frozen' => true
        ]);

        if ($result) {
            return redirect()->back()->with('success', 'Baseline frozen at ' . $freezeDate . '. Baseline will no longer update with new transactions.');
        }

        return redirect()->back()->with('error', 'Failed to freeze baseline');
    }

    /**
     * Unfreeze baseline (back to real-time)
     */
    public function unfreezeBaseline($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found');
        }

        if ($session['status'] === 'closed') {
            return redirect()->back()->with('error', 'Cannot unfreeze closed session');
        }

        $result = $this->sessionModel->update($sessionId, [
            'is_baseline_frozen' => false
        ]);

        if ($result) {
            return redirect()->back()->with('success', 'Baseline unfrozen. Now using real-time baseline based on current transactions.');
        }

        return redirect()->back()->with('error', 'Failed to unfreeze baseline');
    }

    /**
     * Get mutation summary per day during SO session
     */
    protected function getMutationSummary($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            return [];
        }

        // Fallback untuk backward compatibility
        $isBaselineFrozen = isset($session['is_baseline_frozen']) ? $session['is_baseline_frozen'] : false;
        $baselineFreezeDate = isset($session['baseline_freeze_date']) ? $session['baseline_freeze_date'] : null;

        $referenceDate = $isBaselineFrozen && $baselineFreezeDate
            ? $baselineFreezeDate
            : date('Y-m-d');

        // Get daily mutations
        $builder = $this->db->table('transactions t')
            ->select('
                DATE(t.transaction_date) as date,
                SUM(CASE WHEN t.type = "purchase" THEN t.qty ELSE 0 END) as total_purchase,
                SUM(CASE WHEN t.type = "sale" THEN t.qty ELSE 0 END) as total_sale,
                (SUM(CASE WHEN t.type = "purchase" THEN t.qty ELSE 0 END) - 
                 SUM(CASE WHEN t.type = "sale" THEN t.qty ELSE 0 END)) as net_mutation,
                COUNT(DISTINCT t.product_id) as affected_products
            ')
            ->where('t.transaction_date >', $session['session_date'])
            ->where('t.transaction_date <=', $referenceDate)
            ->groupBy('DATE(t.transaction_date)')
            ->orderBy('date', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * AJAX: Get mutation detail for specific product
     */
    public function getMutationDetail($sessionId, $productId)
    {
        // Log untuk debugging
        log_message('info', "getMutationDetail called with sessionId: $sessionId, productId: $productId");

        if (!$this->request->isAJAX()) {
            log_message('error', 'getMutationDetail: Not AJAX request');
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            log_message('error', "getMutationDetail: Session $sessionId not found");
            return $this->response->setJSON(['success' => false, 'message' => 'Session not found']);
        }

        // Fallback untuk backward compatibility
        $isBaselineFrozen = isset($session['is_baseline_frozen']) ? $session['is_baseline_frozen'] : false;
        $baselineFreezeDate = isset($session['baseline_freeze_date']) ? $session['baseline_freeze_date'] : null;

        $referenceDate = $isBaselineFrozen && $baselineFreezeDate
            ? $baselineFreezeDate
            : date('Y-m-d');

        // Get all transactions for this product during session
        $transactions = $this->transactionModel
            ->where('product_id', $productId)
            ->where('transaction_date >', $session['session_date'])
            ->where('transaction_date <=', $referenceDate)
            ->orderBy('transaction_date', 'ASC')
            ->findAll();

        log_message('info', "Found " . count($transactions) . " transactions for product $productId");

        // Calculate cumulative mutation
        $cumulativeMutation = 0;
        $details = [];

        foreach ($transactions as $trans) {
            $qty = $trans['type'] === 'purchase' ? $trans['qty'] : -$trans['qty'];
            $cumulativeMutation += $qty;

            $details[] = [
                'date' => $trans['transaction_date'],
                'type' => $trans['type'],
                'qty' => $trans['qty'],
                'reference_no' => $trans['reference_no'],
                'cumulative_mutation' => $cumulativeMutation
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'session_date' => $session['session_date'],
            'reference_date' => $referenceDate,
            'is_frozen' => $isBaselineFrozen,
            'total_mutation' => $cumulativeMutation,
            'transactions' => $details
        ]);
    }

    /**
     * Print Final Report - Real-time data
     */
    public function printReport($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get filter parameters
        $filters = [
            'category' => $this->request->getGet('category'),
            'department' => $this->request->getGet('department'),
            'is_counted' => $this->request->getGet('is_counted'),
            'show_variance_only' => $this->request->getGet('show_variance_only'),
        ];

        // Determine reference date (real-time)
        $isBaselineFrozen = isset($session['is_baseline_frozen']) ? $session['is_baseline_frozen'] : false;
        $baselineFreezeDate = isset($session['baseline_freeze_date']) ? $session['baseline_freeze_date'] : null;
        $referenceDate = $isBaselineFrozen && $baselineFreezeDate ? $baselineFreezeDate : date('Y-m-d');

        // Get all items with product details
        $builder = $this->db->table('stock_opname_items soi')
            ->select('soi.*, p.code, p.plu, p.name, p.unit, p.category, p.department, p.buy_price, p.sell_price')
            ->join('products p', 'p.id = soi.product_id')
            ->where('soi.session_id', $sessionId);

        // Apply filters
        if (!empty($filters['category'])) {
            $builder->where('p.category', $filters['category']);
        }
        if (!empty($filters['department'])) {
            $builder->where('p.department', $filters['department']);
        }
        if ($filters['is_counted'] !== null && $filters['is_counted'] !== '') {
            $builder->where('soi.is_counted', $filters['is_counted']);
        }

        $builder->orderBy('p.department', 'ASC')
            ->orderBy('p.category', 'ASC')
            ->orderBy('p.code', 'ASC');

        $items = $builder->get()->getResultArray();

        // Calculate real-time data for each item
        $reportData = [];
        $totalSystemStock = 0;
        $totalPhysicalStock = 0;
        $totalVariance = 0;
        $totalVarianceValue = 0;
        $totalSurplusValue = 0;
        $totalShortageValue = 0;

        foreach ($items as &$item) {
            // Calculate mutation from session_date to reference_date
            $mutation = $this->transactionModel->getMutation(
                $item['product_id'],
                $session['session_date'],
                $referenceDate
            );

            // Real-time baseline (system stock)
            $systemStock = (float)$item['original_baseline_stock'] + $mutation;
            $item['system_stock'] = $systemStock;
            $item['mutation'] = $mutation;

            // Physical stock (if counted)
            $physicalStock = $item['is_counted'] ? (float)$item['physical_stock'] : null;

            // If counted, adjust physical stock for mutations after count
            if ($item['is_counted'] && $item['counted_date']) {
                $mutationAfterCount = $this->transactionModel->getMutation(
                    $item['product_id'],
                    $item['counted_date'],
                    $referenceDate
                );
                $adjustedPhysical = (float)$item['physical_stock'] + $mutationAfterCount;
                $item['adjusted_physical'] = $adjustedPhysical;
                $item['mutation_after_count'] = $mutationAfterCount;
            } else {
                $item['adjusted_physical'] = $physicalStock;
                $item['mutation_after_count'] = 0;
            }

            // Calculate variance
            if ($item['is_counted']) {
                $variance = $item['adjusted_physical'] - $systemStock;
                $item['variance'] = $variance;
                $item['variance_value'] = $variance * (float)$item['buy_price'];

                $totalPhysicalStock += $item['adjusted_physical'];
                $totalVariance += $variance;
                $totalVarianceValue += $item['variance_value'];

                if ($variance > 0) {
                    $totalSurplusValue += $item['variance_value'];
                } else {
                    $totalShortageValue += abs($item['variance_value']);
                }
            } else {
                $item['variance'] = null;
                $item['variance_value'] = null;
            }

            $totalSystemStock += $systemStock;

            // Filter by variance only
            if ($filters['show_variance_only'] === '1') {
                if (!$item['is_counted'] || $item['variance'] == 0) {
                    continue;
                }
            }

            $reportData[] = $item;
        }

        // Get summary by department
        $summaryByDept = [];
        foreach ($reportData as $item) {
            $dept = $item['department'] ?: 'No Department';
            if (!isset($summaryByDept[$dept])) {
                $summaryByDept[$dept] = [
                    'total_items' => 0,
                    'counted_items' => 0,
                    'total_system' => 0,
                    'total_physical' => 0,
                    'total_variance' => 0,
                    'total_variance_value' => 0,
                    'surplus_value' => 0,
                    'shortage_value' => 0,
                ];
            }
            $summaryByDept[$dept]['total_items']++;
            $summaryByDept[$dept]['total_system'] += $item['system_stock'];

            if ($item['is_counted']) {
                $summaryByDept[$dept]['counted_items']++;
                $summaryByDept[$dept]['total_physical'] += $item['adjusted_physical'];
                $summaryByDept[$dept]['total_variance'] += $item['variance'];
                $summaryByDept[$dept]['total_variance_value'] += $item['variance_value'];

                if ($item['variance'] > 0) {
                    $summaryByDept[$dept]['surplus_value'] += $item['variance_value'];
                } else {
                    $summaryByDept[$dept]['shortage_value'] += abs($item['variance_value']);
                }
            }
        }

        $data = [
            'title' => 'Final Report: ' . $session['session_code'],
            'session' => $session,
            'items' => $reportData,
            'filters' => $filters,
            'reference_date' => $referenceDate,
            'is_frozen' => $isBaselineFrozen,
            'summary' => [
                'total_items' => count($reportData),
                'total_system_stock' => $totalSystemStock,
                'total_physical_stock' => $totalPhysicalStock,
                'total_variance' => $totalVariance,
                'total_variance_value' => $totalVarianceValue,
                'total_surplus_value' => $totalSurplusValue,
                'total_shortage_value' => $totalShortageValue,
            ],
            'summary_by_dept' => $summaryByDept,
            'categories' => $this->productModel->getCategories(),
            'departments' => $this->productModel->getDepartments(),
            'print_date' => date('Y-m-d H:i:s'),
        ];

        return view('stock_opname/print_report', $data);
    }

    /**
     * Export Final Report to Excel
     */
    public function exportReport($sessionId)
    {
        $session = $this->sessionModel->find($sessionId);

        if (!$session) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get filter parameters
        $filters = [
            'category' => $this->request->getGet('category'),
            'department' => $this->request->getGet('department'),
            'is_counted' => $this->request->getGet('is_counted'),
            'show_variance_only' => $this->request->getGet('show_variance_only'),
        ];

        // Determine reference date (real-time)
        $isBaselineFrozen = isset($session['is_baseline_frozen']) ? $session['is_baseline_frozen'] : false;
        $baselineFreezeDate = isset($session['baseline_freeze_date']) ? $session['baseline_freeze_date'] : null;
        $referenceDate = $isBaselineFrozen && $baselineFreezeDate ? $baselineFreezeDate : date('Y-m-d');

        // Get all items
        $builder = $this->db->table('stock_opname_items soi')
            ->select('soi.*, p.code, p.plu, p.name, p.unit, p.category, p.department, p.buy_price')
            ->join('products p', 'p.id = soi.product_id')
            ->where('soi.session_id', $sessionId);

        if (!empty($filters['category'])) {
            $builder->where('p.category', $filters['category']);
        }
        if (!empty($filters['department'])) {
            $builder->where('p.department', $filters['department']);
        }
        if ($filters['is_counted'] !== null && $filters['is_counted'] !== '') {
            $builder->where('soi.is_counted', $filters['is_counted']);
        }

        $builder->orderBy('p.department', 'ASC')
            ->orderBy('p.category', 'ASC')
            ->orderBy('p.code', 'ASC');

        $items = $builder->get()->getResultArray();

        // Create Excel using PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Stock Opname Report');

        // Header Info
        $sheet->setCellValue('A1', 'LAPORAN STOCK OPNAME');
        $sheet->setCellValue('A2', 'Session: ' . $session['session_code']);
        $sheet->setCellValue('A3', 'Tanggal: ' . date('d/m/Y', strtotime($session['session_date'])));
        $sheet->setCellValue('A4', 'Status: ' . ucfirst($session['status']));
        $sheet->setCellValue('A5', 'Dicetak: ' . date('d/m/Y H:i:s'));

        // Merge header cells
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');
        $sheet->mergeCells('A4:K4');
        $sheet->mergeCells('A5:K5');

        // Style header info
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Table Header (Row 7)
        $headers = [
            'A7' => 'No',
            'B7' => 'Code',
            'C7' => 'PLU',
            'D7' => 'Nama Barang',
            'E7' => 'Department',
            'F7' => 'Kategori',
            'G7' => 'Harga Beli',
            'H7' => 'Stok Sistem',
            'I7' => 'Stok Fisik',
            'J7' => 'Selisih',
            'K7' => 'Nominal'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style table header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A7:K7')->applyFromArray($headerStyle);

        // Data rows
        $row = 8;
        $no = 1;
        $totalVarianceValue = 0;
        $totalSurplus = 0;
        $totalShortage = 0;
        $countedItems = 0;
        $uncountedItems = 0;

        foreach ($items as $item) {
            // Calculate real-time data
            $mutation = $this->transactionModel->getMutation(
                $item['product_id'],
                $session['session_date'],
                $referenceDate
            );
            $systemStock = (float)$item['original_baseline_stock'] + $mutation;

            $physicalStock = null;
            $variance = null;
            $varianceValue = null;

            if ($item['is_counted']) {
                $countedItems++;
                if ($item['counted_date']) {
                    $mutationAfterCount = $this->transactionModel->getMutation(
                        $item['product_id'],
                        $item['counted_date'],
                        $referenceDate
                    );
                    $adjustedPhysical = (float)$item['physical_stock'] + $mutationAfterCount;
                } else {
                    $adjustedPhysical = (float)$item['physical_stock'];
                }

                $physicalStock = $adjustedPhysical;
                $variance = $adjustedPhysical - $systemStock;
                $varianceValue = $variance * (float)$item['buy_price'];
                $totalVarianceValue += $varianceValue;

                if ($variance > 0) {
                    $totalSurplus += $varianceValue;
                } elseif ($variance < 0) {
                    $totalShortage += abs($varianceValue);
                }

                // Skip if show_variance_only and no variance
                if ($filters['show_variance_only'] === '1' && $variance == 0) {
                    continue;
                }
            } else {
                $uncountedItems++;
                if ($filters['show_variance_only'] === '1') {
                    continue;
                }
            }

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $item['code']);
            $sheet->setCellValue('C' . $row, $item['plu']);
            $sheet->setCellValue('D' . $row, $item['name']);
            $sheet->setCellValue('E' . $row, $item['department']);
            $sheet->setCellValue('F' . $row, $item['category']);
            $sheet->setCellValue('G' . $row, (float)$item['buy_price']);
            $sheet->setCellValue('H' . $row, $systemStock);
            $sheet->setCellValue('I' . $row, $physicalStock !== null ? $physicalStock : '-');
            $sheet->setCellValue('J' . $row, $variance !== null ? $variance : '-');
            $sheet->setCellValue('K' . $row, $varianceValue !== null ? $varianceValue : '-');

            // Color for variance
            if ($variance !== null && $variance != 0) {
                $color = $variance > 0 ? '92D050' : 'FF6B6B'; // Green for surplus, Red for shortage
                $sheet->getStyle('J' . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($color);
                $sheet->getStyle('K' . $row)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($color);
            }

            $no++;
            $row++;
        }

        // Apply border to data rows
        $dataRange = 'A7:K' . ($row - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Total row
        $row++;
        $sheet->setCellValue('I' . $row, 'TOTAL NOMINAL:');
        $sheet->setCellValue('K' . $row, $totalVarianceValue);
        $sheet->getStyle('I' . $row . ':K' . $row)->getFont()->setBold(true);
        $sheet->getStyle('K' . $row)->getNumberFormat()
            ->setFormatCode('#,##0.00');

        // Summary section
        $row += 2;
        $sheet->setCellValue('A' . $row, 'RINGKASAN:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Item:');
        $sheet->setCellValue('B' . $row, count($items));

        $row++;
        $sheet->setCellValue('A' . $row, 'Sudah Dihitung:');
        $sheet->setCellValue('B' . $row, $countedItems);

        $row++;
        $sheet->setCellValue('A' . $row, 'Belum Dihitung:');
        $sheet->setCellValue('B' . $row, $uncountedItems);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Surplus (Rp):');
        $sheet->setCellValue('B' . $row, $totalSurplus);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('008000');

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Shortage (Rp):');
        $sheet->setCellValue('B' . $row, $totalShortage);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('FF0000');

        $row++;
        $sheet->setCellValue('A' . $row, 'Selisih Bersih (Rp):');
        $sheet->setCellValue('B' . $row, $totalVarianceValue);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

        // Format number columns
        $sheet->getStyle('G8:G' . ($row - 8))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('H8:K' . ($row - 8))->getNumberFormat()->setFormatCode('#,##0.00');

        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create Excel file
        $filename = 'SO_Report_' . $session['session_code'] . '_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
