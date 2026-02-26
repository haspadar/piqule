#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(pwd)"

IMAGE="${PIQULE_INFRA_IMAGE:-piqule-infra:local}"

docker run --rm \
  -v "$PROJECT_ROOT:/project" \
  -w /project \
  "$IMAGE" \
  "$@"