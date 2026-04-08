<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Secret;

use Haspadar\Piqule\Secret\InfectionSecret;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InfectionSecretTest extends TestCase
{
    #[Test]
    public function enabledWhenInfectionEnabled(): void
    {
        self::assertSame(
            true,
            (new InfectionSecret())->enabled(new FakeConfig(['infection.cli' => [true]])),
            'InfectionSecret must be enabled when infection.cli is true',
        );
    }

    #[Test]
    public function enabledWhenKeyAbsent(): void
    {
        self::assertSame(
            true,
            (new InfectionSecret())->enabled(new FakeConfig([])),
            'InfectionSecret must be enabled when infection.cli key is absent',
        );
    }

    #[Test]
    public function disabledWhenInfectionDisabled(): void
    {
        self::assertSame(
            false,
            (new InfectionSecret())->enabled(new FakeConfig(['infection.cli' => [false]])),
            'InfectionSecret must be disabled when infection.cli is false',
        );
    }

    #[Test]
    public function returnsCorrectName(): void
    {
        self::assertSame(
            'STRYKER_DASHBOARD_API_KEY',
            (new InfectionSecret())->name(),
            'InfectionSecret name must be STRYKER_DASHBOARD_API_KEY',
        );
    }

    #[Test]
    public function returnsUrl(): void
    {
        self::assertSame(
            'https://dashboard.stryker-mutator.io',
            (new InfectionSecret())->url('acme'),
            'InfectionSecret url must point to Stryker dashboard',
        );
    }
}
