<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Source\PlacedSource;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PlacedSourceTest extends TestCase
{
    #[Test]
    public function returnsPlacedName(): void
    {
        self::assertSame(
            '.git/hooks/pre-push',
            (new PlacedSource(
                new InlineSource('source.txt', 'data'),
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
            (new PlacedSource(
                new InlineSource('source.txt', 'payload'),
                'target.txt',
            ))->contents(),
            'Expected contents to be delegated to origin file',
        );
    }

    #[Test]
    public function writesFileUsingPlacedName(): void
    {
        $fs = new InMemoryFileSystem();

        (new PlacedSource(
            new InlineSource('source.txt', 'content'),
            'placed.txt',
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'content',
            $fs->read('placed.txt'),
            'Expected file to be written under placed name',
        );
    }
}
