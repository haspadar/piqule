<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Settings;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Settings\YamlDocument;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class YamlDocumentTest extends TestCase
{
    #[Test]
    public function returnsEmptySectionForFileWithoutThatKey(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "other: 1\n");

        self::assertSame(
            [],
            (new YamlDocument($folder->path() . '/.piqule.yaml'))->section('override'),
            'YamlDocument must return an empty section when the key is absent',
        );
    }

    #[Test]
    public function returnsEmptySectionForEmptyFile(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', '');

        self::assertSame(
            [],
            (new YamlDocument($folder->path() . '/.piqule.yaml'))->section('override'),
            'YamlDocument must return an empty section when the file is empty',
        );
    }

    #[Test]
    public function readsExistingSectionAsAssociativeArray(): void
    {
        $folder = (new TempFolder())->withFile(
            '.piqule.yaml',
            "override:\n  phpstan.level: 8\n",
        );

        self::assertSame(
            ['phpstan.level' => 8],
            (new YamlDocument($folder->path() . '/.piqule.yaml'))->section('override'),
            'YamlDocument must expose the named section as a string-keyed mapping',
        );
    }

    #[Test]
    public function rejectsMissingFile(): void
    {
        $this->expectException(PiquleException::class);

        (new YamlDocument('/nonexistent/path/.piqule.yaml'))->section('override');
    }

    #[Test]
    public function rejectsTopLevelThatIsNotAMapping(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "- a\n- b\n");

        $this->expectException(PiquleException::class);

        (new YamlDocument($folder->path() . '/.piqule.yaml'))->section('override');
    }

    #[Test]
    public function rejectsSectionThatIsNotAMapping(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "override: 8\n");

        $this->expectException(PiquleException::class);

        (new YamlDocument($folder->path() . '/.piqule.yaml'))->section('override');
    }

    #[Test]
    public function rejectsMalformedYaml(): void
    {
        $folder = (new TempFolder())->withFile('.piqule.yaml', "override:\n  bad: [unclosed\n");

        $this->expectException(PiquleException::class);

        (new YamlDocument($folder->path() . '/.piqule.yaml'))->section('override');
    }
}
