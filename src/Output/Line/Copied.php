<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;
use Haspadar\Piqule\Output\Color\Green;

final readonly class Copied implements Line
{
    public function __construct(private string $filename) {}

    public function text(): string
    {
        return "Copied $this->filename";
    }

    public function color(): Color
    {
        return new Green();
    }

    public function stream(): mixed
    {
        return STDOUT;
    }
}
