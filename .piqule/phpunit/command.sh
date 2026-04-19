#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpunit/phpunit.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPUnit config not found: $CONFIG" >&2
  exit 1
fi

SEED="${PHPUNIT_SEED:-}"

BIN="$(.piqule/_composer.sh phpunit)"

ARGS=(-c "$CONFIG" --order-by=random)

if [ -n "$SEED" ]; then
  case "$SEED" in
    (''|*[!0-9]*)
      echo "PHPUNIT_SEED must be a positive integer, got: $SEED" >&2
      exit 1
      ;;
  esac

  if [ "$SEED" -eq 0 ]; then
    echo "PHPUNIT_SEED must be greater than zero" >&2
    exit 1
  fi

  ARGS+=(--random-order-seed="$SEED")
fi

COVERAGE_FILE=".piqule/codecov/coverage.xml"

if php -r 'exit(extension_loaded("xdebug") ? 0 : 1);' 2>/dev/null; then
  mkdir -p "$(dirname "$COVERAGE_FILE")"
  ARGS+=(--coverage-clover="$COVERAGE_FILE")
  XDEBUG_MODE=coverage
else
  XDEBUG_MODE=off
fi

PHP_OPTIONS_STR="-d memory_limit=1G"
PHP_OPTIONS=()
if [ -n "$PHP_OPTIONS_STR" ]; then
  read -ra PHP_OPTIONS <<< "$PHP_OPTIONS_STR"
fi

export XDEBUG_MODE

exec .piqule/_skip_if_empty.sh tests '*Test.php' PHPUnit "PHP tests" -- \
  php "${PHP_OPTIONS[@]+"${PHP_OPTIONS[@]}"}" "$BIN" "${ARGS[@]}"
