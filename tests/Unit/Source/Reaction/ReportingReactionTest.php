<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source\Reaction;

use Haspadar\Piqule\Source\Event\FileCreated;
use Haspadar\Piqule\Source\Event\FileSkipped;
use Haspadar\Piqule\Source\Event\FileUpdated;
use Haspadar\Piqule\Source\Reaction\ReportingFileReaction;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReportingReactionTest extends TestCase
{
    #[Test]
    public function reportsCreatedFile(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->created(new FileCreated('reporting/pre-push'));

        self::assertCount(
            1,
            $output->lines(),
            'Created file must be reported',
        );
    }

    #[Test]
    public function reportsUpdatedFile(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->updated(new FileUpdated('reporting/pre-commit'));

        self::assertCount(
            1,
            $output->lines(),
            'Updated file must be reported',
        );
    }

    #[Test]
    public function reportsSkippedFile(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->skipped(new FileSkipped('reporting/commit-msg'));

        self::assertCount(
            1,
            $output->lines(),
            'Skipped file must be reported',
        );
    }
}
