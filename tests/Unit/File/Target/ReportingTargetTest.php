<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Target;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Target\ReportingTarget;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReportingTargetTest extends TestCase
{
    #[Test]
    public function reportsCreatedFile(): void
    {
        $output = new FakeOutput();

        (new ReportingTarget($output))
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

        (new ReportingTarget($output))
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

        (new ReportingTarget($output))
            ->skipped(new FileSkipped('reporting/commit-msg'));

        self::assertCount(
            1,
            $output->lines(),
            'Skipped file must be reported',
        );
    }
}
