<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Token;

use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use Haspadar\Piqule\Token\InfectionToken;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InfectionTokenTest extends TestCase
{
    #[Test]
    public function enabledWhenInfectionEnabled(): void
    {
        self::assertSame(
            true,
            (new InfectionToken())->enabled(new FakeConfig(['infection.enabled' => [true]])),
            'InfectionToken must be enabled when infection.enabled is true',
        );
    }

    #[Test]
    public function enabledWhenKeyAbsent(): void
    {
        self::assertSame(
            true,
            (new InfectionToken())->enabled(new FakeConfig([])),
            'InfectionToken must be enabled when infection.enabled key is absent',
        );
    }

    #[Test]
    public function disabledWhenInfectionDisabled(): void
    {
        self::assertSame(
            false,
            (new InfectionToken())->enabled(new FakeConfig(['infection.enabled' => [false]])),
            'InfectionToken must be disabled when infection.enabled is false',
        );
    }

    #[Test]
    public function returnsCorrectSecret(): void
    {
        self::assertSame(
            'STRYKER_DASHBOARD_API_KEY',
            (new InfectionToken())->secret(),
            'InfectionToken secret must be STRYKER_DASHBOARD_API_KEY',
        );
    }

    #[Test]
    public function returnsUrlIgnoringOrg(): void
    {
        self::assertSame(
            'https://dashboard.stryker-mutator.io',
            (new InfectionToken())->url('acme'),
            'InfectionToken url must be the Stryker dashboard url',
        );
    }
}
