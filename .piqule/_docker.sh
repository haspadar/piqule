#!/usr/bin/env bash
set -euo pipefail

if ! command -v docker >/dev/null 2>&1; then
  echo "Error: docker is not installed or not in PATH" >&2
  exit 1
fi

PROJECT_ROOT="$(pwd)"

IMAGE="${PIQULE_INFRA_IMAGE:-ghcr.io/haspadar/piqule-infra@sha256:7311388ff9f19a6adb7728f6a2f13f16d5f0e398570b9786232b26159d5a80db}"

docker run --rm \
  --user "$(id -u):$(id -g)" \
  -v "$PROJECT_ROOT:/project" \
  -w /project \
  "$IMAGE" \
  "$@"
