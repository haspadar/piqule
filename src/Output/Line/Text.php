<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;
use Override;

final readonly class Text implements Line
{
    public function __construct(
        private string $text,
        private Color $color,
    ) {}

    #[Override]
    public function text(): string
    {
        return $this->text;
    }

    #[Override]
    public function color(): Color
    {
        return $this->color;
    }

    #[Override]
    public function stream(): mixed
    {
        return STDOUT;
    }
}
