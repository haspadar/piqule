<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\DefaultConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultConfigTest extends TestCase
{
    #[Test]
    public function returnsTrueWhenKeyIsDeclared(): void
    {
        self::assertTrue(
            (new DefaultConfig())->has('shellcheck.shell'),
            'DefaultConfig must report declared keys as present',
        );
    }

    #[Test]
    public function returnsFalseWhenKeyIsNotDeclared(): void
    {
        self::assertFalse(
            (new DefaultConfig())->has('unknown.key'),
            'DefaultConfig must report unknown keys as absent',
        );
    }

    #[Test]
    public function returnsScalarDefaultAsListWhenKeyDeclared(): void
    {
        self::assertSame(
            ['bash'],
            (new DefaultConfig())->list('shellcheck.shell'),
            'scalar default must be wrapped in a list',
        );
    }

    #[Test]
    public function returnsListDefaultWhenKeyDeclared(): void
    {
        self::assertSame(
            ['../../tests/Unit'],
            (new DefaultConfig())->list('phpunit.testsuites.unit'),
            'list default must be returned as-is',
        );
    }

    #[Test]
    public function returnsNumericCoverageDefaultAsListWhenKeyDeclared(): void
    {
        self::assertSame(
            [80],
            (new DefaultConfig())->list('coverage.project.target'),
            'numeric coverage default must be wrapped in a list without percent sign',
        );
    }

    #[Test]
    public function returnsEmptyListWhenKeyIsUnknown(): void
    {
        self::assertSame(
            [],
            (new DefaultConfig())->list('unknown.key'),
            'DefaultConfig must return empty list for unknown keys without validation',
        );
    }

    #[Test]
    public function returnsTrueForToolEnabledByDefault(): void
    {
        self::assertSame(
            [true],
            (new DefaultConfig())->list('hadolint.enabled'),
            'hadolint must be enabled by default',
        );
    }

    #[Test]
    public function returnsFalseForRenovateEnabledByDefault(): void
    {
        self::assertSame(
            [false],
            (new DefaultConfig())->list('renovate.enabled'),
            'renovate must be disabled by default',
        );
    }
}
