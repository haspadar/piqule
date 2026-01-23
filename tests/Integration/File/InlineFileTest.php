<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\File;

use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Integration\Fixtures\DirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InlineFileTest extends TestCase
{
    #[Test]
    public function writesContentsToStorage(): void
    {
        $root = new DirectoryFixture('inline-file');
        $storage = new DiskStorage($root->path());

        (new InlineFile(
            'example.txt',
            'hello',
        ))->writeTo($storage);

        self::assertSame(
            'hello',
            file_get_contents($root->path() . '/example.txt'),
        );
    }
}
