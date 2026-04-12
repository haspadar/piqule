<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Check;

use Haspadar\Piqule\Check\CheckReport;
use Haspadar\Piqule\Tests\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CheckReportTest extends TestCase
{
    #[Test]
    public function writesMutedMessageWhenCheckStartsInBatch(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 3))->started('phpstan', 1);

        self::assertCount(
            1,
            $output->muteds(),
            'started() must write one muted message for a batch run',
        );
    }

    #[Test]
    public function includesCheckNumberInBatchStartMessage(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 3))->started('phpstan', 2);

        self::assertStringContainsString(
            '2/3',
            $output->muteds()[0],
            'started() must include check number and total in batch run',
        );
    }

    #[Test]
    public function omitsCounterWhenSingleCheck(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 1))->started('phpstan', 1);

        self::assertStringNotContainsString(
            '1/1',
            $output->muteds()[0],
            'started() must omit counter when only one check runs',
        );
    }

    #[Test]
    public function writesSuccessMessageWhenCheckPasses(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 1))->passed('phpstan', 1.5);

        self::assertCount(
            1,
            $output->successes(),
            'passed() must write one success message',
        );
    }

    #[Test]
    public function includesElapsedTimeInPassedMessage(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 1))->passed('phpstan', 2.0);

        self::assertStringContainsString(
            '2.0s',
            $output->successes()[0],
            'passed() must include elapsed time',
        );
    }

    #[Test]
    public function writesErrorMessageWhenCheckFails(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 1))->failed('phpstan', 0.5);

        self::assertCount(
            1,
            $output->errors(),
            'failed() must write one error message',
        );
    }

    #[Test]
    public function includesFailPrefixInFailedMessage(): void
    {
        $output = new FakeOutput();

        (new CheckReport($output, 1))->failed('phpstan', 0.5);

        self::assertStringContainsString(
            '[FAIL]',
            $output->errors()[0],
            'failed() must include [FAIL] prefix',
        );
    }
}
