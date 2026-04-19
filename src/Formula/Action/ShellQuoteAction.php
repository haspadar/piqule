<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Override;

/**
 * Wraps each value in POSIX single-quoted form for safe shell interpolation.
 */
final readonly class ShellQuoteAction implements Action
{
    #[Override]
    public function transformed(Args $args): Args
    {
        return new ListArgs(
            array_map(
                static fn(int|float|string|bool $item): string => sprintf(
                    "'%s'",
                    str_replace("'", "'\\''", (string) $item),
                ),
                (new StringifiedArgs($args))->values(),
            ),
        );
    }
}
