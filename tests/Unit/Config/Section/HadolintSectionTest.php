<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\HadolintSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class HadolintSectionTest extends TestCase
{
    #[Test]
    public function propagatesExcludesToIgnore(): void
    {
        self::assertSame(
            ['vendor', '.git'],
            (new HadolintSection(['vendor', '.git']))->toArray()['hadolint.ignore'],
            'hadolint.ignore must reflect dirs.exclude',
        );
    }

    #[Test]
    public function setsFailureThresholdToError(): void
    {
        self::assertSame(
            'error',
            (new HadolintSection([]))->toArray()['hadolint.failure_threshold'],
            'hadolint.failure_threshold must default to error',
        );
    }

    #[Test]
    public function setsIgnoredYamlToEmptyJsonList(): void
    {
        self::assertSame(
            '[]',
            (new HadolintSection([]))->toArray()['hadolint.ignored_yaml'],
            'hadolint.ignored_yaml must default to empty JSON list',
        );
    }

    #[Test]
    public function setsOverrideErrorYamlToEmptyJsonList(): void
    {
        self::assertSame(
            '[]',
            (new HadolintSection([]))->toArray()['hadolint.override.error_yaml'],
            'hadolint.override.error_yaml must default to empty JSON list',
        );
    }

    #[Test]
    public function setsOverrideWarningYamlToEmptyJsonList(): void
    {
        self::assertSame(
            '[]',
            (new HadolintSection([]))->toArray()['hadolint.override.warning_yaml'],
            'hadolint.override.warning_yaml must default to empty JSON list',
        );
    }

    #[Test]
    public function setsPatternsToDockerfileGlob(): void
    {
        self::assertSame(
            ['Dockerfile*'],
            (new HadolintSection([]))->toArray()['hadolint.patterns'],
            'hadolint.patterns must default to Dockerfile*',
        );
    }

    #[Test]
    public function enablesHadolintByDefault(): void
    {
        self::assertSame(
            true,
            (new HadolintSection([]))->toArray()['hadolint.enabled'],
            'hadolint.enabled must default to true',
        );
    }
}
