<?php

declare(strict_types=1);

namespace Haspadar\Piqule;

use Haspadar\Piqule\Output\Line\Error;
use Haspadar\Piqule\Output\Output;

final readonly class Update
{
    public function __construct(
        private Output $output,
    ) {}

    public function run(): void
    {
        $this->output->write(new Error('Update not implemented yet'));
    }
}
