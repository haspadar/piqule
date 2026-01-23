<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskStorageTest extends TestCase
{
    #[Test]
    public function writesFileIntoNestedDirectory(): void
    {
        $root = new DirectoryFixture('disk-storage');

        (new DiskStorage($root->path()))->write(
            'nested/dir/example.txt',
            'hello',
        );

        self::assertFileExists(
            $root->path() . '/nested/dir/example.txt',
            'Expected file to be written into nested directory',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        $contents = (new DiskStorage($root->path()))
            ->read('example.txt');

        self::assertSame(
            'hello',
            $contents,
            'Expected read() to return original file contents',
        );
    }

    #[Test]
    public function throwsExceptionWhenCannotCreateDirectory(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('blocker', 'I am a file');

        $this->expectException(PiquleException::class);

        (new DiskStorage(
            $root->path() . '/blocker',
        ))->write('example.txt', 'fail');
    }

    #[Test]
    public function returnsTrueWhenFileExists(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        self::assertTrue(
            (new DiskStorage($root->path()))->exists('example.txt'),
            'Expected exists() to return true for existing file',
        );
    }

    #[Test]
    public function returnsFalseWhenFileDoesNotExist(): void
    {
        $root = new DirectoryFixture('disk-storage');

        self::assertFalse(
            (new DiskStorage($root->path()))->exists('missing.txt'),
            'Expected exists() to return false for missing file',
        );
    }

    #[Test]
    public function throwsExceptionWhenReadFails(): void
    {
        $root = new DirectoryFixture('disk-storage');

        $file = $root->path() . '/unreadable.txt';
        file_put_contents($file, 'secret');
        chmod($file, 0o000);

        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage('Failed to read file "unreadable.txt"');

        (new DiskStorage($root->path()))->read('unreadable.txt');
    }

    #[Test]
    public function throwsExceptionWhenParentDirectoryPathIsAFile(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('blocker', 'I am a file');

        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage(sprintf(
            'Failed to create directory "%s"',
            $root->path() . '/blocker',
        ));

        (new DiskStorage($root->path()))
            ->write('blocker/example.txt', 'fail');
    }

    #[Test]
    public function throwsExceptionWhenCannotCreateDirectoryDueToPermissions(): void
    {
        $root = new DirectoryFixture('disk-storage');

        $readonly = $root->path() . '/readonly';
        mkdir($readonly, 0o555, true);

        $this->expectException(PiquleException::class);
        $this->expectExceptionMessage(sprintf(
            'Failed to create directory "%s"',
            $readonly . '/nested',
        ));

        (new DiskStorage($readonly))
            ->write('nested/example.txt', 'fail');
    }

    #[Test]
    public function rejectsPathTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskStorage('/tmp'))->read('../secrets.txt');
    }
}
