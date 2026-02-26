#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(pwd)"

IMAGE="${PIQULE_INFRA_IMAGE:-<< config(docker.image) | default(["ghcr.io/haspadar/piqule-infra:latest"]) | scalar >>}"

docker run --rm \
  -v "$PROJECT_ROOT:/project" \
  -w /project \
  "$IMAGE" \
  "$@"