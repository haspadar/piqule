<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\ForcedFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ForcedFileTest extends TestCase
{
    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $storage = new InMemoryStorage();

        (new ForcedFile(
            new InlineFile('forced/write.txt', 'hello'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'hello',
            $storage->read('forced/write.txt'),
            'File must be written when it does not exist',
        );
    }

    #[Test]
    public function reportsCreatedEventWhenFileDoesNotExist(): void
    {
        $storage = new InMemoryStorage();
        $target = new FakeTarget();

        (new ForcedFile(
            new InlineFile('forced/created.txt', 'data'),
        ))->writeTo($storage, $target);

        self::assertSame(
            'forced/created.txt',
            $target->events()[0]->name(),
            'Created file name must be reported',
        );
    }

    #[Test]
    public function overwritesFileWhenContentsDiffer(): void
    {
        $storage = new InMemoryStorage([
            'forced/update.txt' => 'old',
        ]);

        (new ForcedFile(
            new InlineFile('forced/update.txt', 'new'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'new',
            $storage->read('forced/update.txt'),
            'File must be overwritten when contents differ',
        );
    }

    #[Test]
    public function reportsUpdatedEventWhenContentsDiffer(): void
    {
        $storage = new InMemoryStorage([
            'forced/updated.txt' => 'before',
        ]);
        $target = new FakeTarget();

        (new ForcedFile(
            new InlineFile('forced/updated.txt', 'after'),
        ))->writeTo($storage, $target);

        self::assertSame(
            'forced/updated.txt',
            $target->events()[0]->name(),
            'Updated file name must be reported',
        );
    }

    #[Test]
    public function doesNotOverwriteFileWhenContentsAreIdentical(): void
    {
        $storage = new InMemoryStorage([
            'forced/same.txt' => 'same',
        ]);

        (new ForcedFile(
            new InlineFile('forced/same.txt', 'same'),
        ))->writeTo($storage, new FakeTarget());

        self::assertSame(
            'same',
            $storage->read('forced/same.txt'),
            'File must not be rewritten when contents are identical',
        );
    }

    #[Test]
    public function reportsSkippedEventWhenContentsAreIdentical(): void
    {
        $storage = new InMemoryStorage([
            'forced/skipped.txt' => 'noop',
        ]);
        $target = new FakeTarget();

        (new ForcedFile(
            new InlineFile('forced/skipped.txt', 'noop'),
        ))->writeTo($storage, $target);

        self::assertSame(
            'forced/skipped.txt',
            $target->events()[0]->name(),
            'Skipped file name must be reported',
        );
    }
}
