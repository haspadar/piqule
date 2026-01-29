<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;
use Override;

final class InMemoryStorage implements Storage
{
    /** @var array<string, string> */
    private array $files;

    /**
     * @param array<string, string> $files
     */
    public function __construct(array $files = [])
    {
        $this->files = $files;
    }

    #[Override]
    public function exists(string $name): bool
    {
        return array_key_exists($name, $this->files);
    }

    #[Override]
    public function read(string $name): string
    {
        if (!$this->exists($name)) {
            throw new PiquleException(
                sprintf('File "%s" does not exist', $name),
            );
        }

        return $this->files[$name];
    }

    #[Override]
    public function write(string $name, string $contents): void
    {
        $this->files[$name] = $contents;
    }

    #[Override]
    public function writeExecutable(string $name, string $contents): void
    {
        $this->write($name, $contents);
    }

    #[Override]
    public function isExecutable(string $name): bool
    {
        if (!$this->exists($name)) {
            throw new PiquleException(
                sprintf('File "%s" does not exist', $name),
            );
        }

        return false;
    }

    /**
     * @return iterable<string> logical file names
     */
    #[Override]
    public function names(): iterable
    {
        return array_keys($this->files);
    }
}
