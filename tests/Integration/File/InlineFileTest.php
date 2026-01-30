<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\DiskPath;
use Haspadar\Piqule\Storage\DiskStorage;
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
        $storage = new DiskStorage(new DiskPath($directory->path()));

        (new InlineFile(
            'example.txt',
            'hello',
        ))->writeTo($storage, new FakeFileReaction());

        self::assertSame(
            'hello',
            file_get_contents($directory->path() . '/example.txt'),
        );
    }
}
