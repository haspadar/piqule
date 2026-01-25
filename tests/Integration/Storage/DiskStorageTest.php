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

        (new DiskStorage($root->path()))
            ->write('nested/dir/example.txt', 'hello');

        self::assertFileExists(
            $root->path() . '/nested/dir/example.txt',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        self::assertSame(
            'hello',
            (new DiskStorage($root->path()))
                ->read('example.txt'),
        );
    }

    #[Test]
    public function throwsExceptionWhenCannotCreateDirectory(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('blocker', 'data');

        $this->expectException(PiquleException::class);

        (new DiskStorage($root->path() . '/blocker'))
            ->write('example.txt', 'fail');
    }

    #[Test]
    public function returnsTrueWhenFileExists(): void
    {
        $root = (new DirectoryFixture('disk-storage'))
            ->withFile('example.txt', 'hello');

        self::assertTrue(
            (new DiskStorage($root->path()))->exists('example.txt'),
        );
    }

    #[Test]
    public function returnsFalseWhenFileDoesNotExist(): void
    {
        $root = new DirectoryFixture('disk-storage');

        self::assertFalse(
            (new DiskStorage($root->path()))->exists('missing.txt'),
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

        (new DiskStorage($root->path()))
            ->read('unreadable.txt');
    }

    #[Test]
    public function rejectsPathTraversal(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskStorage('/tmp'))->read('../secrets.txt');
    }

    #[Test]
    public function writesExecutableFile(): void
    {
        $root = new DirectoryFixture('disk-storage');

        (new DiskStorage($root->path()))
            ->writeExecutable('hook.sh', 'payload');

        self::assertFileExists(
            $root->path() . '/hook.sh',
        );
    }

    #[Test]
    public function setsExecutePermissionsOnExecutableFile(): void
    {
        $root = new DirectoryFixture('disk-storage');
        $path = $root->path() . '/hook.sh';

        (new DiskStorage($root->path()))
            ->writeExecutable('hook.sh', 'data');

        self::assertSame(
            0o755,
            fileperms($path) & 0o777,
        );
    }

    #[Test]
    public function throwsExceptionWhenExecutableCannotBeWritten(): void
    {
        $root = new DirectoryFixture('disk-storage');

        $readonly = $root->path() . '/readonly';
        mkdir($readonly, 0o555, true);

        $this->expectException(PiquleException::class);

        (new DiskStorage($readonly))
            ->writeExecutable('hook.sh', 'x');
    }
}
