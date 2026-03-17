<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpMdSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpMdSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToPaths(): void
    {
        self::assertSame(
            ['src'],
            (new PhpMdSection(['src']))->toArray()['phpmd.paths'],
            'phpmd.paths must reflect the given includes',
        );
    }
}
