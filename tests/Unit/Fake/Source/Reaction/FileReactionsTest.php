<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction;

use Haspadar\Piqule\Source\Event\FileCreated;
use Haspadar\Piqule\Source\Event\FileSkipped;
use Haspadar\Piqule\Source\Event\FileUpdated;
use Haspadar\Piqule\Source\Reaction\FileReactions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileReactionsTest extends TestCase
{
    #[Test]
    public function propagatesCreatedEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->created(new FileCreated('created.txt'));

        self::assertCount(
            1,
            $first->events(),
            'Expected created event to be propagated to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Expected created event to be propagated to second reaction',
        );
    }

    #[Test]
    public function propagatesUpdatedEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->updated(new FileUpdated('updated.txt'));

        self::assertCount(
            1,
            $first->events(),
            'Expected updated event to be propagated to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Expected updated event to be propagated to second reaction',
        );
    }

    #[Test]
    public function propagatesSkippedEventToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->skipped(new FileSkipped('skipped.txt'));

        self::assertCount(
            1,
            $first->events(),
            'Expected skipped event to be propagated to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Expected skipped event to be propagated to second reaction',
        );
    }

    #[Test]
    public function propagatesExecutableAlreadySetToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->executableAlreadySet('bin/hook-present');

        self::assertCount(
            1,
            $first->events(),
            'Expected executableAlreadySet to be propagated to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Expected executableAlreadySet to be propagated to second reaction',
        );
    }

    #[Test]
    public function propagatesExecutableWasSetToAllReactions(): void
    {
        $first = new FakeFileReaction();
        $second = new FakeFileReaction();

        (new FileReactions([$first, $second]))
            ->executableWasSet('bin/hook-missing');

        self::assertCount(
            1,
            $first->events(),
            'Expected executableWasSet to be propagated to first reaction',
        );

        self::assertCount(
            1,
            $second->events(),
            'Expected executableWasSet to be propagated to second reaction',
        );
    }
}
