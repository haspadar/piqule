<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\OverrideConfig;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

final class OverrideConfigTest extends TestCase
{
    #[Test]
    public function hasRecognizesDeclaredCiMatrix(): void
    {
        self::assertTrue(
            (new OverrideConfig(
                new FakeConfig(['ci.php.matrix' => ['8.3']]),
                ['ci.php.matrix' => '8.4'],
            ))->has('ci.php.matrix'),
        );
    }

    #[Test]
    public function hasReturnsFalseForUnknownToolKey(): void
    {
        self::assertFalse(
            (new OverrideConfig(
                new FakeConfig(['phpstan.level' => ['8']]),
                ['phpstan.level' => '9'],
            ))->has('phpstan.memory'),
        );
    }

    #[Test]
    public function returnsDefaultPhpstanLevel(): void
    {
        self::assertSame(
            ['8'],
            (new OverrideConfig(
                new FakeConfig(['phpstan.level' => ['8']]),
                [],
            ))->list('phpstan.level'),
        );
    }

    #[Test]
    public function overridesShellcheckSeverity(): void
    {
        self::assertSame(
            ['error'],
            (new OverrideConfig(
                new FakeConfig(['shellcheck.severity' => ['warning']]),
                ['shellcheck.severity' => 'error'],
            ))->list('shellcheck.severity'),
        );
    }

    #[Test]
    public function overridesPhpunitTestsuitesWithList(): void
    {
        self::assertSame(
            ['Unit', 'Integration'],
            (new OverrideConfig(
                new FakeConfig(['phpunit.testsuites.unit' => ['Unit']]),
                ['phpunit.testsuites.unit' => ['Unit', 'Integration']],
            ))->list('phpunit.testsuites.unit'),
        );
    }

    #[Test]
    public function throwsWhenListCalledForUndeclaredKey(): void
    {
        $this->expectException(PiquleException::class);

        (new OverrideConfig(
            new FakeConfig([]),
            [],
        ))->list('phpmetrics.size.max_loc_per_class');
    }

    #[Test]
    public function wrapsJsonlintCompactBooleanIntoList(): void
    {
        self::assertSame(
            [true],
            (new OverrideConfig(
                new FakeConfig(['jsonlint.compact' => [false]]),
                ['jsonlint.compact' => true],
            ))->list('jsonlint.compact'),
            'Boolean overrides must be normalized to a single-item scalar list.',
        );
    }

    #[Test]
    public function throwsWhenYamlOverrideIsAssociative(): void
    {
        $this->expectException(PiquleException::class);

        (new OverrideConfig(
            new FakeConfig(['hadolint.override.error_yaml' => []]),
            ['hadolint.override.error_yaml' => ['DL3008' => 'ignore']],
        ))->list('hadolint.override.error_yaml');
    }

    #[Test]
    public function throwsWhenMutationTimeoutContainsObject(): void
    {
        $this->expectException(PiquleException::class);

        (new OverrideConfig(
            new FakeConfig(['infection.timeout' => []]),
            ['infection.timeout' => [new stdClass()]],
        ))->list('infection.timeout');
    }

    #[Test]
    public function throwsWhenOverrideIsObject(): void
    {
        $this->expectException(PiquleException::class);

        (new OverrideConfig(
            new FakeConfig(['jsonlint.mode' => []]),
            ['jsonlint.mode' => new stdClass()],
        ))->list('jsonlint.mode');
    }
}
