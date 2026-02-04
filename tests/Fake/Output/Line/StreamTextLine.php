<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Output\Line;

use Haspadar\Piqule\Output\Color\Color;
use Haspadar\Piqule\Output\Line\Line;

final readonly class StreamTextLine implements Line
{
    public function __construct(
        private string $text,
        private Color  $color,
        private mixed  $stream,
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
        return $this->stream;
    }
}
