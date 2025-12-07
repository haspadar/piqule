# Piqule

Static analysis and linters for PHP projects.  
Piqule bundles and standardizes configurations for:

- PHPStan  
- Psalm  
- PHP-CS-Fixer  
- PhpMetrics  
- Markdownlint  
- Hadolint  
- Actionlint  
- Typos (spell‑checking)

You don’t need to configure these tools manually in every project — Piqule provides shared configs and reusable GitHub workflows.

## Usage

Add Piqule to your project:

```
composer require --dev haspadar/piqule
```

### PHPStan

Create your `phpstan.neon`:

```
includes:
  - vendor/haspadar/piqule/config/phpstan.neon
```

### PHP-CS-Fixer

Create your `.php-cs-fixer.php`:

```
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

### Psalm

Create or extend `psalm.xml`:

```
<psalm>
  <import name="vendor/haspadar/piqule/config/psalm.xml" />
</psalm>
```

### Markdownlint

Piqule includes a shared configuration for `markdownlint-cli2` and a reusable workflow that automatically selects the correct config depending on context.

The workflow resolves configuration in this order:

1. `vendor/haspadar/piqule/markdownlint/.markdownlint-cli2.jsonc` (when Piqule is installed as a dependency)
2. `markdownlint/.markdownlint-cli2.jsonc` (when running inside the Piqule repository itself)

Projects do not need to maintain their own `.markdownlint-cli2.jsonc` unless they want to override defaults.

### Hadolint

Piqule provides a shared Hadolint configuration and a reusable workflow:

```
jobs:
  hadolint:
    uses: haspadar/piqule/.github/workflows/hadolint-reusable.yml@v1
```

Projects may override the config stored in:

```
hadolint/.hadolint.yaml
```

### Actionlint

```
jobs:
  actionlint:
    uses: haspadar/piqule/.github/workflows/reusable/actionlint.yml@v1
```

### Typos

Piqule provides a shared configuration and reusable workflow for spell‑checking using Typos.

#### Reusable workflow

```
jobs:
  typos:
    uses: haspadar/piqule/.github/workflows/typos-reusable.yml@v1
    with:
      config: typos/_typos.toml
```

#### CI workflow

Projects may also use the preconfigured CI workflow:

```
jobs:
  typos:
    uses: haspadar/piqule/.github/workflows/typos.yml@v1
```

### Renovate

Piqule provides a shared Renovate configuration and reusable workflow.

#### Reusable workflow

```
jobs:
  renovate:
    uses: haspadar/piqule/.github/workflows/renovate-reusable.yml@v1
    with:
      config: renovate/config.json
```

#### CI workflow

Projects may also use the preconfigured CI workflow:

```
jobs:
  renovate:
    uses: haspadar/piqule/.github/workflows/renovate.yml@v1
```

## GitHub Actions

Piqule provides reusable CI modules.

### PHPStan

```
jobs:
  phpstan:
    uses: haspadar/piqule/.github/workflows/phpstan.yml@v1
```

### Lint suite (CS + Markdownlint + Hadolint + Actionlint)

```
jobs:
  lint:
    uses: haspadar/piqule/.github/workflows/lint.yml@v1
```

### Markdownlint only

```
jobs:
  markdownlint:
    uses: haspadar/piqule/.github/workflows/markdownlint.yml@v1
```

### Actionlint only

```
jobs:
  actionlint:
    uses: haspadar/piqule/.github/workflows/reusable/actionlint.yml@v1
```

### PR Size Checker

Piqule provides a reusable workflow for enforcing pull request size limits using
[`maidsafe/pr_size_checker`](https://github.com/maidsafe/pr_size_checker).

```
jobs:
  pr_size:
    uses: haspadar/piqule/.github/workflows/pr-size-checker-reusable.yml@v1
    with:
      max_lines_changed: 200
```

This workflow fails if a pull request changes more than the configured number of lines.

#### CI workflow

Projects may also use the preconfigured CI workflow:

```
jobs:
  pr_size:
    uses: haspadar/piqule/.github/workflows/pr-size-checker.yml@v1
```

## Contribute

Fork, modify, and open a pull request.  
Before submitting a PR, run all Piqule checks locally or via GitHub Actions.

## License

MIT.
