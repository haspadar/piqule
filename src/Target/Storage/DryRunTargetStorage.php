<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Storage;

use Haspadar\Piqule\File\File;

final readonly class DryRunTargetStorage implements TargetStorage
{
    public function __construct(
        private TargetStorage $origin,
    ) {}

    public function exists(string $relativePath): bool
    {
        return $this->origin->exists($relativePath);
    }

    public function read(string $relativePath): File
    {
        return $this->origin->read($relativePath);
    }

    public function write(string $relativePath, File $source): void
    {
        // intentionally no-op
    }
}
