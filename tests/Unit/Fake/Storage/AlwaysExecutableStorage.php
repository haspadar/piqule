<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Storage;

use Haspadar\Piqule\Storage\Storage;

final class AlwaysExecutableStorage implements Storage
{
    public function __construct(
        private Storage $origin,
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
        $this->origin->write($name, $contents);
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
