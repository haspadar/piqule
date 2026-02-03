<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage\Reaction;

use Haspadar\Piqule\Storage\Reaction\FakeStorageReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FakeStorageReactionTest extends TestCase
{
    #[Test]
    public function collectsCreatedPaths(): void
    {
        $reaction = new FakeStorageReaction();

        $reaction->created('a.txt');

        self::assertSame(
            ['a.txt'],
            $reaction->createdPaths(),
        );
    }

    #[Test]
    public function collectsUpdatedPaths(): void
    {
        $reaction = new FakeStorageReaction();

        $reaction->updated('b.txt');

        self::assertSame(
            ['b.txt'],
            $reaction->updatedPaths(),
        );
    }
}
