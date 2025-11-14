<?php

/**
 * Example Script: Update Campaign with Multiple Images
 * 
 * This script shows how to add multiple images to a campaign
 * Run this from command line: php update_campaign_images.php
 */

require 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Get database instance
$db = \Config\Database::connect();

// Example 1: Add multiple images to campaign ID 1
$campaignId = 1;
$additionalImages = [
    'campaign-1-photo-2.jpg',
    'campaign-1-photo-3.jpg',
    'campaign-1-photo-4.jpg',
];

$db->table('campaigns')
    ->where('id', $campaignId)
    ->update([
        'images' => json_encode($additionalImages),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

echo "✓ Campaign ID {$campaignId} updated with " . count($additionalImages) . " additional images\n";

// Example 2: Add images to multiple campaigns
$campaignsData = [
    1 => ['photo1.jpg', 'photo2.jpg', 'photo3.jpg'],
    2 => ['img1.jpg', 'img2.jpg', 'img3.jpg', 'img4.jpg'],
    3 => ['pic1.jpg', 'pic2.jpg'],
];

foreach ($campaignsData as $id => $images) {
    $db->table('campaigns')
        ->where('id', $id)
        ->update([
            'images' => json_encode($images),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    echo "✓ Campaign ID {$id} updated with " . count($images) . " additional images\n";
}

echo "\n=== Done! ===\n";
echo "Now you can view the campaigns with multiple images in the gallery.\n";
echo "Visit: http://localhost:8080/campaign/{slug}\n";
