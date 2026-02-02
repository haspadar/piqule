<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Source\Reaction;

use Haspadar\Piqule\Source\Event\FileCreated;
use Haspadar\Piqule\Source\Event\FileSkipped;
use Haspadar\Piqule\Source\Event\FileUpdated;
use Haspadar\Piqule\Source\Reaction\FileReaction;

final class FakeFileReaction implements FileReaction
{
    /** @var list<object|string> */
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
     * @return list<object|string>
     */
    public function events(): array
    {
        return $this->events;
    }
}
