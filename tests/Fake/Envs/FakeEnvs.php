<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Envs;

use Haspadar\Piqule\Envs\Envs;

final class FakeEnvs implements Envs
{
    /** @param array<string, string> $vars */
    public function __construct(private array $vars) {}

    public function vars(): array
    {
        return $this->vars;
    }
}
