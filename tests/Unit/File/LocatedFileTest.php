<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File;

use Haspadar\Piqule\File\LocatedFile;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Tests\Constraint\File\HasContents;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LocatedFileTest extends TestCase
{
    #[Test]
    public function returnsItsName(): void
    {
        self::assertSame(
            'file.txt',
            (new LocatedFile(
                new InMemoryStorage(),
                'dir/file.txt',
                'file.txt',
            ))->name(),
            'File must expose its name',
        );
    }

    #[Test]
    public function writesContentsToStorage(): void
    {
        self::assertThat(
            (new LocatedFile(
                new InMemoryStorage(),
                'dir/file.txt',
                'file.txt',
            ))->write('data'),
            new HasContents('data'),
        );
    }

    #[Test]
    public function readsPreviouslyWrittenContents(): void
    {
        self::assertThat(
            (new LocatedFile(
                new InMemoryStorage(),
                'dir/file.txt',
                'file.txt',
            ))->write('hello'),
            new HasContents('hello'),
        );
    }
}
