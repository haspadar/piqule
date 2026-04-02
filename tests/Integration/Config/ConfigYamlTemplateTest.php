<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Config;

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\YamlConfig;
use Haspadar\Piqule\Tests\Constraint\Config\HasConfigYamlKey;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConfigYamlTemplateTest extends TestCase
{
    #[Test]
    public function defaultsAreAvailable(): void
    {
        self::assertThat(
            new DefaultConfig(),
            new HasConfigYamlKey('php.src', ['src']),
            'DefaultConfig must expose php.src default',
        );
    }

    #[Test]
    public function overrideReplacesConfigValue(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    phpstan.level: 7\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('phpstan.level', 7),
                'Override must replace phpstan.level with 7',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function appendAddsNewValueToExclude(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    exclude:\n        - legacy\n",
        );

        try {
            $yamlPath = $folder->path() . '/.piqule.yaml';
            $defaults = (new DefaultConfig())->withYaml($yamlPath);

            self::assertThat(
                new YamlConfig($yamlPath, $defaults),
                new HasConfigYamlKey('exclude', ['vendor', 'tests', '.git', 'legacy']),
                'Append must add "legacy" to the exclude list',
            );
        } finally {
            $folder->close();
        }
    }
}
