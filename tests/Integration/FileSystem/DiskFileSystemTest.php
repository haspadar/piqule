<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\FileSystem;

use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskFileSystemTest extends TestCase
{
    #[Test]
    public function writesFileIntoNestedDirectory(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->write('nested/dir/example.txt', 'hello');

        self::assertFileExists(
            $directory->path() . '/nested/dir/example.txt',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        self::assertSame(
            'hello',
            (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
                ->read('example.txt'),
        );
    }

    #[Test]
    public function throwsExceptionWhenCannotCreateDirectory(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('blocker', 'data');

        $this->expectException(PiquleException::class);

        (new DiskFileSystem(
            new AbsoluteDirectoryPath($directory->path() . '/blocker'),
        ))->write('example.txt', 'fail');
    }

    #[Test]
    public function returnsTrueWhenFileExists(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        self::assertTrue(
            (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
                ->exists('example.txt'),
        );
    }

    #[Test]
    public function returnsFalseWhenFileDoesNotExist(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        self::assertFalse(
            (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
                ->exists('missing.txt'),
        );
    }

    #[Test]
    public function throwsExceptionWhenReadFails(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        $file = $directory->path() . '/unreadable.txt';
        file_put_contents($file, 'secret');
        chmod($file, 0o000);

        $this->expectException(PiquleException::class);

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->read('unreadable.txt');
    }

    #[Test]
    public function writesExecutableFile(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->writeExecutable('hook.sh', 'payload');

        self::assertFileExists(
            $directory->path() . '/hook.sh',
        );
    }

    #[Test]
    public function setsExecutePermissionsOnExecutableFile(): void
    {
        $directory = new DirectoryFixture('disk-storage');
        $path = $directory->path() . '/hook.sh';

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->writeExecutable('hook.sh', 'data');

        self::assertSame(
            0o755,
            fileperms($path) & 0o777,
        );
    }

    #[Test]
    public function throwsExceptionWhenExecutableCannotBeWritten(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        $readonly = $directory->path() . '/readonly';
        mkdir($readonly, 0o555, true);

        $this->expectException(PiquleException::class);

        (new DiskFileSystem(new AbsoluteDirectoryPath($readonly)))
            ->writeExecutable('hook.sh', 'x');
    }

    #[Test]
    public function listsAllFilesRecursively(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('a.txt', 'a')
            ->withFile('nested/b.txt', 'b');

        $names = iterator_to_array(
            (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))->names(),
        );

        self::assertEqualsCanonicalizing(
            ['a.txt', 'nested/b.txt'],
            $names,
            'Expected names() to return all file names relative to root',
        );
    }

    #[Test]
    public function returnsTrueForExecutableFile(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->writeExecutable('hook.sh', 'payload');

        self::assertTrue(
            (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
                ->isExecutable('hook.sh'),
        );
    }

    #[Test]
    public function returnsFalseForNonExecutableFile(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->write('plain.txt', 'data');

        self::assertFalse(
            (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
                ->isExecutable('plain.txt'),
        );
    }

    #[Test]
    public function throwsExceptionWhenCheckingExecutableForMissingFile(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        $this->expectException(PiquleException::class);

        (new DiskFileSystem(new AbsoluteDirectoryPath($directory->path())))
            ->isExecutable('missing.sh');
    }
}
