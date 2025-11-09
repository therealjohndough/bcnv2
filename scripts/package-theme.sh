#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME_SRC="$ROOT_DIR/bcn-wp-theme-clean"
THEME_SLUG="buffalo-cannabis-network"
DIST_DIR="$ROOT_DIR/dist"
BUILD_DIR="$DIST_DIR/$THEME_SLUG"
ZIP_PATH="$DIST_DIR/${THEME_SLUG}.zip"

rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR"

rsync -a \
  --exclude '.git' \
  --exclude '.DS_Store' \
  --exclude 'node_modules' \
  --exclude 'vendor' \
  "$THEME_SRC"/ "$BUILD_DIR"/

cd "$DIST_DIR"
rm -f "$ZIP_PATH"
zip -rq "${THEME_SLUG}.zip" "$THEME_SLUG"

cd "$ROOT_DIR"
rm -rf "$BUILD_DIR"
echo "Theme package created at $ZIP_PATH"
