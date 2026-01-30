<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\FileSystem;

use Haspadar\Piqule\FileSystem\DryRunFileSystem;
use Haspadar\Piqule\FileSystem\InMemoryFileSystem;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DryRunFileSystemTest extends TestCase
{
    #[Test]
    public function delegatesExistsToOriginalFileSystem(): void
    {
        self::assertTrue(
            (new DryRunFileSystem(
                new InMemoryFileSystem([
                    'file.txt' => 'content',
                ]),
            ))->exists('file.txt'),
            'Existing file was not detected',
        );
    }

    #[Test]
    public function delegatesReadToOriginalFileSystem(): void
    {
        self::assertSame(
            'content',
            (new DryRunFileSystem(
                new InMemoryFileSystem([
                    'readable.txt' => 'content',
                ]),
            ))->read('readable.txt'),
            'File contents were not read from original filesystem',
        );
    }

    #[Test]
    public function suppressesWrite(): void
    {
        $origin = new InMemoryFileSystem();

        (new DryRunFileSystem($origin))->write('writable.txt', 'content');

        self::assertFalse(
            $origin->exists('writable.txt'),
            'File was written in dry-run mode',
        );
    }

    #[Test]
    public function suppressesExecutableWrite(): void
    {
        $origin = new InMemoryFileSystem();

        (new DryRunFileSystem($origin))->writeExecutable('executable.sh', 'content');

        self::assertFalse(
            $origin->exists('executable.sh'),
            'Executable file was written in dry-run mode',
        );
    }

    #[Test]
    public function delegatesNamesToOriginalFileSystem(): void
    {
        self::assertEquals(
            ['a.txt', 'nested/b.txt'],
            iterator_to_array(
                (new DryRunFileSystem(
                    new InMemoryFileSystem([
                        'a.txt' => 'a',
                        'nested/b.txt' => 'b',
                    ]),
                ))->names(),
            ),
            'names() was not delegated to original filesystem',
        );
    }

    #[Test]
    public function delegatesIsExecutableToOriginalFileSystem(): void
    {
        self::assertFalse(
            (new DryRunFileSystem(
                new InMemoryFileSystem([
                    'file.sh' => '#!/bin/sh',
                ]),
            ))->isExecutable('file.sh'),
            'isExecutable() was not delegated to original filesystem',
        );
    }
}
