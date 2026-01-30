<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\File\Reaction;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileSkipped;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Reaction\FileReaction;

final class FakeFileReaction implements FileReaction
{
    /** @var list<object> */
    private array $events = [];

    public function created(FileCreated $event): void
    {
        $this->events[] = $event;
    }

    public function updated(FileUpdated $event): void
    {
        $this->events[] = $event;
    }

    public function skipped(FileSkipped $event): void
    {
        $this->events[] = $event;
    }

    public function executableAlreadySet(string $name): void
    {
        $this->events[] = $name;
    }

    public function executableWasSet(string $name): void
    {
        $this->events[] = $name;
    }

    /**
     * @return list<object>
     */
    public function events(): array
    {
        return $this->events;
    }
}
