<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Secret;

use Haspadar\Piqule\Secret\CodecovSecret;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CodecovSecretTest extends TestCase
{
    #[Test]
    public function enabledWhenPhpUnitEnabled(): void
    {
        self::assertSame(
            true,
            (new CodecovSecret())->enabled(new FakeConfig(['phpunit.cli' => [true]])),
            'CodecovSecret must be enabled when phpunit.cli is true',
        );
    }

    #[Test]
    public function enabledWhenKeyAbsent(): void
    {
        self::assertSame(
            true,
            (new CodecovSecret())->enabled(new FakeConfig([])),
            'CodecovSecret must be enabled when phpunit.cli key is absent',
        );
    }

    #[Test]
    public function disabledWhenPhpUnitDisabled(): void
    {
        self::assertSame(
            false,
            (new CodecovSecret())->enabled(new FakeConfig(['phpunit.cli' => [false]])),
            'CodecovSecret must be disabled when phpunit.cli is false',
        );
    }

    #[Test]
    public function returnsCorrectName(): void
    {
        self::assertSame(
            'CODECOV_TOKEN',
            (new CodecovSecret())->name(),
            'CodecovSecret name must be CODECOV_TOKEN',
        );
    }

    #[Test]
    public function returnsUrlWithOrg(): void
    {
        self::assertSame(
            'https://app.codecov.io/account/gh/acme/repositories',
            (new CodecovSecret())->url('acme'),
            'CodecovSecret url must include the org name',
        );
    }
}
