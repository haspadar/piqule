<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PsalmSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PsalmSectionTest extends TestCase
{
    #[Test]
    public function convertsExcludesToProjectIgnoreWithRelativePath(): void
    {
        self::assertSame(
            ['../../vendor', '../../.git'],
            (new PsalmSection(['../../src'], ['vendor', '.git']))->toArray()['psalm.project.ignore'],
            'psalm.project.ignore must prefix dirs.exclude with ../../',
        );
    }
}
