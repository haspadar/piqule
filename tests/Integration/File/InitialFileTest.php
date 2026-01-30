<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\DiskPath;
use Haspadar\Piqule\Storage\DiskStorage;
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
        $storage = new DiskStorage(new DiskPath($directory->path()));

        (new InitialFile(
            new InlineFile('example.txt', 'hello'),
        ))->writeTo($storage, new FakeFileReaction());

        self::assertFileExists($directory->path() . '/example.txt');
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $directory = (new DirectoryFixture('initial-file'))
            ->withFile('example.txt', 'original');

        $storage = new DiskStorage(new DiskPath($directory->path()));

        (new InitialFile(
            new InlineFile('example.txt', 'new'),
        ))->writeTo($storage, new FakeFileReaction());

        self::assertSame(
            'original',
            file_get_contents($directory->path() . '/example.txt'),
        );
    }
}
