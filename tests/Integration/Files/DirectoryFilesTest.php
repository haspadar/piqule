<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Files;

use Haspadar\Piqule\File\DiskFile;
use Haspadar\Piqule\Files\DirectoryFiles;
use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\FileSystem\Path;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DirectoryFilesTest extends TestCase
{
    #[Test]
    public function returnsEmptyIterableWhenDirectoryIsEmpty(): void
    {
        $directory = new DirectoryFixture('stored-files');
        $root = new Path($directory->path());

        $files = new DirectoryFiles(
            new DiskFileSystem($root),
        );

        self::assertSame(
            [],
            iterator_to_array($files->all()),
            'Expected no files for empty directory',
        );
    }

    #[Test]
    public function listsFilesRecursivelyWithRelativePaths(): void
    {
        $directory = (new DirectoryFixture('stored-files'))
            ->withFile('a.txt', 'A')
            ->withFile('nested/b.txt', 'B')
            ->withFile('nested/deep/c.txt', 'C');

        $root = new Path($directory->path());

        $files = new DirectoryFiles(
            new DiskFileSystem($root),
        );

        $paths = array_map(
            static fn(DiskFile $file): string => $file->name(),
            iterator_to_array($files->all()),
        );

        sort($paths);

        self::assertSame(
            [
                'a.txt',
                'nested/b.txt',
                'nested/deep/c.txt',
            ],
            $paths,
            'Expected recursive listing with relative paths',
        );
    }

    #[Test]
    public function ignoresDirectoriesAndYieldsOnlyFiles(): void
    {
        $directory = new DirectoryFixture('stored-files');
        mkdir($directory->path() . '/dir-only');

        $root = new Path($directory->path());

        $files = new DirectoryFiles(
            new DiskFileSystem($root),
        );

        self::assertSame(
            [],
            iterator_to_array($files->all()),
            'Expected directories to be ignored',
        );
    }
}
