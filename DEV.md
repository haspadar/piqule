# DEV

## Templates

Location:

```bash
templates/root/
```

Structure mirrors target project root.

Example:

```bash
templates/root/.github/workflows/ci.yml
templates/root/.piqule/phpstan.neon
templates/root/docker/Dockerfile
```

Everything under `templates/root/` is copied relative to project root.

---

## Synchronization

Run:

```bash
bin/piqule-sync.php
```

Flow:

1. Load `.piqule/config.php` (if exists)
2. Scan `templates/root/`
3. Resolve placeholders
4. Copy files to project root

---

## Placeholders

Syntax:

```bash
<< config(path.to.value) >>
```

Example:

```bash
<< config(coverage.project.target) >>
```

Supported actions:

```bash
default([...])
join(',')
format('%s')
scalar
```

Example:

```bash
<< config(paths)|default(['src'])|join(',') >>
```

---

## Project Configuration

Optional file:

```bash
.piqule/config.php
```

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

```bash
coverage.project.target
```

---

## Docker

Build:

```bash
docker buildx build -f docker/Dockerfile -t piqule:latest --load .
```

Run shell:

```bash
docker run --rm -it \
  --entrypoint bash \
  -v "$PWD:/project" \
  -w /project \
  piqule:latest 
```

Run checks:

```bash
bash docker/bin/check
```

---

## Tool Versions

Pinned in:

```bash
docker/Dockerfile
```

Updated via `ARG` variables.

---

## Entry Point

```bash
/usr/local/lib/piqule/entrypoint
```
