# Piqule

Static analysis and linters for PHP projects.  
It bundles PHPStan, Psalm, PHP-CS-Fixer, PhpMetrics, Markdownlint, Hadolint, Actionlint, and REUSE checks.  
You don’t need to configure them individually anymore.

## Usage

Add Piqule to your project:

```
composer require --dev haspadar/piqule
```

Include shared configs:

**phpstan.neon**

```
includes:
- vendor/piqule/piqule/config/phpstan.neon
  ```

**php-cs-fixer.php**

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

**psalm.xml**

```
<psalm>
  <import name="vendor/piqule/piqule/config/psalm.xml" />
</psalm>
```

Optional helper:

```
vendor/bin/piqule init
```

## GitHub Actions

Use preconfigured workflows:

```
jobs:
  phpstan:
    uses: haspadar/piqule/.github/workflows/phpstan.yml@v1
```

```
jobs:
  lint:
    uses: haspadar/piqule/.github/workflows/lint.yml@v1
```

## Contribute

Fork, modify, and submit a pull request.  
Before sending it, run all Piqule checks locally.

## License

MIT.
