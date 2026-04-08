<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Files;

use Closure;
use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Runnable;
use Override;

/**
 * Iterates over all files in a collection and applies a side-effectful action to each.
 */
final readonly class EachFile implements Runnable
{
    /**
     * Initializes with a file collection and an action to apply.
     *
     * @param Files $files File collection to iterate
     * @param Closure(File): void $action
     */
    public function __construct(private Files $files, private Closure $action) {}

    #[Override]
    public function run(): void
    {
        foreach ($this->files->all() as $file) {
            ($this->action)($file);
        }
    }
}
