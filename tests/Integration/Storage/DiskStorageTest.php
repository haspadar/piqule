<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Storage;

use Haspadar\Piqule\File\TempFolder;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntries;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiskStorageTest extends TestCase
{
    #[Test]
    public function writesAndReadsFile(): void
    {
        self::assertThat(
            (new DiskStorage(
                (new TempFolder())->path(),
            ))->write('a/b/read.txt', 'hello'),
            new HasEntry('a/b/read.txt', 'hello'),
        );
    }

    #[Test]
    public function overwritesExistingFile(): void
    {
        self::assertThat(
            (new DiskStorage(
                (new TempFolder())
                    ->withFile('overwrite.txt', 'first')
                    ->path(),
            ))->write('overwrite.txt', 'second'),
            new HasEntry('overwrite.txt', 'second'),
        );
    }

    #[Test]
    public function reportsExistingLocation(): void
    {
        $folder = (new TempFolder())
            ->withFile('exists/file.txt', 'data');

        self::assertTrue(
            (new DiskStorage($folder->path()))
                ->exists('exists/file.txt'),
            'DiskStorage must report existing location',
        );
    }

    #[Test]
    public function reportsNonExistingLocation(): void
    {
        self::assertFalse(
            (new DiskStorage(
                (new TempFolder())->path(),
            ))->exists('missing/file.txt'),
            'DiskStorage must report non-existing location',
        );
    }

    #[Test]
    public function throwsWhenReadingMissingLocation(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskStorage(
            (new TempFolder())->path(),
        ))->read('no/such/file.txt');
    }

    #[Test]
    public function listsEntriesInFolder(): void
    {
        self::assertThat(
            new DiskStorage(
                (new TempFolder())
                    ->withFile('a/one.txt', '1')
                    ->withFile('a/two.txt', '2')
                    ->path(),
            ),
            new HasEntries('a', ['a/one.txt', 'a/two.txt']),
        );
    }
}
