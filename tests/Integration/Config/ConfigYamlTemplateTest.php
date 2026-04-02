<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Config;

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\YamlConfig;
use Haspadar\Piqule\File\ConfiguredFile;
use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

final class ConfigYamlTemplateTest extends TestCase
{
    private static function template(): string
    {
        return (string) file_get_contents(
            dirname(__DIR__, 3) . '/templates/always/.piqule/config.yaml',
        );
    }

    private static function rendered(string $template, \Haspadar\Piqule\Config\Config $config): mixed
    {
        return Yaml::parse(
            (new ConfiguredFile(new TextFile('.piqule/config.yaml', $template), $config))->contents(),
        );
    }

    #[Test]
    public function rendersValidYaml(): void
    {
        self::assertIsArray(
            self::rendered(self::template(), new DefaultConfig()),
            'Rendered config.yaml must be valid YAML',
        );
    }

    #[Test]
    public function renderedYamlContainsDefaultsKey(): void
    {
        self::assertArrayHasKey(
            'defaults',
            self::rendered(self::template(), new DefaultConfig()),
            'Rendered config.yaml must contain a "defaults" key',
        );
    }

    #[Test]
    public function defaultPhpSrcIsSrc(): void
    {
        $parsed = self::rendered(self::template(), new DefaultConfig());

        self::assertSame(['src'], $parsed['defaults']['php.src'], 'php.src default must be ["src"]');
    }

    #[Test]
    public function defaultPhpVersionsIs83(): void
    {
        $parsed = self::rendered(self::template(), new DefaultConfig());

        self::assertSame(['8.3'], $parsed['defaults']['php.versions'], 'php.versions default must be ["8.3"]');
    }

    #[Test]
    public function defaultPhpstanLevelIs9(): void
    {
        $parsed = self::rendered(self::template(), new DefaultConfig());

        self::assertSame(9, $parsed['defaults']['phpstan.level'], 'phpstan.level default must be 9');
    }

    #[Test]
    public function defaultPsalmErrorLevelIs1(): void
    {
        $parsed = self::rendered(self::template(), new DefaultConfig());

        self::assertSame(1, $parsed['defaults']['psalm.error_level'], 'psalm.error_level default must be 1');
    }

    #[Test]
    public function overrideReplacesPhpstanLevel(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    phpstan.level: 7\n",
        );

        try {
            $parsed = self::rendered(
                self::template(),
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
            );

            self::assertSame(7, $parsed['defaults']['phpstan.level'], 'Override must replace phpstan.level with 7');
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function appendAddsValueToExclude(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    exclude:\n        - legacy\n",
        );

        try {
            $parsed = self::rendered(
                self::template(),
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
            );

            self::assertContains('legacy', $parsed['defaults']['exclude'], 'Append must add "legacy" to exclude list');
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function appendPreservesDefaultExcludes(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    exclude:\n        - legacy\n",
        );

        try {
            $parsed = self::rendered(
                self::template(),
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
            );

            self::assertContains('vendor', $parsed['defaults']['exclude'], 'Append must preserve existing default excludes');
        } finally {
            $folder->close();
        }
    }
}
