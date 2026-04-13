<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\ProjectConfig;
use Haspadar\Piqule\PiquleException;
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

    #[Test]
    public function loadsConfigFromPiqulePhp(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.php',
            <<<'PHP'
            <?php
            return new \Haspadar\Piqule\Tests\Fake\Config\FakeConfig([
                'custom.key' => ['custom-value'],
            ]);
            PHP,
        );

        try {
            self::assertSame(
                ['custom-value'],
                (new ProjectConfig($folder->path()))->list('custom.key'),
                'ProjectConfig must load config from .piqule.php when .piqule.yaml is absent',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function throwsWhenPiqulePhpReturnsNonConfig(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.php',
            '<?php return "not a config";',
        );

        try {
            $this->expectException(PiquleException::class);
            (new ProjectConfig($folder->path()))->has('any');
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function returnsConfigAsArray(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.php',
            <<<'PHP'
            <?php
            return new \Haspadar\Piqule\Tests\Fake\Config\FakeConfig([
                'custom.key' => ['custom-value'],
            ]);
            PHP,
        );

        try {
            self::assertSame(
                ['custom.key' => ['custom-value']],
                (new ProjectConfig($folder->path()))->toArray(),
                'ProjectConfig must return the resolved config as array',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function returnsCachedConfigOnSecondCall(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    phpstan.level: 5",
        );

        try {
            $config = new ProjectConfig($folder->path());
            $config->has('phpstan.level');

            file_put_contents(
                $folder->path() . '/.piqule.yaml',
                "override:\n    phpstan.level: 9",
            );

            self::assertSame(
                [5],
                $config->list('phpstan.level'),
                'ProjectConfig must return cached value even after file changes',
            );
        } finally {
            $folder->close();
        }
    }
}
