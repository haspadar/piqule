<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\FileSystem;

use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use Haspadar\Piqule\PiquleException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InMemoryFileSystemTest extends TestCase
{
    #[Test]
    public function existsReturnsFalseForMissingFile(): void
    {
        self::assertFalse(
            (new InMemoryFileSystem())->exists('missing.txt'),
            'Expected missing file to not exist',
        );
    }

    #[Test]
    public function existsReturnsTrueForExistingFile(): void
    {
        self::assertTrue(
            (new InMemoryFileSystem(['file.txt' => 'data']))->exists('file.txt'),
            'Expected existing file to exist',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $fs = new InMemoryFileSystem();
        $fs->write('file.txt', 'hello');

        self::assertSame(
            'hello',
            $fs->read('file.txt'),
            'Expected read() to return written contents',
        );
    }

    #[Test]
    public function readReturnsInitialContents(): void
    {
        self::assertSame(
            'hello',
            (new InMemoryFileSystem(['file.txt' => 'hello']))->read('file.txt'),
            'Expected read() to return initial contents',
        );
    }

    #[Test]
    public function throwsExceptionWhenReadingMissingFile(): void
    {
        $this->expectException(PiquleException::class);

        (new InMemoryFileSystem())->read('missing.txt');
    }

    #[Test]
    public function writeOverwritesExistingFile(): void
    {
        $fs = new InMemoryFileSystem(['file.txt' => 'old']);
        $fs->write('file.txt', 'new');

        self::assertSame(
            'new',
            $fs->read('file.txt'),
            'Expected write() to overwrite existing contents',
        );
    }

    #[Test]
    public function writeExecutableWritesFile(): void
    {
        $fs = new InMemoryFileSystem();

        $fs->writeExecutable('hook.sh', '#!/bin/sh');

        self::assertSame(
            '#!/bin/sh',
            $fs->read('hook.sh'),
            'Expected writeExecutable() to behave like write()',
        );
    }

    #[Test]
    public function returnsAllStoredNames(): void
    {
        self::assertEquals(
            ['a.txt', 'nested/b.txt'],
            (new InMemoryFileSystem([
                'a.txt' => 'a',
                'nested/b.txt' => 'b',
            ]))->names(),
            'Expected names() to return all stored file names',
        );
    }

    #[Test]
    public function isExecutableReturnsFalseForExistingFile(): void
    {
        self::assertFalse(
            (new InMemoryFileSystem(['file.txt' => 'data']))->isExecutable('file.txt'),
            'Expected in-memory file to not be executable',
        );
    }

    #[Test]
    public function throwsExceptionWhenCheckingExecutableForMissingFile(): void
    {
        $this->expectException(PiquleException::class);

        (new InMemoryFileSystem())->isExecutable('missing.txt');
    }
}
