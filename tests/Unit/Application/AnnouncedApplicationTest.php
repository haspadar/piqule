<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Application;

use Haspadar\Piqule\Application\AnnouncedApplication;
use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Tests\Unit\Fake\Application\FakeApplication;
use Haspadar\Piqule\Tests\Unit\Fake\Output\FakeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AnnouncedApplicationTest extends TestCase
{
    #[Test]
    public function announcesExecutionStart(): void
    {
        $output = new FakeOutput();

        (new AnnouncedApplication(
            new FakeApplication(),
            $output,
            new Text('execution started', new Yellow()),
            new Text('...', new Yellow()),
        ))->run();

        self::assertEquals(
            new Text('execution started', new Yellow()),
            $output->lines()[0],
            'Execution start was not announced',
        );
    }

    #[Test]
    public function announcesExecutionEnd(): void
    {
        $output = new FakeOutput();

        (new AnnouncedApplication(
            new FakeApplication(),
            $output,
            new Text('...', new Yellow()),
            new Text('execution finished', new Yellow()),
        ))->run();

        self::assertEquals(
            new Text('execution finished', new Yellow()),
            $output->lines()[1],
            'Execution end was not announced',
        );
    }
}
