<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Storage;

use Haspadar\Piqule\File\File;
use Override;

final readonly class DryRunTargetStorage implements TargetStorage
{
    public function __construct(
        private TargetStorage $origin,
    ) {}

    #[Override]
    public function exists(string $relativePath): bool
    {
        return $this->origin->exists($relativePath);
    }

    #[Override]
    public function read(string $relativePath): File
    {
        return $this->origin->read($relativePath);
    }

    #[Override]
    public function write(string $relativePath, File $source): void
    {
        // intentionally no-op
    }
}
