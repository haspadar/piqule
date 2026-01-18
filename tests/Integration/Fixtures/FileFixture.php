<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Integration\Fixtures;

use Haspadar\Piqule\File\File;

final readonly class FileFixture implements File
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
