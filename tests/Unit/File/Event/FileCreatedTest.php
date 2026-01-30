<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\File\Event;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\Tests\Unit\Fake\File\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileCreatedTest extends TestCase
{
    #[Test]
    public function passesCreatedEventToReaction(): void
    {
        $reaction = new FakeFileReaction();

        (new FileCreated('config/app.php'))->passTo($reaction);

        self::assertCount(
            1,
            $reaction->events(),
            'Created event must be passed to reaction',
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
