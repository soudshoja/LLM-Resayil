#!/bin/bash
# LLM Resayil Portal Deployment Script
# Usage: ./deploy.sh [environment]

set -e

ENVIRONMENT="${1:-production}"
DEPLOY_DIR="/home/resayili/llm.resayil.io"

echo "=== LLM Resayil Portal Deployment ==="
echo "Environment: $ENVIRONMENT"
echo "Deploy directory: $DEPLOY_DIR"

# Pull latest changes
echo "Pulling latest changes..."
cd "$DEPLOY_DIR"
git pull origin main

# Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run migrations
echo "Running database migrations..."
php artisan migrate:fresh --force

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Generate optimized autoload
echo "Generating optimized autoload..."
composer dump-autoload --optimize

echo "=== Deployment Complete ==="
echo "Please ensure:"
echo "1. MySQL database 'resayili_llm_resayil' exists"
echo "2. .env file is configured with real credentials"
echo "3. MyFatoorah webhook URL is set to: https://llm.resayil.io/webhooks/payment"
