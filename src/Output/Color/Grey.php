<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Color;

use Override;

final readonly class Grey implements Color
{
    #[Override]
    public function apply(string $text): string
    {
        return "\033[90m{$text}\033[0m";
    }
}
