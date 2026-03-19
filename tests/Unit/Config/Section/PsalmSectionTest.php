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

    #[Test]
    public function propagatesIncludesToProjectDirectories(): void
    {
        self::assertSame(
            ['../../src'],
            (new PsalmSection(['../../src'], []))->toArray()['psalm.project.directories'],
            'psalm.project.directories must reflect dirs.include',
        );
    }

    #[Test]
    public function setsErrorLevelTo1(): void
    {
        self::assertSame(
            [1],
            (new PsalmSection([], []))->toArray()['psalm.error_level'],
            'psalm.error_level must default to 1',
        );
    }

    #[Test]
    public function enablesPsalmByDefault(): void
    {
        self::assertSame(
            true,
            (new PsalmSection([], []))->toArray()['psalm.enabled'],
            'psalm.enabled must default to true',
        );
    }
}
