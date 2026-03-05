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

Files are written with `DiffingStorage` (overwrite on change), except `pre-push` — it is appended via `AppendingStorage` using the marker `# BEGIN piqule` / `# END piqule`. This makes the operation idempotent: if the block is already present, `sync` skips it.

The appended block wires `pre-push-piqule`:

```sh
# BEGIN piqule
[ -f "$(dirname "$0")/pre-push-piqule" ] && "$(dirname "$0")/pre-push-piqule" "$@"
# END piqule
```

`pre-push-renovate` is copied to `.git/hooks/` but not wired automatically. To enable it, add the following line inside the `# BEGIN piqule / # END piqule` block in `.git/hooks/pre-push`:

```sh
[ -f "$(dirname "$0")/pre-push-renovate" ] && "$(dirname "$0")/pre-push-renovate" "$@"
```

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
7. Write `templates/git/` (non-pre-push files) → `.git/` (overwrite on change)
8. Write `templates/git/` (pre-push file) → `.git/hooks/pre-push` (append if marker absent)
9. Write `templates/once/` → project root (only if file doesn't exist)
10. Pin template checksums → `.piqule/templates.md5`

Note:

`.piqule.php` is generated from `templates/once/` on the first `sync`. Edit it freely — subsequent syncs will not overwrite it.

---

## Template Pinning

`bin/piqule-pin` computes a combined MD5 checksum of all files in `templates/always/` and `templates/git/` and writes it to `.piqule/templates.md5`. Called automatically by `piqule sync`.

`bin/piqule-verify` compares the current checksum against the pinned value. If they differ, it prints a warning and suggests running `piqule sync`. Called automatically by `piqule check` and `piqule fix`. Silent if `.piqule/templates.md5` does not exist.

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

## Infra Image Build

Build:

```bash
docker buildx build -t ghcr.io/haspadar/piqule-infra:local --load .
```

Run shell:

```bash
docker run --rm -it \
  --entrypoint bash \
  -v "$PWD:/project" \
  -w /project \
  ghcr.io/haspadar/piqule-infra:local
```

---

## Tool Versions

Pinned inside the infra image.

Updated via Renovate.

---

## Architecture Overview

Source code is organized into layers. Each layer depends only on its own interfaces:

```
Config → Formula → File → Files → Storage
```

- **Config** (`src/Config/`) — flat key-value store; `DefaultConfig` declares all valid keys, `OverrideConfig` applies user overrides
- **Formula** (`src/Formula/`) — evaluates `<< ... >>` placeholder expressions via a pipeline of `Action` objects
- **File** (`src/File/`) — represents a single file with `name()`, `contents()`, `mode()`; decorators add behaviour (placeholder resolution, path prefix, string replacement)
- **Files** (`src/Files/`) — iterable collection of `File` objects; composable via decorators
- **Storage** (`src/Storage/`) — filesystem abstraction; decorators add write policy (diffing, once-only, or appending) and reactions
- **Output** (`src/Output/`) — console output interface; `Console` writes to stdout, `Message` is a value object for a single line

For a full description of every class and the decorator pattern, see [docs/architecture.md](docs/architecture.md).

---

## Adding a New Tool

1. Create `templates/always/.piqule/<tool>/` and add a `command.sh` inside it
2. Add any config keys the tool needs to `src/Config/DefaultConfig.php` (`DEFAULTS` array)
3. Register the new key type in `src/Config/OverrideConfig.php` (`OverrideMap` PHPDoc)
4. Add the tool name to `$checks` in `bin/piqule-check`
5. Add `'<tool>.enabled' => true` to `DefaultConfig` and `'<tool>.enabled'?: bool` to `OverrideMap` in `OverrideConfig`
6. Run `vendor/bin/piqule sync` to verify template rendering
7. Write unit and integration tests

---

## Adding a Config Key

1. Add the key to `DEFAULTS` in `src/Config/DefaultConfig.php`:
   - Scalar value → stored as-is, returned as single-element list
   - List value → `list<scalar>`
2. Add the corresponding entry to the `OverrideMap` PHPDoc type in `src/Config/OverrideConfig.php`
3. Use the key in a template placeholder: `<< config(my.new.key) >>`

Keys are flat dot-separated names. Accessing an undeclared key throws `PiquleException`.
