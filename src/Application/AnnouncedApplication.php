<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Application;

use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Override;

final readonly class AnnouncedApplication implements Application
{
    public function __construct(
        private Application $origin,
        private Output $output,
        private Text $start,
        private Text $end,
    ) {}

    #[Override]
    public function run(): void
    {
        $this->output->write($this->start);
        $this->origin->run();
        $this->output->write($this->end);
    }
}
