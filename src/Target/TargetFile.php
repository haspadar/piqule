<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\Source\SourceFile;
use Haspadar\Piqule\Target\TargetState\ChangedTarget;
use Haspadar\Piqule\Target\TargetState\MissingTarget;
use Haspadar\Piqule\Target\TargetState\TargetState;
use Haspadar\Piqule\Target\TargetState\UnchangedTarget;

final class TargetFile
{
    public function __construct(
        private SourceFile $source,
        private TargetDirectory $target,
    ) {}

    public function exists(): bool
    {
        return $this->target->exists($this->source->relativePath());
    }

    public function materialize(): void
    {
        $this->target->write(
            $this->source->relativePath(),
            $this->source->file(),
        );
    }

    public function relativePath(): string
    {
        return $this->source->relativePath();
    }

    public function state(): TargetState
    {
        if (!$this->exists()) {
            return new MissingTarget();
        }

        $targetFile = $this->target->read($this->relativePath());
        if ($targetFile->hash() !== $this->source->file()->hash()) {
            return new ChangedTarget();
        }

        return new UnchangedTarget();
    }
}
