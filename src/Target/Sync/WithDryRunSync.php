<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Target\Sync;

use Haspadar\Piqule\Output\Color\Yellow;
use Haspadar\Piqule\Output\Line\Text;
use Haspadar\Piqule\Output\Output;
use Haspadar\Piqule\Target\Storage\TargetStorage;
use Override;

final readonly class WithDryRunSync implements Sync
{
    public function __construct(
        private Sync $origin,
        private Output  $output,
    ) {}

    #[Override]
    public function apply(TargetStorage $targetStorage): void
    {
        $this->output->write(
            new Text(
                "DRY RUN started — no files were written\n",
                new Yellow(),
            ),
        );

        $this->origin->apply($targetStorage);

        $this->output->write(
            new Text(
                "\nDRY RUN completed",
                new Yellow(),
            ),
        );
    }
}
