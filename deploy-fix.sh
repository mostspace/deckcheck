#!/bin/bash

# Laravel Cache Path Fix Script
# Run this on your production server to fix the cache path error

echo "🔧 Fixing Laravel Cache Path Error..."

# Navigate to Laravel project directory
cd /var/www/html

echo "📁 Creating missing storage directories..."
# Create all required storage directories
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "🔐 Setting proper permissions..."
# Set permissions for storage and cache directories
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Set ownership (adjust user:group as needed for your server)
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

echo "🧹 Clearing all caches..."
# Clear all Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear

echo "🔄 Rebuilding caches..."
# Rebuild optimized caches
php artisan config:cache
php artisan view:cache
php artisan route:cache

echo "🗄️ Checking database cache table..."
# Ensure cache table exists if using database cache
php artisan cache:table 2>/dev/null || echo "Cache table already exists or not using database cache"

echo "🌐 Restarting web server..."
# Restart web server (adjust command based on your setup)
systemctl restart nginx 2>/dev/null || systemctl restart apache2 2>/dev/null || echo "Please restart your web server manually"

echo "✅ Deployment fix completed!"
echo ""
echo "If you're still getting errors, try:"
echo "1. Check your .env file has correct CACHE_STORE setting"
echo "2. Verify database connection if using database cache"
echo "3. Check web server error logs"
