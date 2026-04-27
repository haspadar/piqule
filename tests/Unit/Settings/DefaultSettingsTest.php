<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Settings\DefaultSettings;
use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\FloatValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\ListValue;
use Haspadar\Piqule\Settings\Value\StringValue;
use Haspadar\Piqule\Settings\Value\TreeValue;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DefaultSettingsTest extends TestCase
{
    #[Test]
    public function reportsDeclaredKeyAsPresent(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.level: 9\n",
        );

        self::assertTrue(
            (new DefaultSettings($folder->path() . '/config.yaml'))->has('phpstan.level'),
            'DefaultSettings must report declared keys as present',
        );
    }

    #[Test]
    public function reportsUnknownKeyAsAbsent(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.level: 9\n",
        );

        self::assertFalse(
            (new DefaultSettings($folder->path() . '/config.yaml'))->has('unknown.key'),
            'DefaultSettings must report unknown keys as absent',
        );
    }

    #[Test]
    public function returnsIntValueForIntegerDefault(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.level: 9\n",
        );

        self::assertEquals(
            new IntValue(9),
            (new DefaultSettings($folder->path() . '/config.yaml'))->value('phpstan.level'),
            'integer default must be wrapped in IntValue',
        );
    }

    #[Test]
    public function returnsFloatValueForFloatDefault(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  threshold: 0.5\n",
        );

        self::assertEquals(
            new FloatValue(0.5),
            (new DefaultSettings($folder->path() . '/config.yaml'))->value('threshold'),
            'float default must be wrapped in FloatValue',
        );
    }

    #[Test]
    public function returnsBoolValueForBooleanDefault(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.cli: true\n",
        );

        self::assertEquals(
            new BoolValue(true),
            (new DefaultSettings($folder->path() . '/config.yaml'))->value('phpstan.cli'),
            'boolean default must be wrapped in BoolValue',
        );
    }

    #[Test]
    public function returnsStringValueForStringDefault(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.memory: \"1G\"\n",
        );

        self::assertEquals(
            new StringValue('1G'),
            (new DefaultSettings($folder->path() . '/config.yaml'))->value('phpstan.memory'),
            'string default must be wrapped in StringValue',
        );
    }

    #[Test]
    public function returnsListValueForListDefault(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.paths:\n    - src\n    - tests\n",
        );

        self::assertEquals(
            new ListValue([new StringValue('src'), new StringValue('tests')]),
            (new DefaultSettings($folder->path() . '/config.yaml'))->value('phpstan.paths'),
            'list default must be wrapped in ListValue with nested string values',
        );
    }

    #[Test]
    public function returnsTreeValueForNestedMappingDefault(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.parameters:\n    haspadar:\n      ignoreAbstract: true\n",
        );

        self::assertEquals(
            new TreeValue([
                'haspadar' => new TreeValue([
                    'ignoreAbstract' => new BoolValue(true),
                ]),
            ]),
            (new DefaultSettings($folder->path() . '/config.yaml'))->value('phpstan.parameters'),
            'nested mapping default must be wrapped in TreeValue',
        );
    }

    #[Test]
    public function throwsWhenKeyIsUnknown(): void
    {
        $folder = (new TempFolder())->withFile(
            'config.yaml',
            "defaults:\n  phpstan.level: 9\n",
        );

        $this->expectException(PiquleException::class);

        (new DefaultSettings($folder->path() . '/config.yaml'))->value('unknown.key');
    }

    #[Test]
    public function throwsWhenDefaultsSectionIsMissing(): void
    {
        $folder = (new TempFolder())->withFile('config.yaml', "other: 1\n");

        $this->expectException(PiquleException::class);

        (new DefaultSettings($folder->path() . '/config.yaml'))->has('phpstan.level');
    }

    #[Test]
    public function throwsWhenYamlIsMalformed(): void
    {
        $folder = (new TempFolder())->withFile('config.yaml', "defaults:\n  bad: [unclosed\n");

        $this->expectException(PiquleException::class);

        (new DefaultSettings($folder->path() . '/config.yaml'))->has('bad');
    }
}
