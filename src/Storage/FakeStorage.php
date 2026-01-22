<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;

final class FakeStorage implements Storage
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

    public function exists(string $name): bool
    {
        return array_key_exists($name, $this->files);
    }

    public function read(string $name): string
    {
        if (!$this->exists($name)) {
            throw new PiquleException(
                sprintf('File "%s" does not exist', $name),
            );
        }

        return $this->files[$name];
    }

    public function write(string $name, string $contents): void
    {
        $this->files[$name] = $contents;
    }
}
