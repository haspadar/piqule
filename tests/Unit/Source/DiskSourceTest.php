<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source;

use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\Source\DiskSource;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskSourceTest extends TestCase
{
    #[Test]
    public function returnsFileName(): void
    {
        self::assertSame(
            'example.txt',
            (new DiskSource(
                'example.txt',
                new InMemoryFileSystem(),
            ))->name(),
            'Expected name() to return original file name',
        );
    }

    #[Test]
    public function readsContentsFromFileSystem(): void
    {
        self::assertSame(
            'hello',
            (new DiskSource(
                'example.txt',
                new InMemoryFileSystem(['example.txt' => 'hello']),
            ))->contents(),
            'Expected contents() to be read from filesystem',
        );
    }

    #[Test]
    public function writesFileToAnotherFileSystem(): void
    {
        $source = new InMemoryFileSystem(['example.txt' => 'hello']);
        $fs = new InMemoryFileSystem();

        (new DiskSource('example.txt', $source))
            ->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'hello',
            $fs->read('example.txt'),
            'Expected writeTo() to copy file contents to filesystem',
        );
    }
}
