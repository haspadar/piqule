<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpMetricsSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpMetricsSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesAndExcludes(): void
    {
        $section = new PhpMetricsSection(['../../src'], ['vendor', '.git']);

        self::assertSame(
            ['vendor', '.git'],
            $section->toArray()['phpmetrics.excludes'],
            'phpmetrics.excludes must reflect dirs.exclude',
        );
    }
}
