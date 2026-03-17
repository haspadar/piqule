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
}
