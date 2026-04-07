<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\EnvVar;

use Haspadar\Piqule\EnvVar\SonarEnvVar;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SonarEnvVarTest extends TestCase
{
    #[Test]
    public function enabledWhenSonarEnabled(): void
    {
        self::assertSame(
            true,
            (new SonarEnvVar())->enabled(new FakeConfig(['sonar.enabled' => [true]])),
            'SonarEnvVar must be enabled when sonar.enabled is true',
        );
    }

    #[Test]
    public function enabledWhenKeyAbsent(): void
    {
        self::assertSame(
            true,
            (new SonarEnvVar())->enabled(new FakeConfig([])),
            'SonarEnvVar must be enabled when sonar.enabled key is absent',
        );
    }

    #[Test]
    public function disabledWhenSonarDisabled(): void
    {
        self::assertSame(
            false,
            (new SonarEnvVar())->enabled(new FakeConfig(['sonar.enabled' => [false]])),
            'SonarEnvVar must be disabled when sonar.enabled is false',
        );
    }

    #[Test]
    public function disabledWhenSonarDisabledAsString(): void
    {
        self::assertSame(
            false,
            (new SonarEnvVar())->enabled(new FakeConfig(['sonar.enabled' => ['false']])),
            'SonarEnvVar must be disabled when sonar.enabled is string "false"',
        );
    }

    #[Test]
    public function enabledWhenInvalidString(): void
    {
        self::assertSame(
            true,
            (new SonarEnvVar())->enabled(new FakeConfig(['sonar.enabled' => ['tru']])),
            'SonarEnvVar must default to enabled when value is not a valid boolean string',
        );
    }

    #[Test]
    public function returnsCorrectName(): void
    {
        self::assertSame(
            'SONAR_TOKEN',
            (new SonarEnvVar())->name(),
            'SonarEnvVar name must be SONAR_TOKEN',
        );
    }

    #[Test]
    public function returnsUrl(): void
    {
        self::assertSame(
            'https://sonarcloud.io/account/security',
            (new SonarEnvVar())->url(),
            'SonarEnvVar url must point to SonarCloud security page',
        );
    }
}
