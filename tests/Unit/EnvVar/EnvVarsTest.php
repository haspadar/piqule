<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\EnvVar;

use Haspadar\Piqule\EnvVar\EnvVars;
use Haspadar\Piqule\EnvVar\SonarEnvVar;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EnvVarsTest extends TestCase
{
    #[Test]
    public function returnsAllItems(): void
    {
        $sonar = new SonarEnvVar();

        self::assertSame(
            [$sonar],
            (new EnvVars([$sonar]))->items(),
            'EnvVars must return all items in order',
        );
    }

    #[Test]
    public function returnsEmptyListWhenNoItems(): void
    {
        self::assertSame(
            [],
            (new EnvVars([]))->items(),
            'EnvVars must return empty list when constructed with no items',
        );
    }
}
