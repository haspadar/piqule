#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpmd/phpmd.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPMD config not found: $CONFIG"
  exit 1
fi

if [ ! -d "src" ] || [ -z "$(find src -name '*.php' -print -quit)" ]; then
  echo "No PHP source files found, skipping PHPMD"
  exit 0
fi

BIN="$(.piqule/_composer.sh phpmd)"

"$BIN" \
src \
text \
"$CONFIG"
