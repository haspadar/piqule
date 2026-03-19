<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\CoverageSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CoverageSectionTest extends TestCase
{
    #[Test]
    public function setsProjectTargetTo80ByDefault(): void
    {
        self::assertSame(
            80,
            (new CoverageSection())->toArray()['coverage.project.target'],
            'coverage.project.target must default to 80',
        );
    }

    #[Test]
    public function setsPatchTargetTo80(): void
    {
        self::assertSame(
            80,
            (new CoverageSection())->toArray()['coverage.patch.target'],
            'coverage.patch.target must default to 80',
        );
    }

    #[Test]
    public function setsPatchThresholdTo5(): void
    {
        self::assertSame(
            5,
            (new CoverageSection())->toArray()['coverage.patch.threshold'],
            'coverage.patch.threshold must default to 5',
        );
    }

    #[Test]
    public function setsProjectThresholdTo2(): void
    {
        self::assertSame(
            2,
            (new CoverageSection())->toArray()['coverage.project.threshold'],
            'coverage.project.threshold must default to 2',
        );
    }
}
