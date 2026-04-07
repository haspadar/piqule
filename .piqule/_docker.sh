#!/usr/bin/env bash
set -euo pipefail

if ! command -v docker >/dev/null 2>&1; then
  echo "Error: docker is not installed or not in PATH" >&2
  exit 1
fi

PROJECT_ROOT="$(pwd)"

IMAGE="${PIQULE_INFRA_IMAGE:-ghcr.io/haspadar/piqule-infra@sha256:9d1f25143571a426ded0f453a442b31fe07137bb6e66d7054ce6601615d645ce}"

docker run --rm \
  --user "$(id -u):$(id -g)" \
  -v "$PROJECT_ROOT:/project" \
  -w /project \
  "$IMAGE" \
  "$@"
