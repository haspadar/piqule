# PHP Quality Laws

[![CI](https://github.com/haspadar/piqule/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/piqule/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/haspadar/piqule/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/piqule)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fpiqule%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/piqule/main)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%209-brightgreen)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/psalm-level%201-brightgreen)](https://psalm.dev)

[![Hits-of-Code](https://hitsofcode.com/github/haspadar/piqule?branch=main)](https://hitsofcode.com/github/haspadar/piqule/view?branch=main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/piqule?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

Quality checks for PHP projects.

Installed via Composer.  
Executed locally, in Git hooks, or in CI.

---

## Installation

Install Piqule as a development dependency:

```bash
composer require --dev haspadar/piqule
```

---

## Synchronization

Synchronize managed configuration files into the project:

```bash
bin/piqule-sync.php
```

---

## Running Quality Checks

### Directly

```bash
vendor/bin/piqule check
vendor/bin/piqule check phpstan
vendor/bin/piqule check phpcs
```

### Via Docker

Build the image:

```bash
docker buildx build -f docker/Dockerfile -t piqule:latest --load .
```

Run checks:

```bash
docker run --rm -it \
  -v "$PWD:/project" \
  -w /project \
  piqule:latest \
  bash docker/bin/check
```

---

## Included Tooling

Piqule Docker image contains:

### PHP Quality Tools

- PHPStan
- Psalm
- PHPUnit
- PHPMD
- PHP Metrics
- PHP_CodeSniffer
- PHP-CS-Fixer
- Infection

### Infrastructure & Configuration Linters

- actionlint
- hadolint
- shellcheck
- markdownlint-cli2
- jsonlint
- yamllint
- typos

---

## Contributing

Fork the repository, apply changes, and open a pull request.

Before submitting, ensure all Piqule checks pass locally or in CI.

---

## License

MIT
