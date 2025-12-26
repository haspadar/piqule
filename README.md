[![CI](https://github.com/haspadar/piqule/actions/workflows/ci.yml/badge.svg)](https://github.com/haspadar/piqule/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/haspadar/piqule/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/piqule)

[![Hits-of-Code](https://hitsofcode.com/github/haspadar/piqule?branch=main)](https://hitsofcode.com/github/haspadar/piqule/view?branch=main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/piqule?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

# Piqule

**Piqule (PHP Quality Laws)** is a curated set of static analysis tools, linters, and CI workflows for PHP projects.

Piqule provides **managed configurations** and **reusable GitHub Actions workflows** to avoid duplicating the same setup across multiple repositories.

---

## Included tools

### PHP tools

- **PHPStan** — static analysis
- **Psalm** — static analysis
- **PHP-CS-Fixer** — code style enforcement
- **PhpMetrics** — code quality and complexity metrics

### Linters and CI tools

- **Markdownlint** — Markdown linting
- **Hadolint** — Dockerfile linting
- **Actionlint** — GitHub Actions workflow linting
- **Typos** — spell checking
- **Renovate** — dependency update automation
- **PR Size Checker** — pull request size enforcement

---

## Installation

Add Piqule as a development dependency:

```
composer require --dev haspadar/piqule
```

---

## Tool configuration

### PHPStan

Create or extend `phpstan.neon`:

```
includes:
- vendor/haspadar/piqule/config/phpstan.neon
```

---

### PHP-CS-Fixer

Create `.php-cs-fixer.php`:

```
<?php

/** @var PhpCsFixer\Config $rules */
$rules = require __DIR__ . '/vendor/haspadar/piqule/php-cs-fixer/rules.php';

$rules->setFinder(
    PhpCsFixer\Finder::create()
        ->in(__DIR__)
        ->exclude('vendor')
        ->exclude('node_modules')
);

return $rules;
```
---

### Psalm

Create or extend `psalm.xml`:

```
<psalm>
  <import name="vendor/haspadar/piqule/config/psalm.xml" />
</psalm>
```

---

### Markdownlint

Piqule ships a managed configuration for `markdownlint-cli2`:

```
.piqule/.markdownlint-cli2.jsonc
```

Reusable workflows automatically resolve the correct configuration depending on context.

Projects do not need to add their own `.markdownlint-cli2.jsonc` unless overrides are required.

---

### Hadolint

Managed config:

```
.piqule/.hadolint.yml
```

Reusable workflow:

```
jobs:
  hadolint:
    uses: haspadar/piqule/.github/workflows/_hadolint.yml@v1
```

---

### Actionlint

Reusable workflow:

```
jobs:
  actionlint:
    uses: haspadar/piqule/.github/workflows/_actionlint.yml@v1
```

---

### Typos

Managed config:

```
.piqule/_typos.toml
```

Reusable workflow:

```
jobs:
  typos:
    uses: haspadar/piqule/.github/workflows/_typos.yml@v1
```

---

### Renovate

Managed config:

```
renovate.json
```

Reusable workflow:

```
jobs:
  renovate:
    uses: haspadar/piqule/.github/workflows/_renovate.yml@v1
```

---

## GitHub Actions

Piqule provides reusable workflow modules stored in `.github/workflows`.

### Full CI pipeline

```
jobs:
  ci:
    uses: haspadar/piqule/.github/workflows/ci.yml@v1
```

---

### Individual workflows

PHP-CS-Fixer:

```
jobs:
  php_cs_fixer:
    uses: haspadar/piqule/.github/workflows/_php-cs-fixer.yml@v1
```

Markdownlint:

```
jobs:
  markdownlint:
    uses: haspadar/piqule/.github/workflows/_markdownlint.yml@v1
```

Hadolint:

```
jobs:
  hadolint:
    uses: haspadar/piqule/.github/workflows/_hadolint.yml@v1
```

Typos:

```
jobs:
  typos:
    uses: haspadar/piqule/.github/workflows/_typos.yml@v1
```

Yamllint:

```
jobs:
  yamllint:
    uses: haspadar/piqule/.github/workflows/_yamllint.yml@v1
```

Actionlint:

```
jobs:
  actionlint:
    uses: haspadar/piqule/.github/workflows/_actionlint.yml@v1
```

---

### PR Size Checker

Reusable workflow:

```
jobs:
  pr_size:
    uses: haspadar/piqule/.github/workflows/_pr-size-checker.yml@v1
    with:
      max_lines_changed: 200
```

Fails the workflow if a pull request exceeds the configured size.

---

## Contributing

Fork the repository, apply changes, and open a pull request.

Before submitting, ensure all Piqule checks pass locally or in CI.

---

## License

MIT
