<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\File\Target;

use Haspadar\Piqule\File\Event\FileCreated;
use Haspadar\Piqule\File\Event\FileUpdated;
use Haspadar\Piqule\File\Target\FileTarget;

final class FakeTarget implements FileTarget
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

    /**
     * @return list<object>
     */
    public function events(): array
    {
        return $this->events;
    }
}
