<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeEventFileReaction;
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
        $storage = new InMemoryStorage();

        (new InitialFile(
            new InlineFile('initial/new.txt', 'hello'),
        ))->writeTo($storage, new FakeEventFileReaction());

        self::assertSame(
            'hello',
            $storage->read('initial/new.txt'),
            'File must be written when it does not exist',
        );
    }

    #[Test]
    public function reportsCreatedEventWhenFileDoesNotExist(): void
    {
        $storage = new InMemoryStorage();
        $reaction = new FakeEventFileReaction();

        (new InitialFile(
            new InlineFile('initial/created.txt', 'data'),
        ))->writeTo($storage, $reaction);

        self::assertSame(
            'initial/created.txt',
            $reaction->events()[0]->name(),
            'Created file name must be reported',
        );
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $storage = new InMemoryStorage([
            'initial/existing.txt' => 'original',
        ]);

        (new InitialFile(
            new InlineFile('initial/existing.txt', 'new'),
        ))->writeTo($storage, new FakeEventFileReaction());

        self::assertSame(
            'original',
            $storage->read('initial/existing.txt'),
            'Existing file must not be overwritten',
        );
    }

    #[Test]
    public function reportsSkippedEventWhenFileAlreadyExists(): void
    {
        $storage = new InMemoryStorage([
            'initial/skipped.txt' => 'keep',
        ]);
        $reaction = new FakeEventFileReaction();

        (new InitialFile(
            new InlineFile('initial/skipped.txt', 'ignored'),
        ))->writeTo($storage, $reaction);

        self::assertSame(
            'initial/skipped.txt',
            $reaction->events()[0]->name(),
            'Skipped file name must be reported',
        );
    }
}
