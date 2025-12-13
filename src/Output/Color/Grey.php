<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Color;

final readonly class Grey implements Color
{
    public function apply(string $text): string
    {
        return "\033[90m{$text}\033[0m";
    }
}
