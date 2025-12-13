<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Structure;

final readonly class Root implements Directory
{
    public function __construct(private string $path) {}

    public function path(): string
    {
        return $this->path;
    }

    public function dotPiqule(): DotPiqule
    {
        return new DotPiqule($this->path . '/.piqule');
    }
}
