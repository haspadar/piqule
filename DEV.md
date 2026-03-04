# DEV

## Templates

Templates are stored in three locations:

- `templates/always/`
- `templates/git/`
- `templates/once/`

### Always Templates

Location:

`templates/always/`

Structure mirrors target project root.

Everything under `templates/always/` is copied relative to project root on every `sync`, overwriting on change.

Example:

`templates/always/.github/workflows/piqule.yml`
`templates/always/.piqule/phpstan.neon`

### Git Templates

Location:

`templates/git/`

Everything under `templates/git/` is copied into:

`.git/`

### Once Templates

Location:

`templates/once/`

Everything under `templates/once/` is copied relative to project root only if the target file does not exist yet. User edits survive subsequent `sync` runs.

`.piqule.php` is generated from `templates/once/` on the first `sync`.

---

## Synchronization

Run:

`bin/piqule sync`

Flow:

1. Load `.piqule.php` if it exists (optional)
2. Scan `templates/always/`
3. Scan `templates/git/`
4. Scan `templates/once/`
5. Resolve placeholders
6. Write `templates/always/` → project root (overwrite on change)
7. Write `templates/git/` → `.git/`
8. Write `templates/once/` → project root (only if file doesn't exist)

Note:

`.piqule.php` is generated from `templates/once/` on the first `sync`. Edit it freely — subsequent syncs will not overwrite it.

---

## Placeholders

Syntax:

`<< config(path.to.value) >>`

Example:

`<< config(phpstan.level) >>`

### Supported actions

- `config(key)` — loads a list of values from configuration
- `format_each(template)` — formats each list item
- `join(delimiter)` — reduces the list to a single scalar value
- `format(template)` — formats the scalar value

### Semantics

The DSL operates in stages:

1. `config(...)` produces a list of values
2. List-level actions:
   - `format_each`
3. `join` reduces the list to a single value
4. Scalar-level actions:
   - `format`

### Examples

List formatting:

`<< config(phpunit.testsuites.unit)|format_each("            <directory>%s</directory>")|join("\n") >>`

Final value formatting:

`<< config(phpstan.level)|join(",")|format('level: %s') >>`

---

## Project Configuration

Optional file:

`.piqule.php`

Example:

```php
<?php

declare(strict_types=1);

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\OverrideConfig;

return new OverrideConfig(new DefaultConfig(), [
    'ci.php.matrix' => ['8.3', '8.4', '8.5'],
    'docker.image' => 'ghcr.io/haspadar/piqule-infra:latest',
]);
```

Keys are flat and use dot-separated names. All valid keys are declared in `DefaultConfig`.

If the file does not exist, defaults are used.

---

## Infra Image

Runtime image is selected via:

- `.piqule.php` → `docker.image`
- `PIQULE_INFRA_IMAGE` environment variable (highest priority)

Execution is delegated to `.piqule/_docker.sh`.

---

## Tool Versions

Pinned inside the infra image.

Updated via Renovate.
