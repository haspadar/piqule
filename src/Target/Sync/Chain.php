<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Sync;

use Haspadar\Piqule\Target\Storage\TargetStorage;

final readonly class Chain implements Sync
{
    /** @param Sync[] $syncs */
    public function __construct(
        private array $syncs,
    ) {}

    public function apply(TargetStorage $targetStorage): void
    {
        foreach ($this->syncs as $sync) {
            $sync->apply($targetStorage);
        }
    }
}
