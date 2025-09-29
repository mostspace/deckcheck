@echo off
REM Laravel Build Script for Windows
REM This script ensures proper directory structure for Laravel deployment

echo Setting up Laravel application structure...

REM Create necessary directories if they don't exist
if not exist "bootstrap\cache" mkdir "bootstrap\cache"
if not exist "storage\app\public" mkdir "storage\app\public"
if not exist "storage\framework\cache" mkdir "storage\framework\cache"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\logs" mkdir "storage\logs"

echo Directory structure created successfully.

REM Run composer install
echo Installing PHP dependencies...
composer install --no-dev --optimize-autoloader

REM Run npm build
echo Building frontend assets...
npm ci
npm run build

echo Build completed successfully.
