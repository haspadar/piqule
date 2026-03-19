<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\PhpMetricsSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpMetricsSectionTest extends TestCase
{
    #[Test]
    public function propagatesExcludes(): void
    {
        $section = new PhpMetricsSection(['../../src'], ['vendor', '.git']);

        self::assertSame(
            ['vendor', '.git'],
            $section->toArray()['phpmetrics.excludes'],
            'phpmetrics.excludes must reflect dirs.exclude',
        );
    }

    #[Test]
    public function propagatesIncludes(): void
    {
        self::assertSame(
            ['src'],
            (new PhpMetricsSection(['src'], []))->toArray()['phpmetrics.includes'],
            'phpmetrics.includes must reflect dirs.include',
        );
    }

    #[Test]
    public function setsMaxCyclomaticPerMethodTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.complexity.max_cyclomatic_per_method'],
            'phpmetrics.complexity.max_cyclomatic_per_method must default to 10',
        );
    }

    #[Test]
    public function setsMaxWeightedMethodsPerClassTo20(): void
    {
        self::assertSame(
            [20],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.complexity.max_weighted_methods_per_class'],
            'phpmetrics.complexity.max_weighted_methods_per_class must default to 20',
        );
    }

    #[Test]
    public function setsMaxAfferentTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.coupling.max_afferent'],
            'phpmetrics.coupling.max_afferent must default to 10',
        );
    }

    #[Test]
    public function setsMaxEfferentTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.coupling.max_efferent'],
            'phpmetrics.coupling.max_efferent must default to 10',
        );
    }

    #[Test]
    public function setsExtensionsToPhp(): void
    {
        self::assertSame(
            ['php'],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.extensions'],
            'phpmetrics.extensions must default to php',
        );
    }

    #[Test]
    public function setsMaxBugsPerMethodToHalfPoint(): void
    {
        self::assertSame(
            [0.5],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.halstead.max_bugs_per_method'],
            'phpmetrics.halstead.max_bugs_per_method must default to 0.5',
        );
    }

    #[Test]
    public function setsMaxDifficultyPerMethodTo15(): void
    {
        self::assertSame(
            [15],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.halstead.max_difficulty_per_method'],
            'phpmetrics.halstead.max_difficulty_per_method must default to 15',
        );
    }

    #[Test]
    public function setsMaxEffortPerMethodTo15000(): void
    {
        self::assertSame(
            [15000],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.halstead.max_effort_per_method'],
            'phpmetrics.halstead.max_effort_per_method must default to 15000',
        );
    }

    #[Test]
    public function setsMaxVolumePerMethodTo1000(): void
    {
        self::assertSame(
            [1000],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.halstead.max_volume_per_method'],
            'phpmetrics.halstead.max_volume_per_method must default to 1000',
        );
    }

    #[Test]
    public function setsMaxInheritanceDepthTo3(): void
    {
        self::assertSame(
            [3],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.inheritance.max_depth'],
            'phpmetrics.inheritance.max_depth must default to 3',
        );
    }

    #[Test]
    public function setsHtmlReportFormatToHtml(): void
    {
        self::assertSame(
            ['html'],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.report.html'],
            'phpmetrics.report.html must default to html',
        );
    }

    #[Test]
    public function setsJsonReportPathToPhpmetricsJson(): void
    {
        self::assertSame(
            ['phpmetrics.json'],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.report.json'],
            'phpmetrics.report.json must default to phpmetrics.json',
        );
    }

    #[Test]
    public function setsMaxLocPerClassTo1000(): void
    {
        self::assertSame(
            [1000],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.size.max_loc_per_class'],
            'phpmetrics.size.max_loc_per_class must default to 1000',
        );
    }

    #[Test]
    public function setsMaxLogicalLocPerClassTo600(): void
    {
        self::assertSame(
            [600],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.size.max_logical_loc_per_class'],
            'phpmetrics.size.max_logical_loc_per_class must default to 600',
        );
    }

    #[Test]
    public function setsMaxLogicalLocPerMethodTo20(): void
    {
        self::assertSame(
            [20],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.size.max_logical_loc_per_method'],
            'phpmetrics.size.max_logical_loc_per_method must default to 20',
        );
    }

    #[Test]
    public function setsMaxMethodsPerClassTo10(): void
    {
        self::assertSame(
            [10],
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.structure.max_methods_per_class'],
            'phpmetrics.structure.max_methods_per_class must default to 10',
        );
    }

    #[Test]
    public function enablesPhpMetricsByDefault(): void
    {
        self::assertSame(
            true,
            (new PhpMetricsSection([], []))->toArray()['phpmetrics.enabled'],
            'phpmetrics.enabled must default to true',
        );
    }
}
