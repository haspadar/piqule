<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpUnitSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpUnitSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToSourceInclude(): void
    {
        self::assertSame(
            ['../../src'],
            (new PhpUnitSection(['../../src']))->toArray()['phpunit.source.include'],
            'phpunit.source.include must reflect the given includes',
        );
    }
}
