<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Event;

use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileSkippedTest extends TestCase
{
    #[Test]
    public function passesSkippedEventToTarget(): void
    {
        $target = new FakeTarget();

        (new FileSkipped('config/logging.php'))->passTo($target);

        self::assertCount(
            1,
            $target->events(),
            'Skipped event must be passed to target',
        );
    }

    #[Test]
    public function exposesFileName(): void
    {
        self::assertSame(
            'config/session.php',
            (new FileSkipped('config/session.php'))->name(),
            'FileSkipped must expose the file name it was created with',
        );
    }
}
