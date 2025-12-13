<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;
use Haspadar\Piqule\Output\Color\Red;

final readonly class Error implements Line
{
    public function __construct(private string $message) {}

    public function text(): string
    {
        return "Error: $this->message";
    }

    public function color(): Color
    {
        return new Red();
    }

    public function stream()
    {
        return STDOUT;
    }
}
