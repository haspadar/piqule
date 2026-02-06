<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\Placeholder\Placeholder;
use Override;

final readonly class CombinedPlaceholders implements Placeholders
{
    /**
     * @param iterable<Placeholders> $placeholders
     */
    public function __construct(
        private iterable $placeholders,
    ) {}

    /**
     * @return iterable<Placeholder>
     */
    #[Override]
    public function all(): iterable
    {
        foreach ($this->placeholders as $placeholders) {
            yield from $placeholders->all();
        }
    }
}
