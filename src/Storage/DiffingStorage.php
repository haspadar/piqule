<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Storage\Reaction\StorageReaction;
use Override;

final readonly class DiffingStorage implements Storage
{
    public function __construct(
        private Storage $origin,
        private StorageReaction $reaction,
    ) {}

    #[Override]
    public function write(File $file): self
    {
        $path = $file->name();

        if (!$this->origin->exists($path)) {
            $this->origin->write($file);
            $this->reaction->created($path);

            return $this;
        }

        if ($this->origin->read($path) !== $file->contents()) {
            $this->origin->write($file);
            $this->reaction->updated($path);
        }

        return $this;
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
}
