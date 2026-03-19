<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\CiSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CiSectionTest extends TestCase
{
    #[Test]
    public function propagatesMatrixVersionsIndependentlyFromTestVersion(): void
    {
        $section = new CiSection(['8.3', '8.4', '8.5'], ['8.3']);

        self::assertSame(
            ['8.3', '8.4', '8.5'],
            $section->toArray()['ci.php.matrix'],
            'ci.php.matrix must reflect the given matrix versions',
        );
    }

    #[Test]
    public function propagatesTestVersionIndependentlyFromMatrix(): void
    {
        $section = new CiSection(['8.3', '8.4', '8.5'], ['8.3']);

        self::assertSame(
            ['8.3'],
            $section->toArray()['ci.php.test_version'],
            'ci.php.test_version must reflect the given test version independently',
        );
    }

    #[Test]
    public function setsMaxLinesChangedTo250(): void
    {
        self::assertSame(
            250,
            (new CiSection([], []))->toArray()['ci.pr.max_lines_changed'],
            'ci.pr.max_lines_changed must default to 250',
        );
    }
}
