<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Override;

/**
 * Escapes each value for safe interpolation inside a JSON string literal.
 */
final readonly class JsonEscapeAction implements Action
{
    #[Override]
    public function transformed(Args $args): Args
    {
        return new ListArgs(
            array_map(
                static fn(int|float|string|bool $item): string => (string) preg_replace(
                    '/^"|"$/',
                    '',
                    (string) json_encode((string) $item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ),
                (new StringifiedArgs($args))->values(),
            ),
        );
    }
}
