<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\TyposSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TyposSectionTest extends TestCase
{
    #[Test]
    public function convertsExcludesToTrailingSlashPatterns(): void
    {
        self::assertSame(
            ['vendor/', '.git/'],
            (new TyposSection(['vendor', '.git']))->toArray()['typos.exclude'],
            'typos.exclude must use trailing slash patterns from dirs.exclude',
        );
    }

    #[Test]
    public function enablesTyposByDefault(): void
    {
        self::assertSame(
            true,
            (new TyposSection([]))->toArray()['typos.enabled'],
            'typos.enabled must default to true',
        );
    }
}
