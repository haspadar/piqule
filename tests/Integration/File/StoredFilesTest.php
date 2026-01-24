<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\StoredFile;
use Haspadar\Piqule\File\StoredFiles;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StoredFilesTest extends TestCase
{
    #[Test]
    public function returnsEmptyIterableWhenDirectoryIsEmpty(): void
    {
        $root = new DirectoryFixture('stored-files');

        $files = new StoredFiles(
            new DiskStorage($root->path()),
            $root->path(),
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
        $root = (new DirectoryFixture('stored-files'))
            ->withFile('a.txt', 'A')
            ->withFile('nested/b.txt', 'B')
            ->withFile('nested/deep/c.txt', 'C');

        $files = new StoredFiles(
            new DiskStorage($root->path()),
            $root->path(),
        );

        $paths = array_map(
            static fn(StoredFile $file): string => $file->name(),
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
        $root = new DirectoryFixture('stored-files');
        mkdir($root->path() . '/dir-only');

        $files = new StoredFiles(
            new DiskStorage($root->path()),
            $root->path(),
        );

        self::assertSame(
            [],
            iterator_to_array($files->all()),
            'Expected directories to be ignored',
        );
    }
}
