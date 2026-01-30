<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Reaction;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Reaction\ReportingFileReaction;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReportingFileReactionTest extends TestCase
{
    #[Test]
    public function outputsCreatedMessage(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->created(new FileCreated('created.txt'));

        self::assertEquals(
            'Created: created.txt',
            $output->lines()[0]->text(),
            'Expected created message',
        );
    }

    #[Test]
    public function outputsUpdatedMessage(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->updated(new FileUpdated('updated.txt'));

        self::assertEquals(
            'Updated: updated.txt',
            $output->lines()[0]->text(),
            'Expected updated message',
        );
    }

    #[Test]
    public function outputsSkippedMessage(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->skipped(new FileSkipped('skipped.txt'));

        self::assertEquals(
            'Skipped: skipped.txt',
            $output->lines()[0]->text(),
            'Expected skipped message',
        );
    }

    #[Test]
    public function outputsAlreadyExecutableMessage(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->executableAlreadySet('already-executable.sh');

        self::assertEquals(
            'Already executable: already-executable.sh',
            $output->lines()[0]->text(),
            'Expected already executable message',
        );
    }

    #[Test]
    public function outputsSetExecutableMessage(): void
    {
        $output = new FakeOutput();

        (new ReportingFileReaction($output))
            ->executableWasSet('set-executable.sh');

        self::assertEquals(
            'Set executable: set-executable.sh',
            $output->lines()[0]->text(),
            'Expected set executable message',
        );
    }
}
