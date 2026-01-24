<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\ForcedFile;
use Haspadar\Piqule\File\InlineFile;
use Haspadar\Piqule\Storage\FakeStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ForcedFileTest extends TestCase
{
    #[Test]
    public function writesFileWhenItDoesNotExist(): void
    {
        $storage = new FakeStorage();

        (new ForcedFile(
            new InlineFile('example.txt', 'hello'),
        ))->writeTo($storage);

        self::assertSame(
            'hello',
            $storage->read('example.txt'),
        );
    }

    #[Test]
    public function overwritesFileWhenContentsDiffer(): void
    {
        $storage = new FakeStorage([
            'example.txt' => 'old',
        ]);

        (new ForcedFile(
            new InlineFile('example.txt', 'new'),
        ))->writeTo($storage);

        self::assertSame(
            'new',
            $storage->read('example.txt'),
        );
    }

    #[Test]
    public function doesNothingWhenContentsAreIdentical(): void
    {
        $storage = new FakeStorage([
            'example.txt' => 'same',
        ]);

        (new ForcedFile(
            new InlineFile('example.txt', 'same'),
        ))->writeTo($storage);

        self::assertSame(
            'same',
            $storage->read('example.txt'),
        );
    }
}
