<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Closure;
use Haspadar\Piqule\File\File;
use Override;

final readonly class FilteredFiles implements Files
{
    /**
     * @param Closure(File): bool $predicate
     */
    public function __construct(
        private Files $origin,
        private Closure $predicate,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->origin->all() as $file) {
            if (($this->predicate)($file)) {
                yield $file;
            }
        }
    }
}
