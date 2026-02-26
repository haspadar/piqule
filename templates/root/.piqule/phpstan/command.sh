#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpstan/phpstan.neon"

if [ ! -f "$CONFIG" ]; then
  echo "PHPStan config not found: $CONFIG"
  exit 1
fi

BIN="$(.piqule/_composer.sh phpstan)"

"$BIN" analyse \
  -c "$CONFIG" \
  --memory-limit=<< config(phpstan.memory)|default(["1G"])|scalar >>
