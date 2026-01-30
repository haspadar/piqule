<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\File\PlacedFile;
use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PlacedFileTest extends TestCase
{
    #[Test]
    public function returnsPlacedName(): void
    {
        self::assertSame(
            '.git/hooks/pre-push',
            (new PlacedFile(
                new InlineFile('source.txt', 'data'),
                '.git/hooks/pre-push',
            ))->name(),
            'Expected placed file name to be returned',
        );
    }

    #[Test]
    public function delegatesContentsToOrigin(): void
    {
        self::assertSame(
            'payload',
            (new PlacedFile(
                new InlineFile('source.txt', 'payload'),
                'target.txt',
            ))->contents(),
            'Expected contents to be delegated to origin file',
        );
    }

    #[Test]
    public function writesFileUsingPlacedName(): void
    {
        $fs = new InMemoryFileSystem();

        (new PlacedFile(
            new InlineFile('source.txt', 'content'),
            'placed.txt',
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'content',
            $fs->read('placed.txt'),
            'Expected file to be written under placed name',
        );
    }
}
