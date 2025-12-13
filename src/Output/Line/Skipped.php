<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;
use Haspadar\Piqule\Output\Color\Grey;
use Haspadar\Piqule\Output\Color\Yellow;

final readonly class Skipped implements Line
{
    public function __construct(private string $message) {}

    public function text(): string
    {
        return "Skipped: $this->message";
    }

    public function color(): Color
    {
        return new Grey();
    }

    public function stream(): mixed
    {
        return STDOUT;
    }
}
