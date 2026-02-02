<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

use Haspadar\Piqule\PiquleException;

final readonly class FileName
{
    public function __construct(
        private string $value,
    ) {}

    /**
     * @throws PiquleException
     */
    public function value(): string
    {
        if ($this->value === '') {
            throw new PiquleException('File name cannot be empty');
        }

        if (str_starts_with($this->value, '/')) {
            throw new PiquleException('File name must be relative');
        }

        if (preg_match('/^[A-Za-z]:[\\\\\\/]/', $this->value) === 1) {
            throw new PiquleException('File name must be relative');
        }

        if (str_contains($this->value, '..')) {
            throw new PiquleException('File name cannot contain ".."');
        }

        return $this->value;
    }
}
