<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InitialFileTest extends TestCase
{
    #[Test]
    public function delegatesName(): void
    {
        self::assertSame(
            'initial/config.php',
            (new InitialFile(
                new InlineFile('initial/config.php', 'data'),
            ))->name(),
            'InitialFile must delegate name to origin file',
        );
    }

    #[Test]
    public function delegatesContents(): void
    {
        self::assertSame(
            'payload',
            (new InitialFile(
                new InlineFile('initial/data.txt', 'payload'),
            ))->contents(),
            'InitialFile must delegate contents to origin file',
        );
    }

    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $fs = new InMemoryFileSystem();

        (new InitialFile(
            new InlineFile('initial/new.txt', 'hello'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'hello',
            $fs->read('initial/new.txt'),
            'File must be written when it does not exist',
        );
    }

    #[Test]
    public function reportsCreatedEventWhenFileDoesNotExist(): void
    {
        $fs = new InMemoryFileSystem();
        $reaction = new FakeFileReaction();

        (new InitialFile(
            new InlineFile('initial/created.txt', 'data'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            'initial/created.txt',
            $reaction->events()[0]->name(),
            'Created file name must be reported',
        );
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $fs = new InMemoryFileSystem([
            'initial/existing.txt' => 'original',
        ]);

        (new InitialFile(
            new InlineFile('initial/existing.txt', 'new'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'original',
            $fs->read('initial/existing.txt'),
            'Existing file must not be overwritten',
        );
    }

    #[Test]
    public function reportsSkippedEventWhenFileAlreadyExists(): void
    {
        $fs = new InMemoryFileSystem([
            'initial/skipped.txt' => 'keep',
        ]);
        $reaction = new FakeFileReaction();

        (new InitialFile(
            new InlineFile('initial/skipped.txt', 'ignored'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            'initial/skipped.txt',
            $reaction->events()[0]->name(),
            'Skipped file name must be reported',
        );
    }
}
