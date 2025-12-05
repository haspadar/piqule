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

## Optional helper

```
vendor/bin/piqule init
```

This command can generate baseline config files for PHPStan, Psalm, and PHP-CS-Fixer.

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

## Contribute

Fork, modify, and open a pull request.  
Before submitting a PR, run all Piqule checks locally or via GitHub Actions.

## License

MIT.
