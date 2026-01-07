# Opinionated PHP Quality Policies

[![CI](https://github.com/haspadar/piqule/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/piqule/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/haspadar/piqule/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/piqule)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fpiqule%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/piqule/main)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%209-brightgreen)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/psalm-level%201-brightgreen)](https://psalm.dev)

[![Hits-of-Code](https://hitsofcode.com/github/haspadar/piqule?branch=main)](https://hitsofcode.com/github/haspadar/piqule/view?branch=main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/piqule?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

**Piqule** provides a distribution of PHP quality policies.

It delivers predefined static analysis rules, CI workflows, and tooling
configurations by copying them into project repositories.

Piqule defines a single source of truth for quality policies;
conflicts and deviations are resolved explicitly through Git.

---

## Tooling overview

| Tool            | Purpose               | What Piqule provides                                |
|-----------------|-----------------------|-----------------------------------------------------|
| PHPStan         | Static analysis       | Strict ruleset and high analysis level              |
| Psalm           | Static analysis       | Complementary checks and early error detection      |
| PHP-CS-Fixer    | Code style            | Deterministic formatting rules                      |
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

Piqule defines quality policies and configuration files.
Actual analysis tools are installed explicitly per project.

### 1) Install Piqule

```bash
composer require --dev haspadar/piqule
```

### 2) Install PHP analysis tools

```bash
composer require --dev \
friendsofphp/php-cs-fixer \
phpunit/phpunit \
phpstan/phpstan \
vimeo/psalm \
phpmd/phpmd \
phpmetrics/phpmetrics \
infection/infection
```

---

## Configuration synchronization

Copies predefined configuration files into the project, overwriting existing ones.
No merging is performed; conflicts are resolved via Git.

```bash
bin/piqule.php sync
bin/piqule.php sync --dry-run
```

## Composer scripts (optional)

Piqule provides configuration files for PHP tooling.
Composer scripts are project-specific and not enforced.

A reference setup used in this repository:
https://github.com/haspadar/piqule/blob/main/composer.json

## Docker image

Piqule includes a Dockerfile that builds a Docker image with
infrastructure-level linters and AST Metrics.

The Docker image contains:

- actionlint
- hadolint
- markdownlint-cli2
- yamllint
- typos
- pmd (CPD)

The Docker image is provided as a **ready-to-use local environment**
for running linters without installing them on the host system.
The Docker image is optional and independent of CI workflows.

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
