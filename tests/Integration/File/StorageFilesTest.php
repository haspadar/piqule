<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\StorageFile;
use Haspadar\Piqule\File\StorageFiles;
use Haspadar\Piqule\Storage\DiskPath;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StorageFilesTest extends TestCase
{
    #[Test]
    public function returnsEmptyIterableWhenDirectoryIsEmpty(): void
    {
        $directory = new DirectoryFixture('stored-files');

        $files = new StorageFiles(
            new DiskStorage(new DiskPath($directory->path())),
            $directory->path(),
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

        $files = new StorageFiles(
            new DiskStorage(new DiskPath($directory->path())),
            $directory->path(),
        );

        $paths = array_map(
            static fn(StorageFile $file): string => $file->name(),
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
            'Expected recursive listing with storage-relative paths',
        );
    }

    #[Test]
    public function ignoresDirectoriesAndYieldsOnlyFiles(): void
    {
        $directory = new DirectoryFixture('stored-files');
        mkdir($directory->path() . '/dir-only');

        $files = new StorageFiles(
            new DiskStorage(new DiskPath($directory->path())),
            $directory->path(),
        );

        self::assertSame(
            [],
            iterator_to_array($files->all()),
            'Expected directories to be ignored',
        );
    }
}
