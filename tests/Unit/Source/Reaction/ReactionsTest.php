<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source\Reaction;

use Haspadar\Piqule\Source\Event\FileCreated;
use Haspadar\Piqule\Source\Event\FileSkipped;
use Haspadar\Piqule\Source\Event\FileUpdated;
use Haspadar\Piqule\Source\Reaction\FileReactions;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
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

    #[Test]
    public function propagatesExecutableAlreadySetEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->executableAlreadySet('reactions/pre-push');

        self::assertCount(
            1,
            $first->events(),
            'ExecutableAlreadySet event must be passed to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'ExecutableAlreadySet event must be passed to second reaction',
        );
    }

    #[Test]
    public function propagatesExecutableWasSetEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->executableWasSet('reactions/pre-commit');

        self::assertCount(
            1,
            $first->events(),
            'ExecutableWasSet event must be passed to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'ExecutableWasSet event must be passed to second reaction',
        );
    }
}
