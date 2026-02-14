<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Actions;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\PiquleException;
use Override;

final readonly class ParsedActions implements Actions
{
    /**
     * @param array<string, callable(string): Action> $actions
     */
    public function __construct(
        private string $expression,
        private array  $actions,
    ) {}

    /**
     * Parses DSL expression into a sequence of actions.
     *
     * DSL intentionally does not support nested parentheses
     * in action arguments. Arguments are treated as flat strings.
     *
     * @return list<Action>
     */
    #[Override]
    public function all(): array
    {
        preg_match_all(
            '/([a-z_]+)\(([^)]*)\)/',
            $this->expression,
            $matches,
            PREG_SET_ORDER,
        );

        return array_map(function (array $m): Action {
            $name = $m[1];

            if (!isset($this->actions[$name])) {
                throw new PiquleException(
                    sprintf('Unknown formula action "%s"', $name),
                );
            }

            return ($this->actions[$name])($m[2]);
        }, $matches);
    }
}
