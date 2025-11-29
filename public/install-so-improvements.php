<?php

/**
 * Database Installer untuk Stock Opname Improvements
 * 
 * Cara pakai:
 * 1. Buka browser: http://localhost:8080/install-so-improvements.php
 * 2. Click tombol "Install"
 * 3. Done!
 */

// Load CodeIgniter - Fix path (public folder)
define('ROOTPATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
require_once ROOTPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();

$success = [];
$errors = [];

if (isset($_POST['install'])) {
    // Check if columns already exist
    $query1 = $db->query("SHOW COLUMNS FROM stock_opname_sessions LIKE 'is_baseline_frozen'");
    if ($query1->getNumRows() == 0) {
        try {
            $db->query("ALTER TABLE stock_opname_sessions 
                ADD COLUMN baseline_freeze_date DATE NULL COMMENT 'Date when baseline is frozen' AFTER session_date,
                ADD COLUMN is_baseline_frozen TINYINT(1) DEFAULT 0 COMMENT 'Whether baseline is frozen' AFTER baseline_freeze_date");
            $success[] = "✅ Added freeze baseline columns to stock_opname_sessions";
        } catch (Exception $e) {
            $errors[] = "❌ Error adding columns to stock_opname_sessions: " . $e->getMessage();
        }
    } else {
        $success[] = "ℹ️ Freeze baseline columns already exist in stock_opname_sessions";
    }

    $query2 = $db->query("SHOW COLUMNS FROM stock_opname_items LIKE 'mutation_at_count'");
    if ($query2->getNumRows() == 0) {
        try {
            $db->query("ALTER TABLE stock_opname_items
                ADD COLUMN mutation_at_count DECIMAL(10,2) DEFAULT 0 COMMENT 'Mutation from session_date to counted_date' AFTER difference,
                ADD COLUMN mutation_after_count DECIMAL(10,2) DEFAULT 0 COMMENT 'Mutation from counted_date to now' AFTER mutation_at_count");
            $success[] = "✅ Added mutation tracking columns to stock_opname_items";
        } catch (Exception $e) {
            $errors[] = "❌ Error adding columns to stock_opname_items: " . $e->getMessage();
        }
    } else {
        $success[] = "ℹ️ Mutation tracking columns already exist in stock_opname_items";
    }

    if (empty($errors)) {
        $success[] = "🎉 Installation completed successfully!";
        $success[] = "👉 You can now delete this file (install-so-improvements.php) for security.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Opname Improvements - Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-database mr-2 text-blue-600"></i>
                Stock Opname Improvements - Database Installer
            </h1>
            <p class="text-gray-600 mb-6">
                This installer will add new columns to your database for the Stock Opname improvements.
            </p>

            <?php if (!empty($success)): ?>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-green-800 mb-2">Success Messages:</h3>
                    <ul class="list-disc list-inside text-green-700">
                        <?php foreach ($success as $msg): ?>
                            <li><?= $msg ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-red-800 mb-2">Errors:</h3>
                    <ul class="list-disc list-inside text-red-700">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (empty($_POST['install']) || !empty($errors)): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        What will be installed?
                    </h3>
                    <div class="text-blue-700 text-sm space-y-2">
                        <p><strong>Table: stock_opname_sessions</strong></p>
                        <ul class="list-disc list-inside ml-4">
                            <li>baseline_freeze_date (DATE) - Date when baseline is frozen</li>
                            <li>is_baseline_frozen (BOOLEAN) - Whether baseline is frozen or real-time</li>
                        </ul>
                        <p class="mt-3"><strong>Table: stock_opname_items</strong></p>
                        <ul class="list-disc list-inside ml-4">
                            <li>mutation_at_count (DECIMAL) - Mutation from session_date to counted_date</li>
                            <li>mutation_after_count (DECIMAL) - Mutation from counted_date to now/close</li>
                        </ul>
                    </div>
                </div>

                <form method="POST" onsubmit="return confirm('Are you sure you want to install these database changes?')">
                    <button type="submit" name="install" value="1"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-download mr-2"></i>
                        Install Database Changes
                    </button>
                </form>
            <?php else: ?>
                <div class="mt-6 flex gap-4">
                    <a href="/stock-opname"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Go to Stock Opname
                    </a>
                    <a href="/"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        <i class="fas fa-home mr-2"></i>
                        Go to Home
                    </a>
                </div>

                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Security Note:</strong> Please delete this file (install-so-improvements.php) after successful installation.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-book mr-2 text-blue-600"></i>
                Quick Guide
            </h2>
            <div class="space-y-3 text-gray-700 text-sm">
                <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 mt-0.5">1</span>
                    <p><strong>Baseline Control:</strong> You can now freeze/unfreeze baseline to prevent it from updating with new transactions.</p>
                </div>
                <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 mt-0.5">2</span>
                    <p><strong>Mutation Tracking:</strong> See exactly how much stock changed due to transactions (purchases - sales).</p>
                </div>
                <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 mt-0.5">3</span>
                    <p><strong>Transaction Summary:</strong> Daily transaction summary shows you all activity during SO session.</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    For complete documentation, see: <code class="bg-gray-100 px-2 py-1 rounded">QUICK_START.md</code>
                </p>
            </div>
        </div>
    </div>
</body>

</html>