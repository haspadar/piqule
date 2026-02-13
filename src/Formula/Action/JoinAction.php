<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Override;

final readonly class JoinAction implements Action
{
    public function __construct(
        private Args $delimiter,
    ) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $items = $args->values();

        if ($items === []) {
            return new ListArgs(['']);
        }

        return new ListArgs([
            implode(
                (string) $this->delimiter->values()[0],
                $items,
            ),
        ]);
    }
}
