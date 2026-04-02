<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\DefaultConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultConfigTest extends TestCase
{
    #[Test]
    public function returnsTrueWhenKeyIsDeclared(): void
    {
        self::assertTrue(
            (new DefaultConfig())->has('shellcheck.shell'),
            'DefaultConfig must report declared keys as present',
        );
    }

    #[Test]
    public function returnsFalseWhenKeyIsNotDeclared(): void
    {
        self::assertFalse(
            (new DefaultConfig())->has('unknown.key'),
            'DefaultConfig must report unknown keys as absent',
        );
    }

    #[Test]
    public function returnsScalarDefaultAsListWhenKeyDeclared(): void
    {
        self::assertSame(
            ['bash'],
            (new DefaultConfig())->list('shellcheck.shell'),
            'scalar default must be wrapped in a list',
        );
    }

    #[Test]
    public function returnsListDefaultWhenKeyDeclared(): void
    {
        self::assertSame(
            ['../../tests/Unit'],
            (new DefaultConfig())->list('phpunit.testsuites.unit'),
            'list default must be returned as-is',
        );
    }

    #[Test]
    public function returnsNumericCoverageDefaultAsListWhenKeyDeclared(): void
    {
        self::assertSame(
            [80],
            (new DefaultConfig())->list('coverage.project.target'),
            'numeric coverage default must be wrapped in a list without percent sign',
        );
    }

    #[Test]
    public function returnsVendorBinaryPathWhenPiquleRunsInCi(): void
    {
        self::assertSame(
            ['vendor/bin/piqule'],
            (new DefaultConfig())->list('ci.piqule_bin'),
            'CI must run the Composer-installed piqule binary by default',
        );
    }

    #[Test]
    public function returnsEmptyListWhenKeyIsUnknown(): void
    {
        self::assertSame(
            [],
            (new DefaultConfig())->list('unknown.key'),
            'DefaultConfig must return empty list for unknown keys without validation',
        );
    }

    #[Test]
    public function returnsTrueForToolEnabledByDefault(): void
    {
        self::assertSame(
            [true],
            (new DefaultConfig())->list('hadolint.enabled'),
            'hadolint must be enabled by default',
        );
    }

    #[Test]
    public function defaultsIncludeToSrc(): void
    {
        self::assertSame(
            ['src'],
            (new DefaultConfig())->list('dirs.include'),
            'dirs.include must default to src',
        );
    }

    #[Test]
    public function defaultsExcludeToVendorTestsGit(): void
    {
        self::assertSame(
            ['vendor', 'tests', '.git'],
            (new DefaultConfig())->list('dirs.exclude'),
            'dirs.exclude must default to vendor, tests, .git',
        );
    }

    #[Test]
    public function defaultsPhpVersionTo83(): void
    {
        self::assertSame(
            ['8.3'],
            (new DefaultConfig())->list('php.version'),
            'php.version must default to 8.3',
        );
    }

    #[Test]
    public function returnsToArrayWithAllDeclaredKeys(): void
    {
        $array = (new DefaultConfig())->toArray();

        self::assertArrayHasKey(
            'phpstan.level',
            $array,
            'toArray must include all declared default keys',
        );
    }

    #[Test]
    public function usesRootNamespaceFromComposerJson(): void
    {
        $folder = sys_get_temp_dir() . '/piqule-test-' . uniqid('', true);
        mkdir($folder, 0o755);
        file_put_contents(
            $folder . '/composer.json',
            '{"autoload":{"psr-4":{"Acme\\\\":"src/"}}}',
        );

        $namespace = (new DefaultConfig(composerJson: $folder . '/composer.json'))->list('phpcs.root_namespace');

        array_map('unlink', glob($folder . '/*') ?: []);
        rmdir($folder);

        self::assertSame(
            ['Acme'],
            $namespace,
            'DefaultConfig must extract root namespace from composer.json psr-4 section',
        );
    }
}
