<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\InfectionSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InfectionSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToSourceDirectories(): void
    {
        self::assertSame(
            ['../../src'],
            (new InfectionSection(['../../src']))->toArray()['infection.source.directories'],
            'infection.source.directories must reflect the given includes',
        );
    }
}
