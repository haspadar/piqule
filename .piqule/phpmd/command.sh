#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpmd/phpmd.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPMD config not found: $CONFIG"
  exit 1
fi

. .piqule/_skip_if_empty.sh src '*.php' PHPMD

BIN="$(.piqule/_composer.sh phpmd)"

"$BIN" \
src \
text \
"$CONFIG"
