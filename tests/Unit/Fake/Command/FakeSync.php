<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Command;

use Haspadar\Piqule\Target\Sync\Sync;

final class FakeSync implements Sync
{
    private bool $ran = false;

    public function apply(): void
    {
        $this->ran = true;
    }

    public function isRan(): bool
    {
        return $this->ran;
    }
}
