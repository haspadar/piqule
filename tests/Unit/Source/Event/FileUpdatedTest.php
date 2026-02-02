<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Source\Event;

use Haspadar\Piqule\Source\Event\FileUpdated;
use Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction\FakeFileReaction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileUpdatedTest extends TestCase
{
    #[Test]
    public function passesUpdatedEventToReaction(): void
    {
        $reaction = new FakeFileReaction();

        (new FileUpdated('config/cache.php'))->passTo($reaction);

        self::assertCount(
            1,
            $reaction->events(),
            'Updated event must be passed to reaction',
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
