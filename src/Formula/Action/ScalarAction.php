<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use Override;

final readonly class ScalarAction implements Action
{
    #[Override]
    public function transformed(Args $args): Args
    {
        $values = $args->values();

        if ($values === []) {
            return new ListArgs([]);
        }

        if (count($values) > 1) {
            throw new PiquleException(
                'Cannot convert list to scalar: more than one value',
            );
        }

        return new ListArgs([$values[0]]);
    }
}
