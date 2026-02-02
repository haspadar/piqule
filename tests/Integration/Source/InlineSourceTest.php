<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Source;

use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\Path\Directory\AbsoluteDirectoryPath;
use Haspadar\Piqule\Source\InlineSource;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InlineSourceTest extends TestCase
{
    #[Test]
    public function writesContentsToStorage(): void
    {
        $directory = new DirectoryFixture('inline-file');
        $fs = new DiskFileSystem(
            new AbsoluteDirectoryPath($directory->path()),
        );

        (new InlineSource(
            'example.txt',
            'hello',
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'hello',
            file_get_contents($directory->path() . '/example.txt'),
            'Writes inline file contents to disk',
        );
    }
}
