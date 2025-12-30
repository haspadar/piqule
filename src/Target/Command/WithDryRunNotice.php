<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Command;

use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Override;

final readonly class WithDryRunNotice implements Command
{
    public function __construct(
        private Command $origin,
        private Output  $output,
    ) {}

    #[Override]
    public function run(): void
    {
        $this->output->write(
            new Text(
                "DRY RUN started — no files were written\n",
                new Yellow(),
            ),
        );

        $this->origin->run();

        $this->output->write(
            new Text(
                "\nDRY RUN completed",
                new Yellow(),
            ),
        );
    }
}
