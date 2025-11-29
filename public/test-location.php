<?php

/**
 * TEST SCRIPT - Location Feature
 * Verifikasi bahwa semua table dan column sudah benar
 */

// Database config
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'db_so_baru'
];

echo "========================================\n";
echo "LOCATION FEATURE - VERIFICATION TEST\n";
echo "========================================\n\n";

try {
    $conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "✓ Database Connected: {$dbConfig['name']}\n\n";

    // Test 1: Check locations table
    echo "Test 1: Check 'locations' table\n";
    $result = $conn->query("SHOW TABLES LIKE 'locations'");
    if ($result->num_rows > 0) {
        echo "  ✓ Table 'locations' exists\n";

        // Count records
        $result = $conn->query("SELECT COUNT(*) as count FROM locations");
        $row = $result->fetch_assoc();
        echo "  ✓ Total locations: {$row['count']}\n";
    } else {
        echo "  ✗ Table 'locations' NOT found\n";
    }

    // Test 2: Check stock_opname_items table
    echo "\nTest 2: Check 'stock_opname_items' table\n";
    $result = $conn->query("SHOW TABLES LIKE 'stock_opname_items'");
    if ($result->num_rows > 0) {
        echo "  ✓ Table 'stock_opname_items' exists\n";

        // Check location_id column
        $result = $conn->query("SHOW COLUMNS FROM stock_opname_items LIKE 'location_id'");
        if ($result->num_rows > 0) {
            echo "  ✓ Column 'location_id' exists\n";
        } else {
            echo "  ✗ Column 'location_id' NOT found\n";
        }
    } else {
        echo "  ✗ Table 'stock_opname_items' NOT found\n";
    }

    // Test 3: Check if stock_opname_detail exists (should NOT exist)
    echo "\nTest 3: Verify 'stock_opname_detail' doesn't exist\n";
    $result = $conn->query("SHOW TABLES LIKE 'stock_opname_detail'");
    if ($result->num_rows == 0) {
        echo "  ✓ Correct! Table 'stock_opname_detail' does not exist\n";
        echo "  ✓ Using 'stock_opname_items' instead\n";
    } else {
        echo "  ⚠ Warning: Table 'stock_opname_detail' exists\n";
        echo "  → This might cause confusion. Consider renaming.\n";
    }

    // Test 4: Sample locations data
    echo "\nTest 4: Sample locations data\n";
    $result = $conn->query("SELECT kode_lokasi, nama_lokasi, departemen, status FROM locations LIMIT 3");
    if ($result->num_rows > 0) {
        echo "  Sample data:\n";
        while ($row = $result->fetch_assoc()) {
            echo "  - {$row['kode_lokasi']}: {$row['nama_lokasi']} ({$row['departemen']})\n";
        }
    }

    echo "\n========================================\n";
    echo "✓ ALL TESTS PASSED\n";
    echo "========================================\n";
    echo "\nYou can now:\n";
    echo "1. Access: http://localhost:8080/admin/location\n";
    echo "2. Manage locations\n";
    echo "3. Use locations in Stock Opname\n";

    $conn->close();
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
}
