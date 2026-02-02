<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InMemoryStorageTest extends TestCase
{
    #[Test]
    public function readsWrittenContents(): void
    {
        self::assertThat(
            (new InMemoryStorage())->write('read/file.txt', 'hello'),
            new HasEntry('read/file.txt', 'hello'),
        );
    }

    #[Test]
    public function overwritesExistingContents(): void
    {
        $path = './overwrite.txt';

        self::assertThat(
            (new InMemoryStorage())
                ->write($path, 'first')
                ->write($path, 'second'),
            new HasEntry($path, 'second'),
        );
    }

    #[Test]
    public function reportsNonExistingLocation(): void
    {
        self::assertFalse(
            (new InMemoryStorage())->exists('missing/location.txt'),
            'Storage must report non-existing location',
        );
    }

    #[Test]
    public function reportsExistingLocation(): void
    {
        self::assertTrue(
            (new InMemoryStorage())
                ->write('exists/data.bin', 'data')
                ->exists('exists/data.bin'),
            'Storage must report existing location',
        );
    }

    #[Test]
    public function doesNotMutateOriginalStorage(): void
    {
        $storage = new InMemoryStorage();

        $storage->write('../immutable.txt', 'data');

        self::assertFalse(
            $storage->exists('../immutable.txt'),
            'Initial storage must not be modified after write',
        );
    }

    #[Test]
    public function returnsNewStorageWithWrittenEntry(): void
    {
        self::assertTrue(
            (new InMemoryStorage())
                ->write('new/file.log', 'data')
                ->exists('new/file.log'),
            'Write must return a new storage instance with updated state',
        );
    }

    #[Test]
    public function throwsWhenReadingMissingLocation(): void
    {
        $this->expectException(PiquleException::class);

        (new InMemoryStorage())->read('./absent.txt');
    }
}
