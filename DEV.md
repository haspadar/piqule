# DEV

## Templates

Location:

`templates/root/`

Structure mirrors target project root.

Example:

`templates/root/.github/workflows/ci.yml`  
`templates/root/.piqule/phpstan.neon`  
`templates/root/Dockerfile`

Everything under `templates/root/` is copied relative to project root.

---

## Synchronization

Run:

`bin/piqule sync`

Flow:

1. Load `.piqule.php` (if exists)
2. Scan `templates/root/`
3. Resolve placeholders
4. Write files to project root
5. `.piqule/` is fully generated

---

## Placeholders

Syntax:

`<< config(path.to.value) >>`

Example:

`<< config(coverage.project.target) >>`

Supported actions:

- `default([...])`
- `join(',')`
- `format('%s')`
- `scalar`

Example:

`<< config(paths)|default(["src"])|join(",") >>`

---

## Project Configuration

Optional file:

`.piqule.php`

Example:

```php
<?php

return [
    'coverage' => [
        'project' => [
            'target' => '85%',
        ],
    ],
];
```

Accessed via dot notation:

`coverage.project.target`

If the file does not exist, defaults are used.

---

## Docker (Infra Image)

The infra image is defined by:

`Dockerfile` (project root)

Build locally:

```bash
docker buildx build -t piqule-infra:local --load .
```

Runtime image is selected via:

- `.piqule.php` → `docker.image`
- `PIQULE_INFRA_IMAGE` environment variable (highest priority)

Execution is delegated to `.piqule/_docker.sh`.

---

## Tool Versions

Pinned via `ARG` variables in:

`Dockerfile`

Updated via Renovate.

---