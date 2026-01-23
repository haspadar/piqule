<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Storage\Storage;
use Override;

final readonly class InlineFile implements File
{
    public function __construct(
        private string $name,
        private string $contents,
    ) {}

    #[Override]
    public function name(): string
    {
        return $this->name;
    }

    #[Override]
    public function contents(): string
    {
        return $this->contents;
    }

    #[Override]
    public function writeTo(Storage $storage): void
    {
        $storage->write(
            $this->name,
            $this->contents,
        );
    }
}
