<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage\Reaction;

use Haspadar\Piqule\Storage\Reaction\StorageReactions;
use Haspadar\Piqule\Tests\Fake\Storage\Reaction\FakeStorageReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StorageReactionsTest extends TestCase
{
    #[Test]
    public function delegatesCreatedToAllReactions(): void
    {
        $first = new FakeStorageReaction();
        $second = new FakeStorageReaction();

        (new StorageReactions([$first, $second]))
            ->created('file.txt');

        self::assertSame(
            ['file.txt'],
            $first->createdPaths(),
            'StorageReactions must delegate created() to all contained reactions',
        );
    }

    #[Test]
    public function delegatesUpdatedToAllReactions(): void
    {
        $first = new FakeStorageReaction();
        $second = new FakeStorageReaction();

        (new StorageReactions([$first, $second]))
            ->updated('file.txt');

        self::assertSame(
            ['file.txt'],
            $second->updatedPaths(),
            'StorageReactions must delegate updated() to all contained reactions',
        );
    }

    #[Test]
    public function delegatesSkippedToAllReactions(): void
    {
        $first = new FakeStorageReaction();
        $second = new FakeStorageReaction();

        (new StorageReactions([$first, $second]))
            ->skipped('file.txt');

        self::assertSame(
            ['file.txt'],
            $first->skippedPaths(),
            'StorageReactions must delegate skipped() to all contained reactions',
        );
    }
}
