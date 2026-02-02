<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Sources;

use Haspadar\Piqule\Source\Source;

interface Sources
{
    /**
     * @return iterable<Source>
     */
    public function all(): iterable;
}
