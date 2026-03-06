<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\Reaction\StorageReaction;
use Override;

/**
 * Appends file contents to an existing file unless the marker string is already present
 */
final readonly class AppendingStorage implements Storage
{
    public function __construct(
        private Storage $origin,
        private StorageReaction $reaction,
        private string $marker,
    ) {}

    /**
     * Appends file contents to an existing file if the marker is absent; creates a new file otherwise.
     */
    #[Override]
    public function write(File $file): self
    {
        $path = $file->name();

        if (!$this->origin->exists($path)) {
            $newOrigin = $this->origin->write($file);
            $this->reaction->created($path);

            return new self($newOrigin, $this->reaction, $this->marker);
        }

        $current = $this->origin->read($path);

        if (str_contains($current, $this->marker)) {
            return $this;
        }

        $merged = new TextFile(
            $path,
            $current . "\n" . $file->contents(),
            $this->origin->mode($path),
        );
        $newOrigin = $this->origin->write($merged);
        $this->reaction->updated($path);

        return new self($newOrigin, $this->reaction, $this->marker);
    }

    /**
     * Reads file contents from the underlying storage.
     */
    #[Override]
    public function read(string $location): string
    {
        return $this->origin->read($location);
    }

    /**
     * Checks whether a file exists in the underlying storage.
     */
    #[Override]
    public function exists(string $location): bool
    {
        return $this->origin->exists($location);
    }

    /**
     * Returns an iterable of file paths within the given folder.
     */
    #[Override]
    public function entries(string $location): iterable
    {
        return $this->origin->entries($location);
    }

    /**
     * Returns the file permission bits for the given location.
     */
    #[Override]
    public function mode(string $location): int
    {
        return $this->origin->mode($location);
    }
}
