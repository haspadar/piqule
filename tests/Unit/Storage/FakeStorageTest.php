<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\FakeStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FakeStorageTest extends TestCase
{
    #[Test]
    public function existsReturnsFalseForMissingFile(): void
    {
        self::assertFalse(
            (new FakeStorage())->exists('missing.txt'),
            'Expected missing file to not exist',
        );
    }

    #[Test]
    public function existsReturnsTrueForExistingFile(): void
    {
        self::assertTrue(
            (new FakeStorage(['file.txt' => 'data']))->exists('file.txt'),
            'Expected existing file to exist',
        );
    }

    #[Test]
    public function readsPreviouslyWrittenFile(): void
    {
        $storage = new FakeStorage();
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
            (new FakeStorage(['file.txt' => 'hello']))->read('file.txt'),
            'Expected read() to return initial contents',
        );
    }

    #[Test]
    public function throwsExceptionWhenReadingMissingFile(): void
    {
        $this->expectException(PiquleException::class);

        (new FakeStorage())->read('missing.txt');
    }

    #[Test]
    public function writeOverwritesExistingFile(): void
    {
        $storage = new FakeStorage(['file.txt' => 'old']);
        $storage->write('file.txt', 'new');

        self::assertSame(
            'new',
            $storage->read('file.txt'),
            'Expected write() to overwrite existing contents',
        );
    }
}
