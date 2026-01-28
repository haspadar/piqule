<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Application;

use Haspadar\Piqule\Application\Application;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;

final readonly class OutputtingApplication implements Application
{
    public function __construct(
        private Output $output,
        private Text $text,
    ) {}

    public function run(): void
    {
        $this->output->write($this->text);
    }
}
