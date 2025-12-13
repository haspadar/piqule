<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Command;

use Haspadar\Piqule\FileSystem\FileSystem;
use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Structure\Directory;
use Haspadar\Piqule\Structure\Root;
use Haspadar\Piqule\Structure\Templates;

final readonly class Update implements Command
{
    public function __construct(
        private string $templates,
        private string $root,
        private FileSystem $fileSystem,
        private Output $output,
    ) {}

    public function run(): void
    {
        $this->output->write(new Error('Update not implemented yet'));
    }
}
