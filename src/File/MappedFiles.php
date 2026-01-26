<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Closure;
use Override;

final readonly class MappedFiles implements Files
{
    /**
     * @param Closure(File): File $map
     */
    public function __construct(
        private Files $origin,
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
