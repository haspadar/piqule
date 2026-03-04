#!/usr/bin/env bash
set -euo pipefail

if [ ! -f "renovate.json" ]; then
  echo "No renovate.json found, skipping"
  exit 0
fi

npx --yes --package renovate -- renovate-config-validator renovate.json
