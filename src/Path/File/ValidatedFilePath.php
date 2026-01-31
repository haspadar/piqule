<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Path\File;

use Haspadar\Piqule\Path\Path;
use Haspadar\Piqule\PiquleException;
use Override;

final readonly class ValidatedFilePath implements FilePath
{
    public function __construct(
        private Path $origin,
    ) {}

    #[Override]
    public function value(): string
    {
        $value = $this->origin->value();

        if (str_ends_with($value, '/') || str_ends_with($value, '\\')) {
            throw new PiquleException('File path must point to a file, not a directory');
        }

        return $value;
    }
}
