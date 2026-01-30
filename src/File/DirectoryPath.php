<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

final readonly class DirectoryPath
{
    public function __construct(
        private string $value,
    ) {}

    public function value(): string
    {
        return $this->normalized();
    }

    private function normalized(): string
    {
        if ($this->isRoot()) {
            return $this->value;
        }

        return rtrim($this->value, '\\/');
    }

    private function isRoot(): bool
    {
        return $this->value === '/'
            || $this->value === '\\'
            || preg_match('/^[A-Za-z]:[\\\\\/]$/', $this->value) === 1;
    }
}
