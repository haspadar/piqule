<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;

final readonly class DiskFile implements File
{
    /**
     * Absolute or relative filesystem path
     */
    public function __construct(
        private string $path,
    ) {}

    /**
     * Returns the filesystem path of the file
     *
     * This method is intentionally not part of the File interface,
     * as paths are an infrastructure concern.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Reads and returns the file contents
     *
     * @throws PiquleException If the file cannot be read
     */
    public function contents(): string
    {
        $contents = file_get_contents($this->path);

        if ($contents === false) {
            throw new PiquleException(
                sprintf('Failed to read file: "%s"', $this->path),
            );
        }

        return $contents;
    }
}
