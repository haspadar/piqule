<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Haspadar\Piqule\File\TextFile;
use Override;

final readonly class TextFiles implements Files
{
    /**
     * @param array<string, string> $files
     */
    public function __construct(
        private array $files,
    ) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->files as $path => $contents) {
            yield new TextFile($path, $contents);
        }
    }
}
