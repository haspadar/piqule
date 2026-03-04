<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\Reaction\StorageReaction;
use Override;

final readonly class AppendingStorage implements Storage
{
    public function __construct(
        private Storage $origin,
        private StorageReaction $reaction,
        private string $marker,
    ) {}

    #[Override]
    public function write(File $file): self
    {
        if (!$this->origin->exists($file->name())) {
            $newOrigin = $this->origin->write($file);
            $this->reaction->created($file->name());

            return new self($newOrigin, $this->reaction, $this->marker);
        }

        if (str_contains($this->origin->read($file->name()), $this->marker)) {
            return $this;
        }

        $merged = new TextFile(
            $file->name(),
            $this->origin->read($file->name()) . "\n" . $file->contents(),
            $this->origin->mode($file->name()),
        );
        $newOrigin = $this->origin->write($merged);
        $this->reaction->updated($file->name());

        return new self($newOrigin, $this->reaction, $this->marker);
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
