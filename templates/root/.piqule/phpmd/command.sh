#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpmd/phpmd.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPMD config not found: $CONFIG"
  exit 1
fi

BIN="$(.piqule/_composer.sh phpmd)"

"$BIN" \
<< config(phpmd.paths)
   |default(["src"])
   |join(" ")
>> \
text \
"$CONFIG"