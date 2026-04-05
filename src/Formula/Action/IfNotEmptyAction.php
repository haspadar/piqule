<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Override;

/**
 * Passes non-empty input through, clears empty input
 */
final readonly class IfNotEmptyAction implements Action
{
    #[Override]
    public function transformed(Args $args): Args
    {
        $values = $args->values();

        if ($values === [] || $values === ['']) {
            return new ListArgs([]);
        }

        return $args;
    }
}
