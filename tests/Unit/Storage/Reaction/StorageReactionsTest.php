<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage\Reaction;

use Haspadar\Piqule\Storage\Reaction\FakeStorageReaction;
use Haspadar\Piqule\Storage\Reaction\StorageReactions;
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
        );
    }
}
