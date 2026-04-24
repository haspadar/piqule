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
    public function psalmProjectIgnoreIsEmptyByDefault(): void
    {
        self::assertThat(
            new DefaultConfig(),
            new HasConfigYamlKey('psalm.project.ignore', []),
            'psalm.project.ignore must be empty so .git is never passed as a <directory> to psalm',
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
    public function appendAddsNewValueToInfraExclude(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    infra.exclude:\n        - dist\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('infra.exclude', ['vendor', 'tests', '.git', 'dist']),
                'Append must add "dist" to infra.exclude',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function overrideInfraExcludeCascadesToDerivedKeys(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n    infra.exclude:\n        - dist\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('shellcheck.ignore_dirs', ['dist']),
                'Override infra.exclude must cascade to shellcheck.ignore_dirs',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function appendInfraExcludeCascadesToDerivedKeys(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    infra.exclude:\n        - dist\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('shellcheck.ignore_dirs', ['vendor', 'tests', '.git', 'dist']),
                'Append infra.exclude must cascade to shellcheck.ignore_dirs',
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

    #[Test]
    public function phpCsFixerDisabledRulesIsEmptyByDefault(): void
    {
        self::assertThat(
            new DefaultConfig(),
            new HasConfigYamlKey('php_cs_fixer.disabled_rules', []),
            'php_cs_fixer.disabled_rules must be empty so no rule overrides are emitted by default',
        );
    }

    #[Test]
    public function appendPhpCsFixerDisabledRulesAddsRuleNames(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    php_cs_fixer.disabled_rules:\n        - phpdoc_scalar\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('php_cs_fixer.disabled_rules', ['phpdoc_scalar']),
                'Append must add "phpdoc_scalar" to php_cs_fixer.disabled_rules',
            );
        } finally {
            $folder->close();
        }
    }

    #[Test]
    public function phpcsDisabledRulesIsEmptyByDefault(): void
    {
        self::assertThat(
            new DefaultConfig(),
            new HasConfigYamlKey('phpcs.disabled_rules', []),
            'phpcs.disabled_rules must be empty so no rule overrides are emitted by default',
        );
    }

    #[Test]
    public function appendPhpcsDisabledRulesAddsRuleNames(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n    phpcs.disabled_rules:\n        - SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly\n",
        );

        try {
            self::assertThat(
                new YamlConfig($folder->path() . '/.piqule.yaml', new DefaultConfig()),
                new HasConfigYamlKey('phpcs.disabled_rules', ['SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly']),
                'Append must add rule name to phpcs.disabled_rules',
            );
        } finally {
            $folder->close();
        }
    }
}
