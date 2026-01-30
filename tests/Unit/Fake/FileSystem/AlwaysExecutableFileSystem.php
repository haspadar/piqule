<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\FileSystem;

use Haspadar\Piqule\FileSystem\FileSystem;

final class AlwaysExecutableFileSystem implements FileSystem
{
    public function __construct(
        private FileSystem $origin,
    ) {}

    public function exists(string $name): bool
    {
        return $this->origin->exists($name);
    }

    public function read(string $name): string
    {
        return $this->origin->read($name);
    }

    public function write(string $name, string $contents): void
    {
        $this->origin->write($name, $contents);
    }

    public function writeExecutable(string $name, string $contents): void
    {
        $this->origin->writeExecutable($name, $contents);
    }

    public function isExecutable(string $name): bool
    {
        return true;
    }

    public function names(): iterable
    {
        return $this->origin->names();
    }
}
