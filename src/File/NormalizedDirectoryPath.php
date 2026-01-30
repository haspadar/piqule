<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Path\Path;

final readonly class NormalizedDirectoryPath implements Path
{
    public function __construct(
        private DirectoryPath $origin,
    ) {}

    public function value(): string
    {
        $value = $this->origin->value();

        if ($this->isRoot($value)) {
            return $value;
        }

        return rtrim($value, '\\/');
    }

    private function isRoot(string $value): bool
    {
        return $value === '/'
            || $value === '\\'
            || preg_match('/^[A-Za-z]:[\\\\\/]$/', $value) === 1;
    }
}
