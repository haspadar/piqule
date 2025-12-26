<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Target\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Target\Storage\DiskTargetStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use Haspadar\Piqule\Tests\Integration\Fixtures\FileFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskTargetStorageTest extends TestCase
{
    #[Test]
    public function writesFileIntoNestedDirectory(): void
    {
        $target = new DirectoryFixture('disk-target');

        (new DiskTargetStorage($target->path()))->write(
            'nested/dir/example.txt',
            new FileFixture('hello'),
        );

        self::assertFileExists(
            $target->path() . '/nested/dir/example.txt',
            'Expected file to be written into nested directory',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $target = (new DirectoryFixture('disk-target'))
            ->withFile('example.txt', 'hello');

        $file = (new DiskTargetStorage($target->path()))->read('example.txt');

        self::assertSame(
            'hello',
            $file->contents(),
            'Expected read() to return file with original contents',
        );
    }

    #[Test]
    public function throwsExceptionWhenCannotCreateDirectory(): void
    {
        $root = (new DirectoryFixture('disk-target'))
            ->withFile('blocker', 'I am a file');

        $this->expectException(PiquleException::class);

        (new DiskTargetStorage(
            $root->path() . '/blocker',
        ))->write(
            'example.txt',
            new FileFixture('fail'),
        );
    }
}
