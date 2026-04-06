#!/usr/bin/env bash
set -euo pipefail

PROPS=".piqule/sonar/sonar-project.properties"

if [ ! -f "$PROPS" ]; then
  echo "SonarCloud config not found: $PROPS" >&2
  exit 1
fi

if [ -z "${SONAR_TOKEN:-}" ]; then
  echo -e "\033[33m[TIP] Set SONAR_TOKEN to enable SonarCloud analysis\033[0m"
  echo -e "\033[33m      Get token at: https://sonarcloud.io/account/security\033[0m"
  echo -e "\033[33m      ● export SONAR_TOKEN=<your-token>     (bash/zsh)\033[0m"
  echo -e "\033[33m      ● set -Ux SONAR_TOKEN <your-token>    (fish)\033[0m"
  exit 0
fi

if ! command -v docker >/dev/null 2>&1; then
  echo "Error: docker is not installed or not in PATH" >&2
  exit 1
fi

PROJECT_ROOT="$(pwd)"
IMAGE="${PIQULE_INFRA_IMAGE:-<< config(docker.image) >>}"

docker run --rm \
  --user "$(id -u):$(id -g)" \
  -e SONAR_TOKEN="$SONAR_TOKEN" \
  -e HOME=/tmp \
  -v "$PROJECT_ROOT:/project" \
  -w /project \
  "$IMAGE" \
  sonar-scanner -Dproject.settings="$PROPS" -Dsonar.qualitygate.wait=true
