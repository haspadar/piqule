<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Config;

use Haspadar\Piqule\Config\YamlConfig;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Fake\Config\FakeConfig;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class YamlConfigTest extends TestCase
{
    private TempFolder $folder;

    protected function setUp(): void
    {
        $this->folder = new TempFolder();
    }

    protected function tearDown(): void
    {
        $this->folder->close();
    }

    #[Test]
    public function throwsWhenFileContainsInvalidYaml(): void
    {
        $this->expectException(PiquleException::class);

        $path = $this->folder->withFile('.piqule.yaml', ": invalid: yaml: :")->path() . '/.piqule.yaml';

        (new YamlConfig($path, new FakeConfig([])))->has('');
    }

    #[Test]
    public function throwsWhenFileContainsScalarYaml(): void
    {
        $this->expectException(PiquleException::class);

        $path = $this->folder->withFile('.piqule.yaml', "just a string\n")->path() . '/.piqule.yaml';

        (new YamlConfig($path, new FakeConfig([])))->has('');
    }

    #[Test]
    public function returnsDelegatedHasWhenNoOverrides(): void
    {
        $path = $this->folder->withFile('.piqule.yaml', "override: {}\n")->path() . '/.piqule.yaml';

        $config = new YamlConfig($path, new FakeConfig(['phpstan.level' => ['8']]));

        self::assertTrue(
            $config->has('phpstan.level'),
            'YamlConfig must delegate has() to defaults when no overrides are present',
        );
    }

    #[Test]
    public function returnsDefaultValueWhenNoOverridesOrAppends(): void
    {
        $path = $this->folder->withFile('.piqule.yaml', "{}\n")->path() . '/.piqule.yaml';

        $config = new YamlConfig($path, new FakeConfig(['phpstan.level' => ['8']]));

        self::assertSame(
            ['8'],
            $config->list('phpstan.level'),
            'YamlConfig must return the default value when no overrides or appends are specified',
        );
    }

    #[Test]
    public function returnsOverriddenValueWhenOverrideSectionPresent(): void
    {
        $yaml = "override:\n    phpstan.level: 9\n";
        $path = $this->folder->withFile('.piqule.yaml', $yaml)->path() . '/.piqule.yaml';

        $config = new YamlConfig($path, new FakeConfig(['phpstan.level' => ['8']]));

        self::assertSame(
            [9],
            $config->list('phpstan.level'),
            'YamlConfig must return the overridden value when override section is present',
        );
    }

    #[Test]
    public function returnsAppendedValuesWhenAppendSectionPresent(): void
    {
        $yaml = "append:\n    phpstan.neon_includes:\n        - ../../rules.neon\n";
        $path = $this->folder->withFile('.piqule.yaml', $yaml)->path() . '/.piqule.yaml';

        $config = new YamlConfig($path, new FakeConfig(['phpstan.neon_includes' => []]));

        self::assertSame(
            ['../../rules.neon'],
            $config->list('phpstan.neon_includes'),
            'YamlConfig must append values from the append section to the default list',
        );
    }

    #[Test]
    public function appendsToExistingDefaultList(): void
    {
        $yaml = "append:\n    phpstan.neon_includes:\n        - ../../extra.neon\n";
        $path = $this->folder->withFile('.piqule.yaml', $yaml)->path() . '/.piqule.yaml';

        $config = new YamlConfig($path, new FakeConfig(['phpstan.neon_includes' => ['../../base.neon']]));

        self::assertSame(
            ['../../base.neon', '../../extra.neon'],
            $config->list('phpstan.neon_includes'),
            'YamlConfig must preserve default values and append new ones after them',
        );
    }

    #[Test]
    public function toArrayReturnsAllKeysWithOverridesAndAppendsApplied(): void
    {
        $yaml = "override:\n    phpstan.level: 9\nappend:\n    phpstan.neon_includes:\n        - ../../rules.neon\n";
        $path = $this->folder->withFile('.piqule.yaml', $yaml)->path() . '/.piqule.yaml';

        $config = new YamlConfig(
            $path,
            new FakeConfig([
                'phpstan.level' => ['8'],
                'phpstan.neon_includes' => [],
            ]),
        );

        self::assertSame(
            [
                'phpstan.level' => [9],
                'phpstan.neon_includes' => ['../../rules.neon'],
            ],
            $config->toArray(),
            'toArray must return all keys with both overrides and appends applied',
        );
    }

    #[Test]
    public function throwsWhenListCalledForUndeclaredKey(): void
    {
        $this->expectException(PiquleException::class);

        $path = $this->folder->withFile('.piqule.yaml', "{}\n")->path() . '/.piqule.yaml';

        (new YamlConfig($path, new FakeConfig([])))->list('phpstan.level');
    }
}
