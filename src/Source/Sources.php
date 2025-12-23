<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source;

interface Sources
{
    /**
     * @return iterable<Source>
     */
    public function files(): iterable;
}
