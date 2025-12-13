<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Color;

interface Color
{
    public function apply(string $text): string;
}
