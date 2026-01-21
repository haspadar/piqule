<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Artifact;

use Haspadar\Piqule\Artifact\File;

final readonly class FakeFile implements File
{
    public function __construct(
        private string $contents,
        private string $id = 'fake:file',
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function contents(): string
    {
        return $this->contents;
    }
}
