<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Envs;

use Haspadar\Piqule\Envs\EmptyEnvs;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EmptyEnvsTest extends TestCase
{
    #[Test]
    public function returnsEmptyArray(): void
    {
        self::assertSame(
            [],
            (new EmptyEnvs())->vars(),
            'EmptyEnvs must return an empty array',
        );
    }
}
