<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project;

use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\PiquleException;

final readonly class InitializedProject implements Project
{
    public function __construct(
        private Output $output,
    ) {}

    public function init(): void
    {
        throw new PiquleException('Project is already initialized');
    }

    public function update(): void
    {
        $this->output->write(new Error('Cannot update uninitialized project'));
    }
}
