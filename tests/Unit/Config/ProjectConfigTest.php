<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\ProjectConfig;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProjectConfigTest extends TestCase
{
    #[Test]
    public function loadsDefaultsWhenNoProjectConfigExists(): void
    {
        $folder = new TempFolder();

        try {
            self::assertTrue(
                (new ProjectConfig($folder->path()))->has('phpstan.level'),
                'ProjectConfig must load defaults when no .piqule.yaml exists',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function loadsOverridesFromPiquleYaml(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    phpstan.level: 5",
        );

        try {
            self::assertSame(
                [5],
                (new ProjectConfig($folder->path()))->list('phpstan.level'),
                'ProjectConfig must apply overrides from .piqule.yaml',
            );
        } finally {
            $folder->close();
        }
    }
}
