<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Override;

final readonly class JoinAction implements Action
{
    public function __construct(
        private Args $delimiter,
    ) {}

    #[Override]
    public function apply(Args $args): Args
    {
        $items = $args->list();

        if ($items === []) {
            return new RawArgs('');
        }

        return new RawArgs(
            implode(
                $this->delimiter->text(),
                $items,
            ),
        );
    }
}
