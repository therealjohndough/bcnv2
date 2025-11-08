#!/usr/bin/env bash
set -euo pipefail

# Minimal packages to install for typical WP theme dev (Ubuntu 24.04)
PKGS=(curl ca-certificates git build-essential unzip)
# add language runtimes if missing
PKGS+=(nodejs npm php-cli php-mbstring php-xml)

echo "Updating apt and installing packages (may ask for sudo)..."
sudo apt update
sudo apt install -y "${PKGS[@]}"

# ensure composer is available
if ! command -v composer >/dev/null 2>&1; then
  echo "Installing composer..."
  EXPECTED_SIGNATURE="$(curl -sSL https://composer.github.io/installer.sig)"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --quiet
  php -r "unlink('composer-setup.php');"
  sudo mv composer.phar /usr/local/bin/composer
fi

# ensure node / npm versions okay (optional)
if command -v node >/dev/null 2>&1; then
  echo "node $(node -v), npm $(npm -v)"
fi

cd /workspaces/bcn-wp-theme

# PHP dependencies
if [ -f composer.json ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --no-progress
fi

# Node dependencies & build
if [ -f package.json ]; then
  echo "Installing npm dependencies..."
  # prefer npm ci if lockfile present
  if [ -f package-lock.json ] || [ -f npm-shrinkwrap.json ]; then
    npm ci
  else
    npm install
  fi

  # try common build scripts
  if npm run | grep -q " build"; then
    echo "Running npm run build..."
    npm run build
  elif npm run | grep -q " production"; then
    echo "Running npm run production..."
    npm run production
  else
    echo "No standard build script found in package.json — skipping build step."
  fi
fi

echo "Build/install finished. If you need to test all branches, run the test-branches.sh script provided earlier."