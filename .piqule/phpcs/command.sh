#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpcs/phpcs.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPCS config not found: $CONFIG"
  exit 1
fi

if [ ! -d "src" ] || [ -z "$(find src -name '*.php' -maxdepth 3 | head -1)" ]; then
  echo "No PHP source files found, skipping PHPCS"
  exit 0
fi

BIN="$(.piqule/_composer.sh phpcs)"

"$BIN" --standard="$CONFIG"
