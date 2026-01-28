<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Application;

use Haspadar\Piqule\Application\Application;

final readonly class FakeApplication implements Application
{
    /**
     * Does nothing
     */
    public function run(): void {}
}
