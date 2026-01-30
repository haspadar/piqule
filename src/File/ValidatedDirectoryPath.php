<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\PiquleException;

final readonly class ValidatedDirectoryPath
{
    public function __construct(
        private DirectoryPath $origin,
    ) {}

    public function value(): string
    {
        $value = $this->origin->value();

        if ($value === '') {
            throw new PiquleException('Directory path cannot be empty');
        }

        if (!$this->isAbsolute($value)) {
            throw new PiquleException('Directory path must be absolute');
        }

        return $value;
    }

    private function isAbsolute(string $value): bool
    {
        return str_starts_with($value, '/')
            || str_starts_with($value, '\\')
            || preg_match('/^[A-Za-z]:[\\\\\\/]/', $value) === 1;
    }
}
