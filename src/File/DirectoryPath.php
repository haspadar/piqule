<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;

final readonly class DirectoryPath
{
    public function __construct(
        private string $value,
    ) {}

    public function value(): string
    {
        if ($this->isEmpty()) {
            throw new PiquleException('Directory path cannot be empty');
        }

        if (!$this->isAbsolute()) {
            throw new PiquleException('Directory path must be absolute');
        }

        return $this->normalized();
    }

    private function isEmpty(): bool
    {
        return $this->value === '';
    }

    private function isAbsolute(): bool
    {
        return $this->isPosixAbsolute()
            || $this->isWindowsDriveAbsolute()
            || $this->isWindowsUncAbsolute();
    }

    private function isPosixAbsolute(): bool
    {
        return str_starts_with($this->value, '/');
    }

    private function isWindowsDriveAbsolute(): bool
    {
        return preg_match('/^[A-Za-z]:[\\\\\\/]/', $this->value) === 1;
    }

    private function isWindowsUncAbsolute(): bool
    {
        return str_starts_with($this->value, '\\');
    }

    private function normalized(): string
    {
        return rtrim($this->value, '\\/');
    }
}
