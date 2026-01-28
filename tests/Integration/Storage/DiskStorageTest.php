<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiskPath;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskStorageTest extends TestCase
{
    #[Test]
    public function writesFileIntoNestedDirectory(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        (new DiskStorage(new DiskPath($directory->path())))
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
            (new DiskStorage(new DiskPath($directory->path())))
                ->read('example.txt'),
        );
    }

    #[Test]
    public function throwsExceptionWhenCannotCreateDirectory(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('blocker', 'data');

        $this->expectException(PiquleException::class);

        (new DiskStorage(new DiskPath($directory->path() . '/blocker')))
            ->write('example.txt', 'fail');
    }

    #[Test]
    public function returnsTrueWhenFileExists(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        self::assertTrue(
            (new DiskStorage(new DiskPath($directory->path())))->exists('example.txt'),
        );
    }

    #[Test]
    public function returnsFalseWhenFileDoesNotExist(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        self::assertFalse(
            (new DiskStorage(new DiskPath($directory->path())))->exists('missing.txt'),
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

        (new DiskStorage(new DiskPath($directory->path())))
            ->read('unreadable.txt');
    }

    #[Test]
    public function rejectsPathTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskStorage(new DiskPath('/tmp')))->read('../secrets.txt');
    }

    #[Test]
    public function writesExecutableFile(): void
    {
        $directory = new DirectoryFixture('disk-storage');

        (new DiskStorage(new DiskPath($directory->path())))
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

        (new DiskStorage(new DiskPath($directory->path())))
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

        (new DiskStorage(new DiskPath($readonly)))
            ->writeExecutable('hook.sh', 'x');
    }

    #[Test]
    public function listsAllFilesRecursively(): void
    {
        $directory = (new DirectoryFixture('disk-storage'))
            ->withFile('a.txt', 'a')
            ->withFile('nested/b.txt', 'b');

        $names = iterator_to_array(
            (new DiskStorage(new DiskPath($directory->path())))->names(),
        );

        self::assertEquals(
            ['a.txt', 'nested/b.txt'],
            $names,
            'Expected names() to return all file names relative to root',
        );
    }
}
