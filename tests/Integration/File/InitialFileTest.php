<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\InitialFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InitialFileTest extends TestCase
{
    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $root = new DirectoryFixture('initial-file');
        $storage = new DiskStorage($root->path());

        (new InitialFile(
            new InlineFile('example.txt', 'hello'),
        ))->writeTo($storage);

        self::assertFileExists($root->path() . '/example.txt');
    }

    #[Test]
    public function doesNotOverwriteExistingFile(): void
    {
        $root = (new DirectoryFixture('initial-file'))
            ->withFile('example.txt', 'original');

        $storage = new DiskStorage($root->path());

        (new InitialFile(
            new InlineFile('example.txt', 'new'),
        ))->writeTo($storage);

        self::assertSame(
            'original',
            file_get_contents($root->path() . '/example.txt'),
        );
    }
}
