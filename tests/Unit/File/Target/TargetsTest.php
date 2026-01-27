<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Target;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Target\Targets;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TargetsTest extends TestCase
{
    #[Test]
    public function propagatesCreatedEventToAllTargets(): void
    {
        $first = new FakeTarget();
        $second = new FakeTarget();

        (new Targets([$first, $second]))
            ->created(new FileCreated('targets/pre-push'));

        self::assertCount(
            1,
            $first->events(),
            'Created event must be passed to first target',
        );

        self::assertCount(
            1,
            $second->events(),
            'Created event must be passed to second target',
        );
    }

    #[Test]
    public function propagatesUpdatedEventToAllTargets(): void
    {
        $first = new FakeTarget();
        $second = new FakeTarget();

        (new Targets([$first, $second]))
            ->updated(new FileUpdated('targets/pre-commit'));

        self::assertCount(
            1,
            $first->events(),
            'Updated event must be passed to first target',
        );

        self::assertCount(
            1,
            $second->events(),
            'Updated event must be passed to second target',
        );
    }

    #[Test]
    public function propagatesSkippedEventToAllTargets(): void
    {
        $first = new FakeTarget();
        $second = new FakeTarget();

        (new Targets([$first, $second]))
            ->skipped(new FileSkipped('targets/commit-msg'));

        self::assertCount(
            1,
            $first->events(),
            'Skipped event must be passed to first target',
        );

        self::assertCount(
            1,
            $second->events(),
            'Skipped event must be passed to second target',
        );
    }
}
