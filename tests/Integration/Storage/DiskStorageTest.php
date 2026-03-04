<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Storage;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Storage\DiskStorage;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntries;
use Haspadar\Piqule\Tests\Constraint\Storage\HasEntry;
use Haspadar\Piqule\Tests\Fixture\TempFolder;
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
            ))->write(
                new TextFile('a/b/read.txt', 'hello'),
            ),
            new HasEntry('a/b/read.txt', 'hello'),
            'DiskStorage must write a file and make its contents readable',
        );
    }

    #[Test]
    public function overwritesExistingFileWhenWritingToSamePath(): void
    {
        self::assertThat(
            (new DiskStorage(
                (new TempFolder())
                    ->withFile('overwrite.txt', 'first')
                    ->path(),
            ))->write(
                new TextFile('overwrite.txt', 'second'),
            ),
            new HasEntry('overwrite.txt', 'second'),
            'DiskStorage must overwrite an existing file with new contents',
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
            'DiskStorage must list all entries in the specified folder',
        );
    }

    #[Test]
    public function listsNoEntriesForNonDirectoryLocation(): void
    {
        self::assertThat(
            new DiskStorage(
                (new TempFolder())
                    ->withFile('file.txt', 'data')
                    ->path(),
            ),
            new HasEntries('file.txt', []),
            'DiskStorage must return no entries when the location is a file, not a directory',
        );
    }

    #[Test]
    public function listsFilesFromNestedDirectories(): void
    {
        self::assertThat(
            new DiskStorage(
                (new TempFolder())
                    ->withFile('a/b/c/deep.txt', 'x')
                    ->path(),
            ),
            new HasEntries('a', [
                'a/b/c/deep.txt',
            ]),
            'DiskStorage must list files from nested subdirectories recursively',
        );
    }

    #[Test]
    public function writesFileWithCustomMode(): void
    {
        $folder = new TempFolder();

        self::assertThat(
            (new DiskStorage($folder->path()))
                ->write(new TextFile('file.txt', 'data', 0o755)),
            new HasEntry('file.txt', 'data', 0o755),
            'DiskStorage must write a file with the specified mode',
        );
    }

    #[Test]
    public function updatesFileModeWhenOverwritten(): void
    {
        $folder = (new TempFolder())
            ->withFile('file.txt', 'data');

        $storage = new DiskStorage($folder->path());

        $storage = $storage->write(
            new TextFile('file.txt', 'data', 0o755),
        );

        self::assertSame(
            0o755,
            $storage->mode('file.txt'),
            'DiskStorage must update the file mode when overwriting an existing file',
        );
    }

    #[Test]
    public function throwsWhenReadingModeOfMissingLocation(): void
    {
        $this->expectException(PiquleException::class);

        (new DiskStorage(
            (new TempFolder())->path(),
        ))->mode('missing.txt');
    }
}
