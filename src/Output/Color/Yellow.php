<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Color;

use Override;

final readonly class Yellow implements Color
{
    #[Override]
    public function apply(string $text): string
    {
        return "\033[33m{$text}\033[0m";
    }
}
