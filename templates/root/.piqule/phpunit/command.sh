#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpunit/phpunit.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPUnit config not found: $CONFIG"
  exit 1
fi

SEED=${PHPUNIT_SEED:-random}

BIN="$(.piqule/_composer.sh phpunit)"

"$BIN" \
  -c "$CONFIG" \
  --order-by=random \
  --random-order-seed="$SEED"
