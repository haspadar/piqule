<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Override;

/**
 * Merges multiple Files sources into a single sequential collection
 */
final readonly class CombinedFiles implements Files
{
    /**
     * @param list<Files> $sources
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
