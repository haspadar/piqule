<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target;

use Haspadar\Piqule\Source\SourceFile;

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

    public function hashDiffers(): bool
    {
        if (!$this->exists()) {
            return true;
        }

        $targetFile = $this->target->read(
            $this->source->relativePath(),
        );

        return $targetFile->hash() !== $this->source->file()->hash();
    }
}
