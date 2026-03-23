<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config\Section;

use Haspadar\Piqule\Config\Section\SonarSection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SonarSectionTest extends TestCase
{
    #[Test]
    public function propagatesIncludesToSources(): void
    {
        self::assertSame(
            ['../../src'],
            (new SonarSection(['../../src'], []))->toArray()['sonar.sources'],
            'sonar.sources must reflect the given includes',
        );
    }

    #[Test]
    public function propagatesExcludesToCpdExclusions(): void
    {
        self::assertSame(
            ['vendor/**', 'tests/**'],
            (new SonarSection([], ['vendor/**', 'tests/**']))->toArray()['sonar.cpd.exclusions'],
            'sonar.cpd.exclusions must reflect the given excludes',
        );
    }

    #[Test]
    public function propagatesExcludesToCoverageExclusions(): void
    {
        self::assertSame(
            ['vendor/**', 'tests/**'],
            (new SonarSection([], ['vendor/**', 'tests/**']))->toArray()['sonar.coverage.exclusions'],
            'sonar.coverage.exclusions must reflect the given excludes',
        );
    }

    #[Test]
    public function setsTestsToDefaultPath(): void
    {
        self::assertSame(
            ['../../tests'],
            (new SonarSection([], []))->toArray()['sonar.tests'],
            'sonar.tests must default to ../../tests',
        );
    }

    #[Test]
    public function enablesSonarByDefault(): void
    {
        self::assertSame(
            true,
            (new SonarSection([], []))->toArray()['sonar.enabled'],
            'sonar.enabled must default to true',
        );
    }
}
