<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\DiffingStorage;
use Haspadar\Piqule\Storage\InMemoryStorage;
use Haspadar\Piqule\Storage\Reaction\FakeStorageReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DiffingStorageTest extends TestCase
{
    #[Test]
    public function reportsCreatedWhenFileDoesNotExist(): void
    {
        $reaction = new FakeStorageReaction();

        (new DiffingStorage(
            new InMemoryStorage(),
            $reaction,
        ))->write(
            new TextFile('created.txt', 'data'),
        );

        self::assertSame(
            ['created.txt'],
            $reaction->createdPaths(),
        );
    }

    #[Test]
    public function reportsUpdatedWhenFileExistsWithDifferentContents(): void
    {
        $reaction = new FakeStorageReaction();

        (new DiffingStorage(
            new InMemoryStorage([
                'updated.txt' => 'old',
            ]),
            $reaction,
        ))->write(
            new TextFile('updated.txt', 'new'),
        );

        self::assertSame(
            ['updated.txt'],
            $reaction->updatedPaths(),
        );
    }

    #[Test]
    public function doesNotReportAnythingWhenContentsAreTheSame(): void
    {
        $reaction = new FakeStorageReaction();

        (new DiffingStorage(
            new InMemoryStorage([
                'same.txt' => 'data',
            ]),
            $reaction,
        ))->write(
            new TextFile('same.txt', 'data'),
        );

        self::assertSame(
            [],
            $reaction->createdPaths(),
        );
    }

    #[Test]
    public function delegatesReadToOrigin(): void
    {
        self::assertSame(
            'data',
            (new DiffingStorage(
                new InMemoryStorage(['a.txt' => 'data']),
                new FakeStorageReaction(),
            ))->read('a.txt'),
        );
    }

    #[Test]
    public function delegatesExistsToOrigin(): void
    {
        self::assertTrue(
            (new DiffingStorage(
                new InMemoryStorage(['b.txt' => 'x']),
                new FakeStorageReaction(),
            ))->exists('b.txt'),
        );
    }
}
