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
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('exclude', ['vendor', 'tests', '.git', 'legacy']),
                'Append must add "legacy" to the exclude list',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function overrideExcludeCascadesToDerivedKeys(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    exclude:\n        - build\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('shellcheck.ignore_dirs', ['build']),
                'Override exclude must cascade to shellcheck.ignore_dirs',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function overridePhpSrcCascadesToDerivedKeys(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    php.src:\n        - lib\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('phpmd.paths', ['lib']),
                'Override php.src must cascade to phpmd.paths',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function appendPhpSrcCascadesToDerivedKeys(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    php.src:\n        - lib\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('phpmd.paths', ['src', 'lib']),
                'Append php.src must cascade to phpmd.paths',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function customIncludeCascadesToDerivedKeys(): void
    {
        self::assertThat(
            new DefaultConfig(['lib', 'app']),
            new HasConfigYamlKey('phpmd.paths', ['lib', 'app']),
            'Custom include must cascade to phpmd.paths',
        );
    }
}
