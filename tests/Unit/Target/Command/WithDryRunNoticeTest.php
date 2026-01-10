<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Target\Command;

use Haspadar\Piqule\Target\Sync\WithDryRunSync;
use Haspadar\Piqule\Tests\Unit\Fake\Command\FakeSync;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithDryRunNoticeTest extends TestCase
{
    #[Test]
    public function runsOriginalCommand(): void
    {
        $origin = new FakeSync();
        $output = new FakeOutput();

        (new WithDryRunSync($origin, $output))->apply();

        self::assertTrue(
            $origin->isRan(),
            'Original command must be executed',
        );
    }

    #[Test]
    public function emitsDryRunNotices(): void
    {
        $output = new FakeOutput();

        (new WithDryRunSync(new FakeSync(), $output))->apply();

        self::assertCount(
            2,
            $output->lines(),
            'Dry run must emit start and end notices',
        );
    }
}
