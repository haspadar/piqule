<?php
declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Placeholders;

use Haspadar\Piqule\Placeholder\Placeholder;
use Haspadar\Piqule\Placeholders\Placeholders;

final readonly class FakePlaceholders implements Placeholders
{
    /**
     * @param iterable<Placeholder> $placeholders
     */
    public function __construct(
        private iterable $placeholders,
    ) {}

    public function all(): iterable
    {
        return $this->placeholders;
    }
}