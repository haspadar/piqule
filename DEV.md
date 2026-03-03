# DEV

## Templates

Templates are stored in two locations:

- `templates/root/`
- `templates/git/`

### Root Templates

Location:

`templates/root/`

Structure mirrors target project root.

Everything under `templates/root/` is copied relative to project root.

Example:

`templates/root/.github/workflows/piqule.yml`  
`templates/root/.piqule/phpstan.neon`

### Git Templates

Location:

`templates/git/`

Everything under `templates/git/` is copied into:

`.git/`

---

## Synchronization

Run:

`bin/piqule sync`

Flow:

1. Load `.piqule.php` if it exists (optional)
2. Scan `templates/root/`
3. Scan `templates/git/`
4. Resolve placeholders
5. Write files to project root
6. Write files into `.git/`
7. `.github/` and `.piqule/` are fully generated

Note:

`.piqule.php` is optional and is not generated automatically.

---

## Placeholders

Syntax:

`<< config(path.to.value) >>`

Example:

`<< config(coverage.project.target) >>`

### Supported actions

- `default_list([...])`
- `default_scalar("value")`
- `format_each('%s')`
- `join(',')`
- `format('%s')`

### Semantics

The DSL operates in stages:

1. `config(...)` produces a list of values
2. List-level actions:
   - `default_list`
   - `format_each`
3. `join` reduces the list to a single value
4. Scalar-level actions:
    - `default_scalar`
    - `format`

`default_scalar` fails fast if the resolved pipeline value contains more than one item.

### Examples

List formatting:

`<< config(paths)|default_list(["src"])|format_each('%s')|join(",") >>`

Scalar default without explicit assertion:

`<< config(phpstan.memory)|default_scalar("1G") >>`

Final value formatting:

`<< config(paths)|default_list(["src"])|join(",")|format('paths: %s') >>`

---

## Project Configuration

Optional file:

`.piqule.php`

Example:

```php
<?php

return [
    'coverage.project.target' => '85%',
    'docker.image' => 'ghcr.io/haspadar/piqule-infra:latest',
];
```

Keys are flat and use dot-separated names directly.

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
