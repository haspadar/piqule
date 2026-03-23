#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/sonar/sonar-project.properties"

if [ ! -f "$CONFIG" ]; then
  echo "SonarQube config not found: $CONFIG"
  exit 1
fi

sonar-scanner -Dproject.settings="$CONFIG"
