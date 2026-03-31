<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Token;

use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use Haspadar\Piqule\Token\CodecovToken;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CodecovTokenTest extends TestCase
{
    #[Test]
    public function enabledWhenPhpUnitEnabled(): void
    {
        self::assertSame(
            true,
            (new CodecovToken())->enabled(new FakeConfig(['phpunit.enabled' => [true]])),
            'CodecovToken must be enabled when phpunit.enabled is true',
        );
    }

    #[Test]
    public function enabledWhenKeyAbsent(): void
    {
        self::assertSame(
            true,
            (new CodecovToken())->enabled(new FakeConfig([])),
            'CodecovToken must be enabled when phpunit.enabled key is absent',
        );
    }

    #[Test]
    public function disabledWhenPhpUnitDisabled(): void
    {
        self::assertSame(
            false,
            (new CodecovToken())->enabled(new FakeConfig(['phpunit.enabled' => [false]])),
            'CodecovToken must be disabled when phpunit.enabled is false',
        );
    }

    #[Test]
    public function returnsCorrectSecret(): void
    {
        self::assertSame(
            'CODECOV_TOKEN',
            (new CodecovToken())->secret(),
            'CodecovToken secret must be CODECOV_TOKEN',
        );
    }

    #[Test]
    public function returnsUrlWithOrg(): void
    {
        self::assertSame(
            'https://app.codecov.io/account/gh/acme/repositories',
            (new CodecovToken())->url('acme'),
            'CodecovToken url must include the org name',
        );
    }
}
