<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\FileSystem\DiskPath;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InlineFileTest extends TestCase
{
    #[Test]
    public function writesContentsToStorage(): void
    {
        $directory = new DirectoryFixture('inline-file');
        $fs = new DiskFileSystem(new DiskPath($directory->path()));

        (new InlineFile(
            'example.txt',
            'hello',
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'hello',
            file_get_contents($directory->path() . '/example.txt'),
        );
    }
}
