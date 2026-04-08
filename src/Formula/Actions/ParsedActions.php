<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Actions;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Parses a DSL expression string into an ordered list of Action instances.
 */
final readonly class ParsedActions implements Actions
{
    /**
     * Initializes with a DSL expression and available action factories.
     *
     * @param array<string, callable(string): Action> $actions
     */
    public function __construct(private string $expression, private array $actions) {}

    /**
     * @throws PiquleException
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

            if (!array_key_exists($name, $this->actions)) {
                throw new PiquleException(
                    sprintf('Unknown formula action "%s"', $name),
                );
            }

            return ($this->actions[$name])($m[2]);
        }, $matches);
    }
}
