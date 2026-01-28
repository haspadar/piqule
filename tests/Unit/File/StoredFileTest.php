<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\StoredFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeEventFileReaction;
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
                new InMemoryStorage(),
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
                new InMemoryStorage(['example.txt' => 'hello']),
            ))->contents(),
            'Expected contents() to be read from storage',
        );
    }

    #[Test]
    public function writesFileToAnotherStorage(): void
    {
        $source = new InMemoryStorage(['example.txt' => 'hello']);
        $storage = new InMemoryStorage();

        (new StoredFile('example.txt', $source))
            ->writeTo($storage, new FakeEventFileReaction());

        self::assertSame(
            'hello',
            $storage->read('example.txt'),
            'Expected writeTo() to copy file contents to storage',
        );
    }
}
