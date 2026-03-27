<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpStanSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpStanSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToPaths(): void
    {
        self::assertSame(
            ['../../src'],
            (new PhpStanSection(['../../src']))->toArray()['phpstan.paths'],
            'phpstan.paths must reflect the given includes',
        );
    }

    #[Test]
    public function setsAnalysisLevelTo9(): void
    {
        self::assertSame(
            [9],
            (new PhpStanSection([]))->toArray()['phpstan.level'],
            'phpstan.level must default to 9',
        );
    }

    #[Test]
    public function setsMemoryLimitTo1G(): void
    {
        self::assertSame(
            '1G',
            (new PhpStanSection([]))->toArray()['phpstan.memory'],
            'phpstan.memory must default to 1G',
        );
    }

    #[Test]
    public function enablesPhpStanByDefault(): void
    {
        self::assertSame(
            true,
            (new PhpStanSection([]))->toArray()['phpstan.enabled'],
            'phpstan.enabled must default to true',
        );
    }

    #[Test]
    public function defaultsNeonIncludesToStrictRules(): void
    {
        self::assertSame(
            ['../../vendor/phpstan/phpstan-strict-rules/rules.neon'],
            (new PhpStanSection([]))->toArray()['phpstan.neon_includes'],
            'phpstan.neon_includes must default to strict-rules extension',
        );
    }

    #[Test]
    public function defaultsCheckedExceptionsToThrowable(): void
    {
        self::assertSame(
            ['\Throwable'],
            (new PhpStanSection([]))->toArray()['phpstan.checked_exceptions'],
            'phpstan.checked_exceptions must default to [\Throwable]',
        );
    }
}
