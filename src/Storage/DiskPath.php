<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\PiquleException;

final readonly class DiskPath
{
    public function __construct(
        private string $root,
    ) {}

    public function full(string $name): string
    {
        if ($this->isTraversal($name)) {
            throw new PiquleException(
                sprintf('Invalid storage path "%s"', $name),
            );
        }

        return rtrim($this->root, '/') . '/' . $name;
    }

    public function root(): string
    {
        return rtrim($this->root, '/');
    }

    private function isTraversal(string $name): bool
    {
        return str_contains($name, "\0")
            || str_starts_with($name, '/')
            || str_contains($name, '../')
            || str_contains($name, '..\\');
    }
}
