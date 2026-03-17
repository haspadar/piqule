<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\AppendConfig;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

final class AppendConfigTest extends TestCase
{
    #[Test]
    public function returnsTrueWhenKeyIsDeclared(): void
    {
        self::assertTrue(
            (new AppendConfig(
                new FakeConfig(['phpstan.paths' => ['../../src']]),
                [],
            ))->has('phpstan.paths'),
            'AppendConfig must report a declared key as present',
        );
    }

    #[Test]
    public function returnsFalseWhenKeyIsNotDeclared(): void
    {
        self::assertFalse(
            (new AppendConfig(
                new FakeConfig(['phpstan.paths' => ['../../src']]),
                [],
            ))->has('phpstan.memory'),
            'AppendConfig must report an undeclared key as absent',
        );
    }

    #[Test]
    public function returnsOriginValueWhenKeyIsNotInAppends(): void
    {
        self::assertSame(
            ['../../src'],
            (new AppendConfig(
                new FakeConfig(['phpstan.paths' => ['../../src']]),
                [],
            ))->list('phpstan.paths'),
            'AppendConfig must return the origin value when the key has no appends',
        );
    }

    #[Test]
    public function appendsScalarToList(): void
    {
        self::assertSame(
            ['../../src', '../../app'],
            (new AppendConfig(
                new FakeConfig(['phpstan.paths' => ['../../src']]),
                ['phpstan.paths' => '../../app'],
            ))->list('phpstan.paths'),
            'AppendConfig must append a scalar value to the origin list',
        );
    }

    #[Test]
    public function appendsListToList(): void
    {
        self::assertSame(
            ['../../src', '../../app', '../../lib'],
            (new AppendConfig(
                new FakeConfig(['phpstan.paths' => ['../../src']]),
                ['phpstan.paths' => ['../../app', '../../lib']],
            ))->list('phpstan.paths'),
            'AppendConfig must append a list value to the origin list',
        );
    }

    #[Test]
    public function deduplicatesWhenAppendedValueAlreadyExists(): void
    {
        self::assertSame(
            ['../../src', '../../app'],
            (new AppendConfig(
                new FakeConfig(['phpstan.paths' => ['../../src']]),
                ['phpstan.paths' => ['../../src', '../../app']],
            ))->list('phpstan.paths'),
            'AppendConfig must not duplicate values already present in the origin list',
        );
    }

    #[Test]
    public function throwsWhenListCalledForUndeclaredKey(): void
    {
        $this->expectException(PiquleException::class);

        (new AppendConfig(
            new FakeConfig([]),
            [],
        ))->list('phpstan.paths');
    }

    #[Test]
    public function throwsWhenAppendIsAssociativeArray(): void
    {
        $this->expectException(PiquleException::class);

        (new AppendConfig(
            new FakeConfig(['hadolint.ignore' => []]),
            ['hadolint.ignore' => ['DL3008' => 'ignore']],
        ))->list('hadolint.ignore');
    }

    #[Test]
    public function throwsWhenAppendContainsObject(): void
    {
        $this->expectException(PiquleException::class);

        (new AppendConfig(
            new FakeConfig(['phpstan.paths' => []]),
            ['phpstan.paths' => [new stdClass()]],
        ))->list('phpstan.paths');
    }
}
