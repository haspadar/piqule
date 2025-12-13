<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Structure;

final readonly class DotPiqule implements Directory
{
    public function __construct(private string $path) {}

    public function path(): string
    {
        return $this->path;
    }
}
