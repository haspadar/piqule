<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

final readonly class CopiedFile
{
    public function __construct(
        public string $relativePath,
        public string $absolutePath,
        public string $templatePath,
    ) {}
}
