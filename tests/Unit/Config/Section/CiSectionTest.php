<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\CiSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CiSectionTest extends TestCase
{
    #[Test]
    public function propagatesPhpVersionToMatrixAndTestVersion(): void
    {
        $section = new CiSection(['8.4']);

        self::assertSame(
            ['8.4'],
            $section->toArray()['ci.php.matrix'],
            'ci.php.matrix must reflect the given PHP version',
        );
    }

    #[Test]
    public function setsTestVersionToSameAsMatrix(): void
    {
        $section = new CiSection(['8.4']);

        self::assertSame(
            $section->toArray()['ci.php.matrix'],
            $section->toArray()['ci.php.test_version'],
            'ci.php.test_version must match ci.php.matrix',
        );
    }
}
