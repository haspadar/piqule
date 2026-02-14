<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Actions;

use Haspadar\Piqule\Formula\Action\Action;
use Override;

final readonly class ParsedActions implements Actions
{
    public function __construct(
        private string $expression,
        private array  $actions,
    ) {}

    #[Override]
    public function all(): array
    {
        preg_match_all(
            '/([a-z_]+)\(([^)]*)\)/',
            $this->expression,
            $matches,
            PREG_SET_ORDER,
        );

        if ($matches === []) {
            return [];
        }

        return array_map(
            fn(array $m): Action => $this->actions[$m[1]]($m[2]),
            $matches,
        );
    }
}
