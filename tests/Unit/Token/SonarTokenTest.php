<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Token;

use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use Haspadar\Piqule\Token\SonarToken;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SonarTokenTest extends TestCase
{
    #[Test]
    public function enabledWhenSonarEnabled(): void
    {
        self::assertSame(
            true,
            (new SonarToken())->enabled(new FakeConfig(['sonar.enabled' => [true]])),
            'SonarToken must be enabled when sonar.enabled is true',
        );
    }

    #[Test]
    public function enabledWhenKeyAbsent(): void
    {
        self::assertSame(
            true,
            (new SonarToken())->enabled(new FakeConfig([])),
            'SonarToken must be enabled when sonar.enabled key is absent',
        );
    }

    #[Test]
    public function disabledWhenSonarDisabled(): void
    {
        self::assertSame(
            false,
            (new SonarToken())->enabled(new FakeConfig(['sonar.enabled' => [false]])),
            'SonarToken must be disabled when sonar.enabled is false',
        );
    }

    #[Test]
    public function disabledWhenSonarDisabledAsString(): void
    {
        self::assertSame(
            false,
            (new SonarToken())->enabled(new FakeConfig(['sonar.enabled' => ['false']])),
            'SonarToken must be disabled when sonar.enabled is string "false"',
        );
    }

    #[Test]
    public function returnsCorrectSecret(): void
    {
        self::assertSame(
            'SONAR_TOKEN',
            (new SonarToken())->secret(),
            'SonarToken secret must be SONAR_TOKEN',
        );
    }

    #[Test]
    public function returnsUrl(): void
    {
        self::assertSame(
            'https://sonarcloud.io/account/security',
            (new SonarToken())->url('acme'),
            'SonarToken url must point to SonarCloud security page',
        );
    }
}
