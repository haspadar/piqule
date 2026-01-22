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
}
