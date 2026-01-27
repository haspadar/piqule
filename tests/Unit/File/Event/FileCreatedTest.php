<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Event;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\Tests\Unit\Fake\File\Target\FakeTarget;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileCreatedTest extends TestCase
{
    #[Test]
    public function passesCreatedEventToTarget(): void
    {
        $target = new FakeTarget();

        (new FileCreated('config/app.php'))->passTo($target);

        self::assertCount(
            1,
            $target->events(),
            'Created event must be passed to target',
        );
    }

    #[Test]
    public function exposesFileName(): void
    {
        self::assertSame(
            'config/services.php',
            (new FileCreated('config/services.php'))->name(),
            'FileCreated must expose the file name it was created with',
        );
    }
}
