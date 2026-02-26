#!/usr/bin/env bash
set -euo pipefail

if [ ! -d ".github/workflows" ]; then
  echo "No GitHub workflows found"
  exit 0
fi

actionlint