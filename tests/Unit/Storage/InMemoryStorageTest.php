<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\InMemoryStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InMemoryStorageTest extends TestCase
{
    #[Test]
    public function existsReturnsFalseForMissingFile(): void
    {
        self::assertFalse(
            (new InMemoryStorage())->exists('missing.txt'),
            'Expected missing file to not exist',
        );
    }

    #[Test]
    public function existsReturnsTrueForExistingFile(): void
    {
        self::assertTrue(
            (new InMemoryStorage(['file.txt' => 'data']))->exists('file.txt'),
            'Expected existing file to exist',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $storage = new InMemoryStorage();
        $storage->write('file.txt', 'hello');

        self::assertSame(
            'hello',
            $storage->read('file.txt'),
            'Expected read() to return written contents',
        );
    }

    #[Test]
    public function readReturnsInitialContents(): void
    {
        self::assertSame(
            'hello',
            (new InMemoryStorage(['file.txt' => 'hello']))->read('file.txt'),
            'Expected read() to return initial contents',
        );
    }

    #[Test]
    public function throwsExceptionWhenReadingMissingFile(): void
    {
        $this->expectException(PiquleException::class);

        (new InMemoryStorage())->read('missing.txt');
    }

    #[Test]
    public function writeOverwritesExistingFile(): void
    {
        $storage = new InMemoryStorage(['file.txt' => 'old']);
        $storage->write('file.txt', 'new');

        self::assertSame(
            'new',
            $storage->read('file.txt'),
            'Expected write() to overwrite existing contents',
        );
    }

    #[Test]
    public function writeExecutableWritesFile(): void
    {
        $storage = new InMemoryStorage();

        $storage->writeExecutable('hook.sh', '#!/bin/sh');

        self::assertSame(
            '#!/bin/sh',
            $storage->read('hook.sh'),
            'Expected writeExecutable() to behave like write()',
        );
    }

    #[Test]
    public function returnsAllStoredNames(): void
    {
        self::assertEquals(
            ['a.txt', 'nested/b.txt'],
            (new InMemoryStorage([
                'a.txt' => 'a',
                'nested/b.txt' => 'b',
            ]))->names(),
            'Expected names() to return all stored file names',
        );
    }
}
