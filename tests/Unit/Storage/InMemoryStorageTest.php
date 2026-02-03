<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntries;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class InMemoryStorageTest extends TestCase
{
    #[Test]
    public function readsWrittenContents(): void
    {
        self::assertThat(
            (new InMemoryStorage())->write(
                new TextFile('read/file.txt', 'hello'),
            ),
            new HasEntry('read/file.txt', 'hello'),
        );
    }

    #[Test]
    public function overwritesExistingContents(): void
    {
        $path = './overwrite.txt';

        self::assertThat(
            (new InMemoryStorage())
                ->write(new TextFile($path, 'first'))
                ->write(new TextFile($path, 'second')),
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
                ->write(new TextFile('exists/data.bin', 'data'))
                ->exists('exists/data.bin'),
            'Storage must report existing location',
        );
    }

    #[Test]
    public function doesNotMutateOriginalStorage(): void
    {
        $storage = new InMemoryStorage();

        $storage->write(new TextFile('../immutable.txt', 'data'));

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
                ->write(new TextFile('new/file.log', 'data'))
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

    #[Test]
    public function listsEntriesUnderGivenLocation(): void
    {
        self::assertThat(
            (new InMemoryStorage())
                ->write(new TextFile('alpha/file-1.log', '1'))
                ->write(new TextFile('alpha/file-2.log', '2'))
                ->write(new TextFile('beta/ignored.log', 'x')),
            new HasEntries('alpha', [
                'alpha/file-1.log',
                'alpha/file-2.log',
            ]),
        );
    }

    #[Test]
    public function doesNotListNestedEntries(): void
    {
        self::assertThat(
            (new InMemoryStorage())
                ->write(new TextFile('root/level1/deep.txt', 'x'))
                ->write(new TextFile('root/shallow.txt', '1')),
            new HasEntries('root', [
                'root/shallow.txt',
            ]),
        );
    }

    #[Test]
    public function listsNoEntriesWhenLocationHasOnlyNestedFiles(): void
    {
        self::assertThat(
            (new InMemoryStorage())
                ->write(new TextFile('container/nested/one.dat', '1'))
                ->write(new TextFile('container/nested/two.dat', '2')),
            new HasEntries('container', []),
        );
    }
}
