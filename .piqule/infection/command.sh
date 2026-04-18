#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/infection/infection.json5"

if [ ! -f "$CONFIG" ]; then
  echo "Infection config not found: $CONFIG"
  exit 1
fi

. .piqule/_skip_if_empty.sh src '*.php' Infection

INFECTION_BIN="$(.piqule/_composer.sh infection)"

XDEBUG_MODE=coverage \
  "$INFECTION_BIN" \
  --configuration="$CONFIG" \
  --threads=max
