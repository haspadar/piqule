<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\CiSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CiSectionTest extends TestCase
{
    #[Test]
    public function propagatesPhpVersions(): void
    {
        self::assertSame(
            ['8.3', '8.4', '8.5'],
            (new CiSection(['8.3', '8.4', '8.5']))->toArray()['php.versions'],
            'php.versions must reflect the given versions list',
        );
    }

    #[Test]
    public function setsMaxLinesChangedTo250(): void
    {
        self::assertSame(
            250,
            (new CiSection([]))->toArray()['ci.pr.max_lines_changed'],
            'ci.pr.max_lines_changed must default to 250',
        );
    }
}
