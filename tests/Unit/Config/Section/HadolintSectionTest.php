<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\HadolintSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class HadolintSectionTest extends TestCase
{
    #[Test]
    public function propagatesExcludesToIgnore(): void
    {
        self::assertSame(
            ['vendor', '.git'],
            (new HadolintSection(['vendor', '.git']))->toArray()['hadolint.ignore'],
            'hadolint.ignore must reflect dirs.exclude',
        );
    }
}
