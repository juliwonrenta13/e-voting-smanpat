#!/bin/bash
set -e

echo "=== Running post-deploy setup ==="

# Create storage symlink
php artisan storage:link || true

# Run database migrations
php artisan migrate --force

echo "=== Deploy setup complete ==="
