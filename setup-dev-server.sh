#!/bin/bash
# LLM Resayil — ONE-TIME Dev Server Setup
# Run this ONCE on the WHM server to set up llmdev.resayil.io
#
# Prerequisites (do in cPanel first):
#   1. Create subdomain llmdev.resayil.io → document root ~/llmdev.resayil.io/public
#   2. Create database: resayili_llmdev  (same user resayili_llm is fine)
#
# Then run:  bash setup-dev-server.sh

set -e

PHP="/opt/cpanel/ea-php82/root/usr/bin/php"
HOME_DIR="/home/resayili"
DEV_DIR="$HOME_DIR/llmdev.resayil.io"
PROD_DIR="$HOME_DIR/llm.resayil.io"
REPO="https://github.com/soudshoja/LLM-Resayil.git"

echo "========================================"
echo "  LLM Resayil — Dev Server Setup"
echo "========================================"

# Clone repo into dev directory
if [ -d "$DEV_DIR" ]; then
    echo "WARNING: $DEV_DIR already exists. Skipping clone."
else
    echo "[1/5] Cloning repo into $DEV_DIR..."
    git clone -b dev "$REPO" "$DEV_DIR"
fi

cd "$DEV_DIR"
git checkout dev

# Copy .env from production as starting point
echo "[2/5] Creating .env for dev..."
if [ ! -f "$DEV_DIR/.env" ]; then
    cp "$PROD_DIR/.env" "$DEV_DIR/.env"
    echo ""
    echo "  IMPORTANT: Edit $DEV_DIR/.env and change:"
    echo "    APP_URL=https://llmdev.resayil.io"
    echo "    APP_ENV=local"
    echo "    APP_DEBUG=true"
    echo "    DB_DATABASE=resayili_llmdev"
    echo "    MYFATOORAH_BASE_URL=https://apitest.myfatoorah.com  (use test gateway for dev)"
    echo ""
    echo "  Press ENTER when you've updated .env to continue..."
    read -r
else
    echo "  .env already exists, skipping."
fi

# Install composer
echo "[3/5] Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generate app key
echo "[4/5] Generating app key..."
"$PHP" artisan key:generate --force

# Run migrations + seed
echo "[5/5] Running migrations..."
"$PHP" artisan migrate --force

echo ""
echo "========================================"
echo "  Dev server setup complete!"
echo "  Test at: https://llmdev.resayil.io"
echo ""
echo "  Daily deploy workflow:"
echo "    ./deploy.sh dev    → push changes to dev"
echo "    ./deploy.sh prod   → push changes to production"
echo "========================================"
