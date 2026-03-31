<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Storage\Reaction\StorageReaction;
use Override;

/**
 * Writes a file only if it does not already exist in the underlying storage
 */
final readonly class OnceStorage implements Storage
{
    public function __construct(
        private Storage $origin,
        private StorageReaction $reaction,
    ) {}

    /** @throws \Haspadar\Piqule\PiquleException */
    #[Override]
    public function write(File $file): self
    {
        if ($this->origin->exists($file->name())) {
            return $this;
        }

        $newOrigin = $this->origin->write($file);
        $this->reaction->created($file->name());

        return new self($newOrigin, $this->reaction);
    }

    #[Override]
    public function read(string $location): string
    {
        return $this->origin->read($location);
    }

    #[Override]
    public function exists(string $location): bool
    {
        return $this->origin->exists($location);
    }

    #[Override]
    public function entries(string $location): iterable
    {
        return $this->origin->entries($location);
    }

    #[Override]
    public function mode(string $location): int
    {
        return $this->origin->mode($location);
    }
}
