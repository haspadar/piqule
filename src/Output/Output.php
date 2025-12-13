<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output;

use Haspadar\Piqule\Output\Line\Line;

interface Output
{
    public function write(Line $line): void;
}
