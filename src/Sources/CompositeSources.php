<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Sources;

use Override;

final readonly class CompositeSources implements Sources
{
    /**
     * @param list<Sources> $sources
     */
    public function __construct(
        private array $sources,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->sources as $files) {
            yield from $files->all();
        }
    }
}
