<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Color;

final readonly class Green implements Color
{
    #[\Override]
    public function apply(string $text): string
    {
        return "\033[32m{$text}\033[0m";
    }
}
