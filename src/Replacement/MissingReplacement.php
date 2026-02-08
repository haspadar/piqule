<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Replacement;

use Haspadar\Piqule\PiquleException;
use Override;

final readonly class MissingReplacement implements Replacement
{
    #[Override]
    public function value(): string
    {
        throw new PiquleException(
            'Replacement is missing and no default was provided',
        );
    }

    #[Override]
    public function withDefault(Replacement $default): Replacement
    {
        return $default;
    }
}
