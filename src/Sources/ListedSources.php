<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Sources;

use Haspadar\Piqule\Source\Source;
use Override;

final readonly class ListedSources implements Sources
{
    /**
     * @param list<Source> $files
     */
    public function __construct(
        private array $files,
    ) {}

    #[Override]
    public function all(): iterable
    {
        return $this->files;
    }
}
