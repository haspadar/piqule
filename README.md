# Piqule

[![CI](https://github.com/haspadar/piqule/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/piqule/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/haspadar/piqule/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/piqule)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fpiqule%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/piqule/main)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%209-brightgreen)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/psalm-level%201-brightgreen)](https://psalm.dev)

[![Hits-of-Code](https://hitsofcode.com/github/haspadar/piqule?branch=main)](https://hitsofcode.com/github/haspadar/piqule/view?branch=main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/piqule?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

## Piqule (PHP Quality Laws)

**Piqule** is a managed quality stack for PHP projects.

It provides a curated set of static analysis tools, linters, and CI workflows,
together with a synchronization mechanism that installs and updates
project-level configuration files in a predictable and repeatable way.

Piqule acts as a **single source of truth** for PHP project quality tooling
and helps avoid configuration drift across repositories.

---

## Tooling overview

| Tool            | Purpose               | What Piqule provides                                |
|-----------------|-----------------------|-----------------------------------------------------|
| PHPStan         | Static analysis       | Strict ruleset and high analysis level              |
| Psalm           | Static analysis       | Complementary checks and early error detection      |
| PHP-CS-Fixer    | Code style            | Deterministic formatting rules                      |
| AST Metrics     | Structural metrics    | Architecture, coupling, complexity, maintainability |
| PHPMD           | Numeric metrics       | Size and complexity thresholds (methods, classes)   |
| PMD (CPD)       | Duplication detection | Copy-paste / duplicated code detection              | 
| Infection       | Mutation testing      | Test quality validation                             |
| Markdownlint    | Markdown linting      | Consistent documentation style                      |
| Hadolint        | Dockerfile linting    | Secure and reproducible Dockerfiles                 |
| Actionlint      | CI linting            | GitHub Actions correctness                          |
| Yamllint        | YAML linting          | Configuration consistency                           |
| Typos           | Spell checking        | Low-noise typo detection                            |
| Renovate        | Dependency automation | Managed dependency update rules                     |
| PR Size Checker | Process guard         | Pull request reviewability enforcement              |

---

## Installation

Add Piqule as a development dependency:

```bash
composer require --dev haspadar/piqule
```

---

## Configuration synchronization

Piqule manages project configuration files via an explicit synchronization step:

```bash
bin/piqule.php sync
```

During synchronization:

- canonical configurations are installed into the project workspace
- files are written to fixed locations:
    - Renovate configuration is placed in the project root
    - tool configurations are placed in `.piqule/`
    - CI workflows are placed in `.github/workflows/`
- **existing files are overwritten**

Piqule does **not** merge configuration files automatically.
If local changes are overwritten, merging must be done manually.

### Dry run mode

To preview changes without modifying files, run:

```bash
bin/piqule.php sync --dry-run
```

This mode shows what files would be created, updated, or overwritten,
allowing verification before applying changes.

---

## PHP tooling (copy-paste)

Piqule synchronizes configuration files, but PHP developer tools are installed
**per-project** via Composer.

This keeps tool versions under project control and avoids hidden dependencies
inside the Docker image.

### 1) Install required Composer tools

Copy and run:

```bash
composer require --dev \
  friendsofphp/php-cs-fixer \
  phpunit/phpunit \
  phpstan/phpstan \
  vimeo/psalm \
  phpmd/phpmd \
  infection/infection
```

### 2) Add Composer scripts

Copy this section into your `composer.json`:

```json
{
  "scripts": {
    "format": "php-cs-fixer fix --config=.piqule/php-cs-fixer/php-cs-fixer.project.php --cache-file=.piqule/php-cs-fixer/.php-cs-fixer.cache",
    "format-check": "php-cs-fixer fix --config=.piqule/php-cs-fixer/php-cs-fixer.project.php --cache-file=.piqule/php-cs-fixer/.php-cs-fixer.cache --dry-run --diff",

    "phpstan": "phpstan analyse -c .piqule/phpstan/phpstan.neon",
    "psalm": "psalm --config=.piqule/psalm/psalm.xml",
    "phpmd": "phpmd src text .piqule/phpmd/phpmd.xml",
    "ast-metrics": "ast-metrics lint --config .piqule/ast-metrics/ast-metrics.yaml",
    "cpd": "pmd cpd --language php --minimum-tokens 100 --dir src",

    "test": "phpunit -c .piqule/phpunit/phpunit.xml --order-by=random",
    "test-coverage": "XDEBUG_MODE=coverage php -d memory_limit=512M phpunit -c .piqule/phpunit/phpunit.xml --path-coverage --coverage-clover=.piqule/codecov/coverage.xml",
    "infection": "XDEBUG_MODE=coverage php -d memory_limit=1G infection --configuration=.piqule/infection/infection.json5 --threads=max",

    "fix": [
      "@format"
    ],
    "check": [
      "@format-check",
      "@phpstan",
      "@psalm",
      "@phpmd",
      "@ast-metrics",
      "@cpd"
    ],
    "ci": [
      "@check",
      "@test"
    ]
  }
}
```

### 3) Docker image

Piqule includes a Dockerfile that builds a Docker image with
infrastructure-level linters and AST Metrics.

The Docker image contains:

- actionlint
- hadolint
- markdownlint-cli2
- yamllint
- typos
- ast-metrics
- pmd (CPD)

The Docker image is provided as a **ready-to-use local environment**
for running linters and AST Metrics without installing them on the host system.
Usage of the Docker image is optional and independent of the CI workflows.

Example local usage:

```bash
docker build -t piqule .
docker run --rm -v "$PWD:/app" -w /app piqule markdownlint-cli2 "**/*.md"
```

---

## Contributing

Fork the repository, apply changes, and open a pull request.

Before submitting, ensure all Piqule checks pass locally or in CI.

---

## License

MIT
