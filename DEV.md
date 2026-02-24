# DEV

## Templates

Location:

`templates/root/`

Structure mirrors target project root.

Example:

`templates/root/.github/workflows/ci.yml`  
`templates/root/.piqule/phpstan.neon`  
`templates/root/docker/Dockerfile`

Everything under `templates/root/` is copied relative to project root.

---

## Synchronization

Run:

`bin/piqule sync`

Flow:

1. Load `.piqule/config.php` (if exists)
2. Scan `templates/root/`
3. Resolve placeholders
4. Copy files to project root

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

`<< config(paths)|default(['src'])|join(',') >>`

---

## Project Configuration

Optional file:

`.piqule/config.php`

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

---

## Docker

### Build

```bash
docker buildx build -f docker/Dockerfile -t piqule:latest --load .
```

### Run all checks

```bash
docker run --rm \
  -v "$PWD:/project" \
  -w /project \
  piqule:latest \
  check
```

### Run specific check

```bash
docker run --rm \
  -v "$PWD:/project" \
  -w /project \
  piqule:latest \
  check phpunit
```

### Open interactive shell

```bash
docker run --rm -it \
  --entrypoint bash \
  -v "$PWD:/project" \
  -w /project \
  piqule:latest
```

---

## Tool Versions

Pinned in:

`docker/Dockerfile`

Updated via `ARG` variables.

---

## Entry Point

`/usr/local/lib/piqule/entrypoint`
