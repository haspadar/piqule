<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Check;

use Haspadar\Piqule\Check\CliOption;

final readonly class FakeCliOption implements CliOption
{
    public function __construct(private bool $enabled) {}

    public function enabled(): bool
    {
        return $this->enabled;
    }
}
