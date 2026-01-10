<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Sync;

interface Sync
{
    public function apply(): void;
}
