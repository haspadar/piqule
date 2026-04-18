#!/usr/bin/env bash
set -euo pipefail

DIR="${1:-}"
GLOB="${2:-}"
TOOL="${3:-}"
KIND="${4:-PHP source files}"

if [ -z "$DIR" ] || [ -z "$GLOB" ] || [ -z "$TOOL" ]; then
  echo "Usage: .piqule/_skip_if_empty.sh <dir> <glob> <tool-name> [<kind>]" >&2
  exit 2
fi

if [ ! -d "$DIR" ] || [ -z "$(find "$DIR" -name "$GLOB" -print -quit)" ]; then
  echo "No $KIND found, skipping $TOOL"
  exit 1
fi

exit 0
