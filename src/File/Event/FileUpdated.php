<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File\Event;

use Haspadar\Piqule\File\Target\FileTarget;
use Override;

final readonly class FileUpdated implements FileEvent
{
    public function __construct(
        private string $name,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function passTo(FileTarget $target): void
    {
        $target->updated($this);
    }
}
