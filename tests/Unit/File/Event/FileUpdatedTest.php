<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Event;

use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileUpdatedTest extends TestCase
{
    #[Test]
    public function passesUpdatedEventToTarget(): void
    {
        $target = new FakeTarget();

        (new FileUpdated('config/cache.php'))->passTo($target);

        self::assertCount(
            1,
            $target->events(),
            'Updated event must be passed to target',
        );
    }

    #[Test]
    public function exposesFileName(): void
    {
        self::assertSame(
            'config/database.php',
            (new FileUpdated('config/database.php'))->name(),
            'FileUpdated must expose the file name it was created with',
        );
    }
}
