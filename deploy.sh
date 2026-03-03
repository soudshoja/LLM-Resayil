#!/bin/bash
# LLM Resayil — Deployment Script
# Usage:
#   ./deploy.sh dev        → deploys dev branch to llmdev.resayil.io
#   ./deploy.sh prod       → deploys main branch to llm.resayil.io
#   ./deploy.sh            → defaults to prod

set -e

ENVIRONMENT="${1:-prod}"

if [ "$ENVIRONMENT" = "dev" ]; then
    DEPLOY_DIR="/home/resayili/llmdev.resayil.io"
    GIT_BRANCH="dev"
    APP_URL="https://llmdev.resayil.io"
elif [ "$ENVIRONMENT" = "prod" ]; then
    DEPLOY_DIR="/home/resayili/llm.resayil.io"
    GIT_BRANCH="main"
    APP_URL="https://llm.resayil.io"
else
    echo "ERROR: Unknown environment '$ENVIRONMENT'. Use 'dev' or 'prod'."
    exit 1
fi

PHP="/opt/cpanel/ea-php82/root/usr/bin/php"

echo "========================================"
echo "  LLM Resayil Deployment"
echo "  Environment : $ENVIRONMENT"
echo "  Branch      : $GIT_BRANCH"
echo "  Directory   : $DEPLOY_DIR"
echo "  URL         : $APP_URL"
echo "========================================"

cd "$DEPLOY_DIR"

# Stash any local .env changes so git pull doesn't fail
echo "[1/6] Stashing local changes..."
git stash

# Pull the correct branch
echo "[2/6] Pulling $GIT_BRANCH..."
git fetch origin
git checkout "$GIT_BRANCH"
git pull origin "$GIT_BRANCH"

# Restore local .env (always kept outside git)
echo "[3/6] Restoring local .env..."
git stash pop 2>/dev/null || true

# Install/update composer dependencies
echo "[4/6] Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Run only new migrations (never fresh on either env)
echo "[5/6] Running pending migrations..."
"$PHP" artisan migrate --force

# Clear caches
echo "[6/6] Clearing caches..."
"$PHP" artisan config:clear
"$PHP" artisan route:clear
"$PHP" artisan view:clear
"$PHP" artisan cache:clear

echo ""
echo "========================================"
echo "  Deployment complete: $APP_URL"
echo "========================================"
