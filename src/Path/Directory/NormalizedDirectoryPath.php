<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path\Directory;

use Haspadar\Piqule\Path\Path;
use Override;

final readonly class NormalizedDirectoryPath implements DirectoryPath
{
    public function __construct(
        private AbsoluteDirectoryPath $origin,
    ) {}

    #[Override]
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
