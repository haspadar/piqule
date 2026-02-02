<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source\Event;

use Haspadar\Piqule\Source\Reaction\FileReaction;
use Override;

final readonly class FileSkipped implements FileEvent
{
    public function __construct(private string $name) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param FileReaction $reaction
     */
    #[Override]
    public function passTo(FileReaction $reaction): void
    {
        $reaction->skipped($this);
    }
}
