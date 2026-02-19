#!/usr/bin/env bash
set -euo pipefail

# Tracked files only (faster + stable); output is NUL-separated.
git ls-files -z -- \
<< config(shellcheck.ignore_dirs)
   |default(["vendor","node_modules",".git","coverage","build","var"])
   |format("  ':!:%s/**' \")
   |join("\n")
>>
| while IFS= read -r -d '' file; do
    # Only executable files
    if [ ! -x "$file" ]; then
      continue
    fi

    # Only shell scripts by shebang
    if head -n1 "$file" | grep -qE '^#!/.*\b(bash|sh)\b'; then
      printf '%s\0' "$file"
    fi
  done