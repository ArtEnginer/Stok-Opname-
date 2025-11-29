<?php

/**
 * INSTALLER MASTER LOCATION
 * 
 * File ini akan menginstall tabel locations dan menambah kolom location_id
 * ke tabel stock_opname_items secara AMAN tanpa mempengaruhi tabel lain
 * 
 * CARA PAKAI:
 * 1. Akses file ini via browser: http://localhost/STOCK%20OPNAME/public/install-location.php
 * 2. Atau jalankan via terminal: php public/install-location.php
 */

// Database Configuration (sesuaikan dengan setting Anda)
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'db_so_baru'  // Database Stock Opname yang aktif
];

// Check if running from CLI or browser
$isCLI = php_sapi_name() === 'cli';

function output($message, $isCLI)
{
    if ($isCLI) {
        echo $message . "\n";
    } else {
        echo nl2br(htmlspecialchars($message)) . "<br>";
    }
}

if (!$isCLI) {
    echo "<html><head><title>Location Installer</title>";
    echo "<style>body{font-family:monospace;background:#1e1e1e;color:#ddd;padding:20px;}";
    echo ".success{color:#0f0;} .error{color:#f00;} .info{color:#ff0;}</style>";
    echo "</head><body>";
}

output("====================================", $isCLI);
output("INSTALLER MASTER LOCATION", $isCLI);
output("====================================", $isCLI);
output("", $isCLI);

try {
    // Connect to database
    $conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
    output("✓ Connected to database: " . $dbConfig['name'], $isCLI);

    output("✓ Connected to database: " . $dbConfig['name'], $isCLI);
    output("", $isCLI);

    // STEP 1: Check if locations table exists
    output("STEP 1: Checking if 'locations' table exists...", $isCLI);

    $result = $conn->query("SHOW TABLES LIKE 'locations'");
    $tableExists = $result->num_rows > 0;

    if ($tableExists) {
        output("✓ Table 'locations' already exists. Skipping creation.", $isCLI);
    } else {
        output("✗ Table 'locations' does not exist. Creating...", $isCLI);

        // Create locations table
        $sql = "CREATE TABLE `locations` (
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `kode_lokasi` varchar(50) NOT NULL,
            `nama_lokasi` varchar(255) NOT NULL,
            `departemen` varchar(100) DEFAULT NULL,
            `keterangan` text DEFAULT NULL,
            `status` enum('aktif','tidak_aktif') NOT NULL DEFAULT 'aktif',
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            `deleted_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `kode_lokasi` (`kode_lokasi`),
            KEY `departemen` (`departemen`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        if ($conn->query($sql)) {
            output("✓ Table 'locations' created successfully!", $isCLI);
        } else {
            throw new Exception("Error creating table: " . $conn->error);
        }
    }

    // STEP 2: Check if location_id column exists in stock_opname_items
    output("", $isCLI);
    output("STEP 2: Checking if 'location_id' column exists in 'stock_opname_items'...", $isCLI);

    $result = $conn->query("SHOW TABLES LIKE 'stock_opname_items'");
    if ($result->num_rows == 0) {
        output("✗ Table 'stock_opname_items' does not exist. Please create stock opname tables first.", $isCLI);
        $conn->close();
        exit;
    }

    // Check if column exists
    $result = $conn->query("SHOW COLUMNS FROM `stock_opname_items` LIKE 'location_id'");
    $columnExists = $result->num_rows > 0;

    if ($columnExists) {
        output("✓ Column 'location_id' already exists in 'stock_opname_items'. Skipping.", $isCLI);
    } else {
        output("✗ Column 'location_id' does not exist. Adding...", $isCLI);

        $sql = "ALTER TABLE `stock_opname_items` 
                ADD COLUMN `location_id` int(11) UNSIGNED DEFAULT NULL AFTER `product_id`,
                ADD KEY `location_id` (`location_id`)";

        if ($conn->query($sql)) {
            output("✓ Column 'location_id' added successfully to 'stock_opname_items'!", $isCLI);
        } else {
            throw new Exception("Error adding column: " . $conn->error);
        }
    }

    // STEP 3: Insert sample data (optional)
    output("", $isCLI);
    output("STEP 3: Checking sample data...", $isCLI);

    $result = $conn->query("SELECT COUNT(*) as count FROM `locations`");
    $row = $result->fetch_assoc();
    $count = $row['count'];

    if ($count > 0) {
        output("✓ Sample data already exists ({$count} locations found). Skipping.", $isCLI);
    } else {
        output("✗ No data found. Inserting sample locations...", $isCLI);

        $sampleData = [
            ['RAK-A-01', 'Rak A Lantai 1', 'Warehouse', 'Rak bagian kiri gudang utama'],
            ['RAK-A-02', 'Rak A Lantai 2', 'Warehouse', 'Rak bagian kiri gudang utama tingkat 2'],
            ['RAK-B-01', 'Rak B Lantai 1', 'Warehouse', 'Rak bagian tengah gudang utama'],
            ['RAK-B-02', 'Rak B Lantai 2', 'Warehouse', 'Rak bagian tengah gudang utama tingkat 2'],
            ['RAK-C-01', 'Rak C Lantai 1', 'Warehouse', 'Rak bagian kanan gudang utama'],
            ['SHOW-01', 'Display Showroom 1', 'Showroom', 'Display area bagian depan'],
            ['SHOW-02', 'Display Showroom 2', 'Showroom', 'Display area bagian belakang'],
            ['STORE-01', 'Toko Area 1', 'Store', 'Area penjualan bagian depan'],
        ];

        $inserted = 0;
        foreach ($sampleData as $data) {
            $sql = "INSERT INTO `locations` (`kode_lokasi`, `nama_lokasi`, `departemen`, `keterangan`, `status`, `created_at`, `updated_at`) 
                    VALUES (?, ?, ?, ?, 'aktif', NOW(), NOW())";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $data[0], $data[1], $data[2], $data[3]);

            if ($stmt->execute()) {
                $inserted++;
            }
            $stmt->close();
        }

        output("✓ Sample data inserted successfully! ({$inserted} locations)", $isCLI);
    }

    output("", $isCLI);
    output("====================================", $isCLI);
    output("✓ INSTALLATION COMPLETED SUCCESSFULLY!", $isCLI);
    output("====================================", $isCLI);
    output("", $isCLI);
    output("You can now access Master Location at:", $isCLI);
    output("URL: http://localhost/STOCK%20OPNAME/admin/location", $isCLI);
    output("", $isCLI);
    output("Next steps:", $isCLI);
    output("1. Access /admin/location to manage locations", $isCLI);
    output("2. Add your own location data", $isCLI);
    output("3. Use locations when doing Stock Opname", $isCLI);

    $conn->close();

    $conn->close();
} catch (Exception $e) {
    output("", $isCLI);
    output("====================================", $isCLI);
    output("✗ ERROR: " . $e->getMessage(), $isCLI);
    output("====================================", $isCLI);
    output("", $isCLI);
    output("Please check:", $isCLI);
    output("1. Database connection settings in this file", $isCLI);
    output("2. Database user has permission to CREATE/ALTER tables", $isCLI);
    output("3. Error message above for more details", $isCLI);

    if (!$isCLI) {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

if (!$isCLI) {
    echo "</body></html>";
}
