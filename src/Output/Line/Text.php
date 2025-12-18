<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;

final readonly class Text implements Line
{
    public function __construct(
        private string $text,
        private Color $color,
    ) {}

    public function text(): string
    {
        return $this->text;
    }

    public function color(): Color
    {
        return $this->color;
    }

    public function stream(): mixed
    {
        return STDOUT;
    }
}
