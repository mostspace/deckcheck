#!/bin/bash

# Laravel Build Script
# This script ensures proper directory structure and permissions for Laravel deployment

echo "Setting up Laravel application structure..."

# Create necessary directories if they don't exist
mkdir -p bootstrap/cache
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set proper permissions (adjust as needed for your deployment environment)
chmod -R 775 bootstrap/cache
chmod -R 775 storage

echo "Directory structure created successfully."

# Run composer install
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Run npm build
echo "Building frontend assets..."
npm ci
npm run build

echo "Build completed successfully."
