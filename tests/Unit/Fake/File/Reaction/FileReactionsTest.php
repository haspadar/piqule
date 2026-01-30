<?php
declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\File\Reaction;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Reaction\FileReactions;
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
            $second->events(),
            'Expected updated event to be propagated to second reaction',
        );
    }

    #[Test]
    public function propagatesSkippedEventToAllReactions(): void
    {
        $reaction = new FakeFileReaction();

        (new FileReactions([$reaction]))
            ->skipped(new FileSkipped('skipped.txt'));

        self::assertCount(
            1,
            $reaction->events(),
            'Expected skipped event to be propagated',
        );
    }

    #[Test]
    public function propagatesExecutableAlreadySet(): void
    {
        $reaction = new FakeFileReaction();

        (new FileReactions([$reaction]))
            ->executableAlreadySet('bin/hook-present');

        self::assertCount(
            1,
            $reaction->events(),
            'Expected executableAlreadySet to be propagated',
        );
    }

    #[Test]
    public function propagatesExecutableWasSet(): void
    {
        $reaction = new FakeFileReaction();

        (new FileReactions([$reaction]))
            ->executableWasSet('bin/hook-missing');

        self::assertCount(
            1,
            $reaction->events(),
            'Expected executableWasSet to be propagated',
        );
    }
}