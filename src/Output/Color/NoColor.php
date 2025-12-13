<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Color;

final readonly class NoColor implements Color
{
    public function apply(string $text): string
    {
        return $text;
    }
}
