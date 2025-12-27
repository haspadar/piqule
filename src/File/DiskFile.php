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
     * Reads and returns the file contents
     *
     * @throws PiquleException If the file cannot be read
     */
    public function contents(): string
    {
        if (!is_file($this->path)) {
            throw new PiquleException(
                sprintf('Failed to read file: "%s"', $this->path),
            );
        }

        return file_get_contents($this->path);
    }
}
