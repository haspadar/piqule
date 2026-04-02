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
    #[Test]
    public function rendersValidYamlWithDefaultValues(): void
    {
        $template = file_get_contents(
            dirname(__DIR__, 3) . '/templates/always/.piqule/config.yaml',
        );

        self::assertIsString($template, 'Template file must be readable');

        $rendered = (new ConfiguredFile(
            new TextFile('.piqule/config.yaml', $template),
            new DefaultConfig(),
        ))->contents();

        $parsed = Yaml::parse($rendered);

        self::assertIsArray($parsed, 'Rendered config.yaml must be valid YAML');
        self::assertArrayHasKey('defaults', $parsed, 'Rendered config.yaml must contain a "defaults" key');

        $defaults = $parsed['defaults'];

        self::assertSame(['src'], $defaults['php.src'], 'php.src default must be ["src"]');
        self::assertSame(['8.3'], $defaults['php.versions'], 'php.versions default must be ["8.3"]');
        self::assertSame(9, $defaults['phpstan.level'], 'phpstan.level default must be 9');
        self::assertSame(1, $defaults['psalm.error_level'], 'psalm.error_level default must be 1');
    }

    #[Test]
    public function rendersOverriddenValuesWhenYamlConfigApplied(): void
    {
        $template = file_get_contents(
            dirname(__DIR__, 3) . '/templates/always/.piqule/config.yaml',
        );

        self::assertIsString($template, 'Template file must be readable');

        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    phpstan.level: 7\nappend:\n    exclude:\n        - legacy\n",
        );

        try {
            $config = new YamlConfig(
                $folder->path() . '/.piqule.yaml',
                new DefaultConfig(),
            );

            $rendered = (new ConfiguredFile(
                new TextFile('.piqule/config.yaml', $template),
                $config,
            ))->contents();

            $parsed = Yaml::parse($rendered);

            self::assertIsArray($parsed);

            $defaults = $parsed['defaults'];

            self::assertSame(7, $defaults['phpstan.level'], 'Override must replace phpstan.level with 7');
            self::assertContains('legacy', $defaults['exclude'], 'Append must add "legacy" to exclude list');
            self::assertContains('vendor', $defaults['exclude'], 'Append must preserve existing default excludes');
        } finally {
            $folder->close();
        }
    }
}
