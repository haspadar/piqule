<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

final readonly class DiskPiquleDirectory implements PiquleDirectory
{
    public function __construct(
        private string $path,
    ) {}

    public function exists(): bool
    {
        return is_dir($this->path);
    }

    public function path(): string
    {
        return $this->path;
    }

    public function lockFile(): string
    {
        return $this->path() . '/lock.json';
    }
}
