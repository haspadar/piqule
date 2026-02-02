<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Source\ForcedSource;
use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ForcedSourceTest extends TestCase
{
    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $fs = new InMemoryFileSystem();

        (new ForcedSource(
            new InlineSource('forced/write.txt', 'hello'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'hello',
            $fs->read('forced/write.txt'),
            'File must be written when it does not exist',
        );
    }

    #[Test]
    public function reportsCreatedEventWhenFileDoesNotExist(): void
    {
        $fs = new InMemoryFileSystem();
        $reaction = new FakeFileReaction();

        (new ForcedSource(
            new InlineSource('forced/created.txt', 'data'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            'forced/created.txt',
            $reaction->events()[0]->name(),
            'Created file name must be reported',
        );
    }

    #[Test]
    public function overwritesFileWhenContentsDiffer(): void
    {
        $fs = new InMemoryFileSystem([
            'forced/update.txt' => 'old',
        ]);

        (new ForcedSource(
            new InlineSource('forced/update.txt', 'new'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'new',
            $fs->read('forced/update.txt'),
            'File must be overwritten when contents differ',
        );
    }

    #[Test]
    public function reportsUpdatedEventWhenContentsDiffer(): void
    {
        $fs = new InMemoryFileSystem([
            'forced/updated.txt' => 'before',
        ]);
        $reaction = new FakeFileReaction();

        (new ForcedSource(
            new InlineSource('forced/updated.txt', 'after'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            'forced/updated.txt',
            $reaction->events()[0]->name(),
            'Updated file name must be reported',
        );
    }

    #[Test]
    public function doesNotOverwriteFileWhenContentsAreIdentical(): void
    {
        $fs = new InMemoryFileSystem([
            'forced/same.txt' => 'same',
        ]);

        (new ForcedSource(
            new InlineSource('forced/same.txt', 'same'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'same',
            $fs->read('forced/same.txt'),
            'File must not be rewritten when contents are identical',
        );
    }

    #[Test]
    public function reportsSkippedEventWhenContentsAreIdentical(): void
    {
        $fs = new InMemoryFileSystem([
            'forced/skipped.txt' => 'noop',
        ]);
        $reaction = new FakeFileReaction();

        (new ForcedSource(
            new InlineSource('forced/skipped.txt', 'noop'),
        ))->writeTo($fs, $reaction);

        self::assertSame(
            'forced/skipped.txt',
            $reaction->events()[0]->name(),
            'Skipped file name must be reported',
        );
    }
}
