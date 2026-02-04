<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\Placeholder\Placeholder;

interface Placeholders
{
    /**
     * @return iterable<Placeholder>
     */
    public function all(): iterable;
}
