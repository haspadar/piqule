<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

/**
 * A named group of default configuration keys
 */
interface ConfigSection
{
    /** @return array<string, scalar|list<scalar>> */
    public function toArray(): array;
}
