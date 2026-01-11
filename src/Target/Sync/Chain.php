<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Sync;

use Haspadar\Piqule\Target\Storage\TargetStorage;
use Override;

final readonly class Chain implements Sync
{
    /** @param Sync[] $syncs */
    public function __construct(
        private array $syncs,
    ) {
    }

    #[Override]
    public function apply(TargetStorage $targetStorage): void
    {
        foreach ($this->syncs as $sync) {
            $sync->apply($targetStorage);
        }
    }
}
