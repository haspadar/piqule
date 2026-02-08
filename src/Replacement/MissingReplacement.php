<?php
declare(strict_types=1);

namespace Haspadar\Piqule\Replacement;

use Haspadar\Piqule\PiquleException;

final readonly class MissingReplacement implements Replacement
{
    public function value(): string
    {
        throw new PiquleException(
            'Replacement is missing and no default was provided',
        );
    }

    public function withDefault(Replacement $default): Replacement
    {
        return $default;
    }
}
