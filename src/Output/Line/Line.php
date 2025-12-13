<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output\Line;

use Haspadar\Piqule\Output\Color\Color;

interface Line
{
    public function text(): string;

    public function color(): Color;

    /** @return resource */
    public function stream();
}
