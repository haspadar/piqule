<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class DiskFile implements File
{
    /**
     * Absolute or relative filesystem path
     */
    public function __construct(
        private string $path,
    ) {
    }

    /**
     * Reads and returns the file contents
     *
     * @throws PiquleException If the file cannot be read
     */
    #[Override]
    public function contents(): string
    {
        if (!is_file($this->path)) {
            throw new PiquleException(
                sprintf('Failed to read file: "%s"', $this->path),
            );
        }

        $contents = file_get_contents($this->path);
        if ($contents === false) {
            throw new PiquleException(sprintf('File is not readable: "%s"', $this->path));
        }

        return $contents;
    }
}
