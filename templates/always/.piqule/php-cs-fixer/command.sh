#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/php-cs-fixer/php-cs-fixer.project.php"

if [ ! -f "$CONFIG" ]; then
  echo "PHP CS Fixer config not found: $CONFIG"
  exit 1
fi

if [ ! -d "src" ] || [ -z "$(find src -name '*.php' -print -quit)" ]; then
  echo "No PHP source files found, skipping PHP CS Fixer"
  exit 0
fi

BIN="$(.piqule/_composer.sh php-cs-fixer)"

"$BIN" fix \
  --config="$CONFIG" \
  --dry-run \
  --diff
