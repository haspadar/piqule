<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\File;

use Haspadar\Piqule\File\File;

final readonly class FakeFile implements File
{
    public function __construct(
        private string $contents,
    ) {}

    public function contents(): string
    {
        return $this->contents;
    }
}
