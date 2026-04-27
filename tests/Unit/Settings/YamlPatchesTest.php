<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Settings\Patch\AppendList;
use Haspadar\Piqule\Settings\Patch\OverrideScalar;
use Haspadar\Piqule\Settings\Patch\RemoveList;
use Haspadar\Piqule\Settings\YamlPatches;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class YamlPatchesTest extends TestCase
{
    #[Test]
    public function returnsEmptyListForFileWithNoSections(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "other: 1\n");

        self::assertSame(
            [],
            (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches(),
            'YamlPatches must return no patches when the file has no override/append/remove sections',
        );
    }

    #[Test]
    public function returnsEmptyListForEmptyFile(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', '');

        self::assertSame(
            [],
            (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches(),
            'YamlPatches must return no patches when the file is empty',
        );
    }

    #[Test]
    public function readsOverrideSectionAsScalarPatch(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n  phpstan.level: 8\n",
        );

        $patches = (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();

        self::assertInstanceOf(
            OverrideScalar::class,
            $patches[0],
            'YamlPatches must read override scalars and produce OverrideScalar',
        );
    }

    #[Test]
    public function readsAppendSectionAsListPatch(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "append:\n  infra.exclude:\n    - dist\n",
        );

        $patches = (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();

        self::assertInstanceOf(
            AppendList::class,
            $patches[0],
            'YamlPatches must read append lists and produce AppendList',
        );
    }

    #[Test]
    public function readsRemoveSectionAsListPatch(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "remove:\n  phpstan.checked_exceptions:\n    - '\\Throwable'\n",
        );

        $patches = (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();

        self::assertInstanceOf(
            RemoveList::class,
            $patches[0],
            'YamlPatches must read remove lists and produce RemoveList',
        );
    }

    #[Test]
    public function combinesPatchesFromAllThreeSectionsInDeclarationOrder(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n  phpstan.level: 8\nappend:\n  infra.exclude:\n    - dist\nremove:\n  phpstan.checked_exceptions:\n    - '\\Throwable'\n",
        );

        $patches = (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();

        self::assertCount(
            3,
            $patches,
            'YamlPatches must combine override, append and remove patches into a single list',
        );
    }

    #[Test]
    public function rejectsTopLevelThatIsNotAMapping(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "- a\n- b\n");

        $this->expectException(PiquleException::class);

        (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();
    }

    #[Test]
    public function rejectsSectionThatIsNotAMapping(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "override: 8\n");

        $this->expectException(PiquleException::class);

        (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();
    }

    #[Test]
    public function rejectsMalformedYaml(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "override:\n  bad: [unclosed\n");

        $this->expectException(PiquleException::class);

        (new YamlPatches($folder->path() . '/.piqule.yaml'))->patches();
    }
}
