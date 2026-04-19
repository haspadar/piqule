#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/infection/infection.json5"

if [ ! -f "$CONFIG" ]; then
  echo "Infection config not found: $CONFIG"
  exit 1
fi

INFECTION_BIN="$(.piqule/_composer.sh infection)"

PHP_OPTIONS_STR="<< config(infection.php_options)|join(' ') >>"
PHP_OPTIONS=()
if [ -n "$PHP_OPTIONS_STR" ]; then
  read -ra PHP_OPTIONS <<< "$PHP_OPTIONS_STR"
fi

exec .piqule/_skip_if_empty.sh src '*.php' Infection -- \
  env XDEBUG_MODE=coverage \
  php "${PHP_OPTIONS[@]+"${PHP_OPTIONS[@]}"}" \
  "$INFECTION_BIN" \
  --configuration="$CONFIG" \
  --threads=max
