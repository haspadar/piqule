<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\Artifact\File;
use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Target\Storage\TargetStorage;
use Override;

final readonly class DiskTarget implements Target
{
    public function __construct(
        private Source $source,
        private TargetStorage $target,
    ) {}

    #[Override]
    public function exists(): bool
    {
        return $this->target->exists($this->source->id());
    }

    #[Override]
    public function materialize(): void
    {
        $this->target->write(
            $this->source->id(),
            $this->source->file(),
        );
    }

    #[Override]
    public function id(): string
    {
        return $this->source->id();
    }

    #[Override]
    public function file(): File
    {
        return $this->target->read(
            $this->source->id(),
        );
    }
}
