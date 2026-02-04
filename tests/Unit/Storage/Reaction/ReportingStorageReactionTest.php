<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Storage\Reaction;

use Haspadar\Piqule\Storage\Reaction\ReportingStorageReaction;
use Haspadar\Piqule\Tests\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReportingStorageReactionTest extends TestCase
{
    #[Test]
    public function writesLineOnCreated(): void
    {
        $output = new FakeOutput();

        (new ReportingStorageReaction($output))
            ->created('file.txt');

        self::assertCount(
            1,
            $output->lines(),
        );
    }

    #[Test]
    public function writesLineOnUpdated(): void
    {
        $output = new FakeOutput();

        (new ReportingStorageReaction($output))
            ->updated('file.txt');

        self::assertCount(
            1,
            $output->lines(),
        );
    }
}
