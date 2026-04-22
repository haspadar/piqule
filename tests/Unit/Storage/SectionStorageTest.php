<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Storage\SectionStorage;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntry;
use Haspadar\Piqule\Tests\Constraint\Storage\ReactionWasSilent;
use Haspadar\Piqule\Tests\Fake\Storage\Reaction\FakeStorageReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SectionStorageTest extends TestCase
{
    private const string PATTERN = '/<!-- skill:begin -->.*?<!-- skill:end -->/s';

    #[Test]
    public function createsFileWhenItDoesNotExist(): void
    {
        $reaction = new FakeStorageReaction();

        (new SectionStorage(
            new InMemoryStorage(),
            $reaction,
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->body<!-- skill:end -->'),
        );

        self::assertSame(
            ['GUIDE.md'],
            $reaction->createdPaths(),
            'created() must be called when the target file does not exist',
        );
    }

    #[Test]
    public function replacesSectionBodyLeavingSurroundingTextIntact(): void
    {
        $result = (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile(
                    'GUIDE.md',
                    "# Intro\n<!-- skill:begin -->old<!-- skill:end -->\n# Outro",
                ),
            ]),
            new FakeStorageReaction(),
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->fresh<!-- skill:end -->'),
        );

        self::assertThat(
            $result,
            new HasEntry(
                'GUIDE.md',
                "# Intro\n<!-- skill:begin -->fresh<!-- skill:end -->\n# Outro",
            ),
            'section body between markers must be swapped while surrounding text stays put',
        );
    }

    #[Test]
    public function reportsUpdatedWhenSectionBodyChanges(): void
    {
        $reaction = new FakeStorageReaction();

        (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile(
                    'GUIDE.md',
                    '<!-- skill:begin -->old<!-- skill:end -->',
                ),
            ]),
            $reaction,
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->fresh<!-- skill:end -->'),
        );

        self::assertSame(
            ['GUIDE.md'],
            $reaction->updatedPaths(),
            'updated() must be called when the section body actually changes',
        );
    }

    #[Test]
    public function reportsSkippedWhenSectionBodyIsIdentical(): void
    {
        $reaction = new FakeStorageReaction();

        (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile(
                    'GUIDE.md',
                    '<!-- skill:begin -->same<!-- skill:end -->',
                ),
            ]),
            $reaction,
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->same<!-- skill:end -->'),
        );

        self::assertSame(
            ['GUIDE.md'],
            $reaction->skippedPaths(),
            'skipped() must be called when the section body is unchanged',
        );
    }

    #[Test]
    public function staysSilentWhenSectionBodyIsIdentical(): void
    {
        $reaction = new FakeStorageReaction();

        (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile(
                    'GUIDE.md',
                    '<!-- skill:begin -->same<!-- skill:end -->',
                ),
            ]),
            $reaction,
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->same<!-- skill:end -->'),
        );

        self::assertThat(
            $reaction,
            new ReactionWasSilent(),
            'identical section replacement must produce no created/updated events',
        );
    }

    #[Test]
    public function skipsFileLackingMarkers(): void
    {
        $reaction = new FakeStorageReaction();

        (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile('GUIDE.md', "# My rules\nno markers here"),
            ]),
            $reaction,
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->body<!-- skill:end -->'),
        );

        self::assertSame(
            ['GUIDE.md'],
            $reaction->skippedPaths(),
            'skipped() must be called when the target file lacks the section markers',
        );
    }

    #[Test]
    public function leavesUnmarkedFileBytewiseUnchanged(): void
    {
        $result = (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile('GUIDE.md', "# My rules\nno markers here"),
            ]),
            new FakeStorageReaction(),
            self::PATTERN,
        ))->write(
            new TextFile('GUIDE.md', '<!-- skill:begin -->body<!-- skill:end -->'),
        );

        self::assertThat(
            $result,
            new HasEntry('GUIDE.md', "# My rules\nno markers here"),
            'unmarked file must retain its original bytes',
        );
    }

    #[Test]
    public function preservesDollarSignsInReplacement(): void
    {
        $result = (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile(
                    'GUIDE.md',
                    '<!-- skill:begin -->old<!-- skill:end -->',
                ),
            ]),
            new FakeStorageReaction(),
            self::PATTERN,
        ))->write(
            new TextFile(
                'GUIDE.md',
                '<!-- skill:begin -->price=$1.99 and $$ literal<!-- skill:end -->',
            ),
        );

        self::assertThat(
            $result,
            new HasEntry(
                'GUIDE.md',
                '<!-- skill:begin -->price=$1.99 and $$ literal<!-- skill:end -->',
            ),
            'dollar-sign sequences must pass through preg_replace as literals',
        );
    }

    #[Test]
    public function preservesBackslashesInReplacement(): void
    {
        $result = (new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile(
                    'GUIDE.md',
                    '<!-- skill:begin -->old<!-- skill:end -->',
                ),
            ]),
            new FakeStorageReaction(),
            self::PATTERN,
        ))->write(
            new TextFile(
                'GUIDE.md',
                '<!-- skill:begin -->C:\\path\\to\\file<!-- skill:end -->',
            ),
        );

        self::assertThat(
            $result,
            new HasEntry(
                'GUIDE.md',
                '<!-- skill:begin -->C:\\path\\to\\file<!-- skill:end -->',
            ),
            'backslash sequences must pass through preg_replace as literals',
        );
    }

    #[Test]
    public function readsFileContentFromOrigin(): void
    {
        $storage = new SectionStorage(
            new InMemoryStorage(['notes.md' => new TextFile('notes.md', 'readable')]),
            new FakeStorageReaction(),
            self::PATTERN,
        );

        self::assertThat(
            $storage,
            new HasEntry('notes.md', 'readable'),
            'read() must delegate to origin storage',
        );
    }

    #[Test]
    public function confirmsExistingFileExistsInOrigin(): void
    {
        self::assertTrue(
            (new SectionStorage(
                new InMemoryStorage(['present.txt' => new TextFile('present.txt', 'data')]),
                new FakeStorageReaction(),
                self::PATTERN,
            ))->exists('present.txt'),
            'exists() must delegate to origin storage',
        );
    }

    #[Test]
    public function returnsSameInstanceWhenFileHasNoMarkers(): void
    {
        $storage = new SectionStorage(
            new InMemoryStorage([
                'GUIDE.md' => new TextFile('GUIDE.md', 'no markers'),
            ]),
            new FakeStorageReaction(),
            self::PATTERN,
        );

        self::assertSame(
            $storage,
            $storage->write(new TextFile('GUIDE.md', '<!-- skill:begin -->body<!-- skill:end -->')),
            'write() must return the same instance when the file is skipped',
        );
    }
}
