<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\FakeStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
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
        $storage = new FakeStorage();

        (new InitialFile(
            new InlineFile('initial/new.txt', 'hello'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'hello',
            $storage->read('initial/new.txt'),
            'File must be written when it does not exist',
        );
    }

    #[Test]
    public function reportsCreatedEventWhenFileDoesNotExist(): void
    {
        $storage = new FakeStorage();
        $target = new FakeTarget();

        (new InitialFile(
            new InlineFile('initial/created.txt', 'data'),
        ))->writeTo($storage, $target);

        self::assertSame(
            'initial/created.txt',
            $target->events()[0]->name(),
            'Created file name must be reported',
        );
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $storage = new FakeStorage([
            'initial/existing.txt' => 'original',
        ]);

        (new InitialFile(
            new InlineFile('initial/existing.txt', 'new'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'original',
            $storage->read('initial/existing.txt'),
            'Existing file must not be overwritten',
        );
    }

    #[Test]
    public function reportsSkippedEventWhenFileAlreadyExists(): void
    {
        $storage = new FakeStorage([
            'initial/skipped.txt' => 'keep',
        ]);
        $target = new FakeTarget();

        (new InitialFile(
            new InlineFile('initial/skipped.txt', 'ignored'),
        ))->writeTo($storage, $target);

        self::assertSame(
            'initial/skipped.txt',
            $target->events()[0]->name(),
            'Skipped file name must be reported',
        );
    }
}
