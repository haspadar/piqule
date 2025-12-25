<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Command;

use Haspadar\Piqule\Target\Command\Command;

final class FakeCommand implements Command
{
    private bool $ran = false;

    public function run(): void
    {
        $this->ran = true;
    }

    public function isRan(): bool
    {
        return $this->ran;
    }
}
