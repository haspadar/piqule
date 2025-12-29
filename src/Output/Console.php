<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output;

use Haspadar\Piqule\Output\Line\Line;

final readonly class Console implements Output
{
    #[\Override]
    public function write(Line $line): void
    {
        fwrite(
            $line->stream(),
            $line->color()->apply($line->text()) . "\n",
        );
    }
}
