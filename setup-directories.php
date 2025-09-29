<?php

/**
 * Laravel Directory Setup Script
 * Ensures all necessary directories exist before composer install
 */

echo "Setting up Laravel directories...\n";

$directories = [
    'bootstrap/cache',
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        if (mkdir($directory, 0755, true)) {
            echo "Created directory: $directory\n";
        } else {
            echo "Failed to create directory: $directory\n";
            exit(1);
        }
    } else {
        echo "Directory already exists: $directory\n";
    }
}

echo "Directory setup completed successfully.\n";
