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
    public function readsWrittenContents(): void
    {
        self::assertSame(
            'hello',
            (new InMemoryStorage())
                ->write('file.txt', 'hello')
                ->read('file.txt'),
            'Storage must return contents written to the same location',
        );
    }

    #[Test]
    public function overwritesExistingContents(): void
    {
        self::assertSame(
            'second',
            (new InMemoryStorage())
                ->write('file.txt', 'first')
                ->write('file.txt', 'second')
                ->read('file.txt'),
            'Storage must overwrite contents for the same location',
        );
    }

    #[Test]
    public function reportsNonExistingLocation(): void
    {
        self::assertFalse(
            (new InMemoryStorage())->exists('file.txt'),
            'Storage must report non-existing location',
        );
    }

    #[Test]
    public function reportsExistingLocation(): void
    {
        self::assertTrue(
            (new InMemoryStorage())
                ->write('file.txt', 'data')
                ->exists('file.txt'),
            'Storage must report existing location',
        );
    }

    #[Test]
    public function doesNotMutateOriginalStorage(): void
    {
        $initial = new InMemoryStorage();

        $initial->write('file.txt', 'data');

        self::assertFalse(
            $initial->exists('file.txt'),
            'Initial storage must not be modified after write',
        );
    }

    #[Test]
    public function returnsNewStorageWithWrittenEntry(): void
    {
        self::assertTrue(
            (new InMemoryStorage())
                ->write('file.txt', 'data')
                ->exists('file.txt'),
            'Write must return a new storage instance with updated state',
        );
    }

    #[Test]
    public function throwsWhenReadingMissingLocation(): void
    {
        $this->expectException(PiquleException::class);

        (new InMemoryStorage())->read('missing.txt');
    }
}
