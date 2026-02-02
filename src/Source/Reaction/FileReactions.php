<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Source\Reaction;

use Haspadar\Piqule\Source\Event\FileCreated;
use Haspadar\Piqule\Source\Event\FileSkipped;
use Haspadar\Piqule\Source\Event\FileUpdated;
use Override;

final readonly class FileReactions implements FileReaction
{
    /**
     * @param list<FileReaction> $reactions
     */
    public function __construct(
        private array $reactions,
    ) {}

    #[Override]
    public function created(FileCreated $event): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->created($event);
        }
    }

    #[Override]
    public function updated(FileUpdated $event): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->updated($event);
        }
    }

    #[Override]
    public function skipped(FileSkipped $event): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->skipped($event);
        }
    }

    #[Override]
    public function executableAlreadySet(string $name): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->executableAlreadySet($name);
        }
    }

    #[Override]
    public function executableWasSet(string $name): void
    {
        foreach ($this->reactions as $reaction) {
            $reaction->executableWasSet($name);
        }
    }
}
