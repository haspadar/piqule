<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Sources;

use Closure;
use Haspadar\Piqule\Source\Source;
use Override;

final readonly class MappedSources implements Sources
{
    /**
     * @param Closure(Source): Source $map
     */
    public function __construct(
        private Sources $origin,
        private Closure $map,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->origin->all() as $file) {
            yield ($this->map)($file);
        }
    }
}
