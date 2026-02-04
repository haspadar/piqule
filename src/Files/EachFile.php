<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Closure;
use Haspadar\Piqule\File\File;

final readonly class EachFile
{
    /**
     * @param Closure(File): void $action
     */
    public function __construct(
        private Files $files,
        private Closure $action,
    ) {}

    public function run(): void
    {
        foreach ($this->files->all() as $file) {
            ($this->action)($file);
        }
    }
}
