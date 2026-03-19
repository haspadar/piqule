<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\YamllintSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class YamllintSectionTest extends TestCase
{
    #[Test]
    public function mergesExcludesWithPiquleInternalPaths(): void
    {
        self::assertSame(
            ['vendor/**', '.git/**', '.piqule/**/html/**', '.piqule/**/coverage-report/**'],
            (new YamllintSection(['vendor', '.git']))->toArray()['yamllint.ignore'],
            'yamllint.ignore must combine dirs.exclude glob patterns with .piqule internal paths',
        );
    }

    #[Test]
    public function enablesYamllintByDefault(): void
    {
        self::assertSame(
            true,
            (new YamllintSection([]))->toArray()['yamllint.enabled'],
            'yamllint.enabled must default to true',
        );
    }

    #[Test]
    public function setsLineLengthMaxTo120(): void
    {
        self::assertSame(
            [120],
            (new YamllintSection([]))->toArray()['yamllint.line_length.max'],
            'yamllint.line_length.max must default to 120',
        );
    }
}
