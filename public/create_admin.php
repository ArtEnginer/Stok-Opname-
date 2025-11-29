<?php

/**
 * Script to create admin user for Stock Opname System
 * Run this script once to create the admin user
 */

// Check PHP version
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    die("PHP version must be {$minPhpVersion} or higher. Current: " . PHP_VERSION);
}

// Path to the front controller
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Load paths config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Load the framework bootstrap file
require $paths->systemDirectory . '/Boot.php';

// Boot the application in CLI mode
$app = CodeIgniter\Boot::bootSpark($paths);

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

echo "=== Stock Opname System - Create Admin User ===\n\n";

$users = new UserModel();

// Check if admin already exists
$existingAdmin = $users->where('username', 'admin')->first();
if ($existingAdmin) {
    echo "Admin user already exists!\n";
    echo "Username: admin\n";
    echo "User ID: " . $existingAdmin->id . "\n";

    // Check groups
    $groups = $existingAdmin->getGroups();
    echo "Groups: " . implode(', ', $groups) . "\n";

    // Reset password
    echo "\nResetting password to 'Admin123!'...\n";
    $existingAdmin->setPassword('Admin123!');
    $users->save($existingAdmin);
    echo "Password reset successful!\n";
    exit;
}

// Create new admin user
echo "Creating new admin user...\n";

$user = new User([
    'username' => 'admin',
    'active'   => 1,
]);

try {
    // Insert user
    $users->insert($user);
    $userId = $users->getInsertID();
    echo "User created with ID: $userId\n";

    // Retrieve the user
    $user = $users->findById($userId);

    // Set email and password
    $user->setEmail('admin@stockopname.com');
    $user->setPassword('Admin123!');

    // Save identity
    $users->save($user);

    // Add to admin group
    $user->addGroup('admin');

    echo "\n=== Admin User Created Successfully ===\n";
    echo "Email: admin@stockopname.com\n";
    echo "Password: Admin123!\n";
    echo "Group: admin\n";
} catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

// Also create a regular user
echo "\n\nCreating regular user...\n";

$existingUser = $users->where('username', 'user')->first();
if ($existingUser) {
    echo "Regular user already exists!\n";
    echo "Username: user\n";

    // Reset password
    echo "Resetting password to 'User123!'...\n";
    $existingUser->setPassword('User123!');
    $users->save($existingUser);
    echo "Password reset successful!\n";
} else {
    try {
        $regularUser = new User([
            'username' => 'user',
            'active'   => 1,
        ]);

        $users->insert($regularUser);
        $userId = $users->getInsertID();
        echo "User created with ID: $userId\n";

        // Retrieve the user
        $regularUser = $users->findById($userId);

        // Set email and password
        $regularUser->setEmail('user@stockopname.com');
        $regularUser->setPassword('User123!');

        // Save identity
        $users->save($regularUser);

        // Add to user group
        $regularUser->addGroup('user');

        echo "\n=== Regular User Created Successfully ===\n";
        echo "Email: user@stockopname.com\n";
        echo "Password: User123!\n";
        echo "Group: user\n";
    } catch (Exception $e) {
        echo "Error creating regular user: " . $e->getMessage() . "\n";
    }
}

echo "\n\nDone! You can now login at /login\n";
