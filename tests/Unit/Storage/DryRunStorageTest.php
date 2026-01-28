<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\Storage\DryRunStorage;
use Haspadar\Piqule\Storage\InMemoryStorage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DryRunStorageTest extends TestCase
{
    #[Test]
    public function delegatesExistsToOriginalStorage(): void
    {
        self::assertTrue(
            (new DryRunStorage(
                new InMemoryStorage([
                    'file.txt' => 'content',
                ]),
            ))->exists('file.txt'),
            'Existing file was not detected',
        );
    }

    #[Test]
    public function delegatesReadToOriginalStorage(): void
    {
        self::assertSame(
            'content',
            (new DryRunStorage(
                new InMemoryStorage([
                    'readable.txt' => 'content',
                ]),
            ))->read('readable.txt'),
            'File contents were not read from original storage',
        );
    }

    #[Test]
    public function suppressesWrite(): void
    {
        $origin = new InMemoryStorage();

        (new DryRunStorage($origin))->write('writable.txt', 'content');

        self::assertFalse(
            $origin->exists('writable.txt'),
            'File was written in dry-run mode',
        );
    }

    #[Test]
    public function suppressesExecutableWrite(): void
    {
        $origin = new InMemoryStorage();

        (new DryRunStorage($origin))->writeExecutable('executable.sh', 'content');

        self::assertFalse(
            $origin->exists('executable.sh'),
            'Executable file was written in dry-run mode',
        );
    }

    #[Test]
    public function delegatesNamesToOriginalStorage(): void
    {
        self::assertEquals(
            ['a.txt', 'nested/b.txt'],
            iterator_to_array(
                (new DryRunStorage(
                    new InMemoryStorage([
                        'a.txt' => 'a',
                        'nested/b.txt' => 'b',
                    ]),
                ))->names(),
            ),
            'names() was not delegated to original storage',
        );
    }
}
