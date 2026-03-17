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
}
