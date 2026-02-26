#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/shellcheck/.shellcheckrc"

if [ ! -f "$CONFIG" ]; then
  echo "ShellCheck config not found: $CONFIG"
  exit 1
fi

# ============================================================
# Collect shell scripts (portable, NUL-safe, no git)
# ============================================================

FILES=()
while IFS= read -r -d '' file; do
  # Only executable files
  if [ ! -x "$file" ]; then
    continue
  fi

  # Only shell scripts by shebang
  if head -n1 "$file" | grep -qE '^#!/.*\b(bash|sh)\b'; then
    FILES+=("$file")
  fi
done < <(
  find . \
    -type f \
    ! -path "./vendor/*" \
    ! -path "./.git/*" \
    ! -path "./templates/*" \
    -print0
)

if [ ${#FILES[@]} -eq 0 ]; then
  echo "No shell scripts found"
  exit 0
fi

# ============================================================
# Run ShellCheck (docker)
# ============================================================

.piqule/_docker.sh shellcheck \
  --rcfile "$CONFIG" \
  "${FILES[@]}"