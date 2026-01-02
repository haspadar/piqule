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

| Tool            | Purpose               | What Piqule provides                                    |
|-----------------|-----------------------|---------------------------------------------------------|
| PHPStan         | Static analysis       | Strict ruleset and high analysis level                  |
| Psalm           | Static analysis       | Complementary checks and early error detection          |
| PHP-CS-Fixer    | Code style            | Deterministic formatting rules                          |
| AST Metrics     | Code metrics          | Complexity, coupling, and maintainability quality gates |
| Infection       | Mutation testing      | Test quality validation                                 |
| Markdownlint    | Markdown linting      | Consistent documentation style                          |
| Hadolint        | Dockerfile linting    | Secure and reproducible Dockerfiles                     |
| Actionlint      | CI linting            | GitHub Actions correctness                              |
| Yamllint        | YAML linting          | Configuration consistency                               |
| Typos           | Spell checking        | Low-noise typo detection                                |
| Renovate        | Dependency automation | Managed dependency update rules                         |
| PR Size Checker | Process guard         | Pull request reviewability enforcement                  |

---

## Installation

Add Piqule as a development dependency:

```
composer require --dev haspadar/piqule
```

---

## Configuration synchronization

Piqule manages project configuration files via an explicit synchronization step.

```
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

---

### Dry run mode

To preview changes without modifying files, run:

```
bin/piqule.php sync --dry-run
```

This mode shows what files would be created, updated, or overwritten,
allowing verification before applying changes.

---

## Design principles

- **Template-driven** — configuration is generated from canonical sources
- **Managed, not merged** — files are overwritten deterministically
- **Explicit ownership** — local modifications are intentional and manual
- **Repeatable setup** — the same command produces the same layout everywhere
- **Low noise** — tooling is curated to avoid redundant checks

---

## Contributing

Fork the repository, apply changes, and open a pull request.

Before submitting, ensure all Piqule checks pass locally or in CI.

---

## License

MIT
