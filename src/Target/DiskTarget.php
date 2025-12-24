<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Source\Source;
use Haspadar\Piqule\Target\Storage\TargetStorage;

final readonly class DiskTarget implements Target
{
    public function __construct(
        private Source $source,
        private TargetStorage $target,
    ) {}

    public function exists(): bool
    {
        return $this->target->exists($this->source->id());
    }

    public function materialize(): void
    {
        $this->target->write(
            $this->source->id(),
            $this->source->file(),
        );
    }

    public function id(): string
    {
        return $this->source->id();
    }

    public function source(): Source
    {
        return $this->source;
    }

    public function file(): File
    {
        return $this->target->read(
            $this->source->id(),
        );
    }
}
