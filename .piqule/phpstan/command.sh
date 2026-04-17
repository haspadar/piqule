#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpstan/phpstan.neon"

if [ ! -f "$CONFIG" ]; then
  echo "PHPStan config not found: $CONFIG"
  exit 1
fi

if [ ! -d "src" ] || [ -z "$(find src -name '*.php' -maxdepth 3 | head -1)" ]; then
  echo "No PHP source files found, skipping PHPStan"
  exit 0
fi

BIN="$(.piqule/_composer.sh phpstan)"

"$BIN" analyse \
  -c "$CONFIG" \
  --memory-limit=1G
