#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpmetrics/config.json"
VERIFY=".piqule/phpmetrics/verify.php"

if [ ! -f "$CONFIG" ]; then
  echo "PHPMetrics config not found: $CONFIG"
  exit 1
fi

BIN="$(.piqule/_composer.sh phpmetrics)"

.piqule/_skip_if_empty.sh src '*.php' PHPMetrics -- \
  php -d error_reporting='E_ALL & ~E_DEPRECATED' \
  "$BIN" \
  --config="$CONFIG"

REPORT=".piqule/phpmetrics/phpmetrics.json"
if [ -f "$VERIFY" ] && [ -f "$REPORT" ]; then
  php "$VERIFY"
fi
