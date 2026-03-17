<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpCsSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpCsSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesAndExcludes(): void
    {
        $section = new PhpCsSection(['../../src'], ['vendor/*']);

        self::assertSame(
            ['../../src'],
            $section->toArray()['phpcs.files'],
            'phpcs.files must reflect the given includes',
        );
    }
}
