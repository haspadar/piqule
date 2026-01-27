<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\StoredFile;
use Haspadar\Piqule\Storage\FakeStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StoredFileTest extends TestCase
{
    #[Test]
    public function returnsFileName(): void
    {
        self::assertSame(
            'example.txt',
            (new StoredFile(
                'example.txt',
                new FakeStorage(),
            ))->name(),
            'Expected name() to return original file name',
        );
    }

    #[Test]
    public function readsContentsFromStorage(): void
    {
        self::assertSame(
            'hello',
            (new StoredFile(
                'example.txt',
                new FakeStorage(['example.txt' => 'hello']),
            ))->contents(),
            'Expected contents() to be read from storage',
        );
    }

    #[Test]
    public function writesFileToAnotherStorage(): void
    {
        $source = new FakeStorage(['example.txt' => 'hello']);
        $target = new FakeStorage();

        (new StoredFile('example.txt', $source))
            ->writeTo($target, new FakeTarget());

        self::assertSame(
            'hello',
            $target->read('example.txt'),
            'Expected writeTo() to copy file contents to target storage',
        );
    }
}
