<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Target\Command;

use Haspadar\Piqule\Target\Command\WithDryRunNotice;
use Haspadar\Piqule\Tests\Fake\Command\FakeCommand;
use Haspadar\Piqule\Tests\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class WithDryRunNoticeTest extends TestCase
{
    #[Test]
    public function runsOriginalCommand(): void
    {
        $origin = new FakeCommand();
        $output = new FakeOutput();

        (new WithDryRunNotice($origin, $output))->run();

        self::assertTrue(
            $origin->isRan(),
            'Original command must be executed',
        );
    }

    #[Test]
    public function emitsDryRunNotices(): void
    {
        $output = new FakeOutput();

        (new WithDryRunNotice(new FakeCommand(), $output))->run();

        self::assertCount(
            2,
            $output->lines(),
            'Dry run must emit start and end notices',
        );
    }
}
