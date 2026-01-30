<?php

declare(strict_types=1);

namespace Haspadar\Piqule\FileSystem;

use Override;

final readonly class DryRunFileSystem implements FileSystem
{
    public function __construct(
        private FileSystem $origin,
    ) {}

    #[Override]
    public function exists(string $name): bool
    {
        return $this->origin->exists($name);
    }

    #[Override]
    public function read(string $name): string
    {
        return $this->origin->read($name);
    }

    /**
     * Suppresses file writing in dry-run mode
     */
    #[Override]
    public function write(string $name, string $contents): void {}

    /**
     * Suppresses executable file writing in dry-run mode
     */
    #[Override]
    public function writeExecutable(string $name, string $contents): void {}

    #[Override]
    public function isExecutable(string $name): bool
    {
        return $this->origin->isExecutable($name);
    }

    #[Override]
    public function names(): iterable
    {
        return $this->origin->names();
    }
}
