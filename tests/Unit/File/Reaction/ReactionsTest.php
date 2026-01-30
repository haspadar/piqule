<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Reaction;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Reaction\FileReactions;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReactionsTest extends TestCase
{
    #[Test]
    public function propagatesCreatedEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->created(new FileCreated('reactions/pre-push'));

        self::assertCount(
            1,
            $first->events(),
            'Created event must be passed to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Created event must be passed to second reaction',
        );
    }

    #[Test]
    public function propagatesUpdatedEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->updated(new FileUpdated('reactions/pre-commit'));

        self::assertCount(
            1,
            $first->events(),
            'Updated event must be passed to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Updated event must be passed to second reaction',
        );
    }

    #[Test]
    public function propagatesSkippedEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->skipped(new FileSkipped('reactions/commit-msg'));

        self::assertCount(
            1,
            $first->events(),
            'Skipped event must be passed to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Skipped event must be passed to second reaction',
        );
    }
}
