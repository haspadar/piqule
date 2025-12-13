<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Color\Yellow;

final readonly class Differs implements Line
{
    public function __construct(private string $filename) {}

    public function text(): string
    {
        return "Differs: $this->filename (template changed)";
    }

    public function color(): Color
    {
        return new Yellow();
    }

    public function stream(): mixed
    {
        return STDOUT;
    }
}
