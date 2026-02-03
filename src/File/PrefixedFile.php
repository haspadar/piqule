<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Override;

final readonly class PrefixedFile implements File
{
    public function __construct(
        private string $prefix,
        private File $origin,
    ) {}

    #[Override]
    public function name(): string
    {
        return rtrim($this->prefix, '/')
            . '/'
            . ltrim($this->origin->name(), '/');
    }

    #[Override]
    public function contents(): string
    {
        return $this->origin->contents();
    }
}
