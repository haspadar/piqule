<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\FileSystem\DiskFileSystem;
use Haspadar\Piqule\Path\DirectoryPath;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InitialFileTest extends TestCase
{
    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $directory = new DirectoryFixture('initial-file');
        $fs = new DiskFileSystem(
            new DirectoryPath($directory->path()),
        );

        (new InitialFile(
            new InlineFile('example.txt', 'hello'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertFileExists(
            $directory->path() . '/example.txt',
            'Expected file to be written when it does not exist',
        );
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $directory = (new DirectoryFixture('initial-file'))
            ->withFile('example.txt', 'original');

        $fs = new DiskFileSystem(
            new DirectoryPath($directory->path()),
        );

        (new InitialFile(
            new InlineFile('example.txt', 'new'),
        ))->writeTo($fs, new FakeFileReaction());

        self::assertSame(
            'original',
            file_get_contents($directory->path() . '/example.txt'),
            'Expected existing file contents to remain unchanged',
        );
    }
}
