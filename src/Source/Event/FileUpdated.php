<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source\Event;

use Haspadar\Piqule\Source\Reaction\FileReaction;
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
    public function passTo(FileReaction $reaction): void
    {
        $reaction->updated($this);
    }
}
