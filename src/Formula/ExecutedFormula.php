<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula;

use Haspadar\Piqule\Formula\Actions\Actions;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\PiquleException;
use Override;

final readonly class ExecutedFormula implements Formula
{
    public function __construct(
        private Actions $actions,
    ) {}

    #[Override]
    public function result(): string
    {
        $args = new ListArgs([]);

        foreach ($this->actions->all() as $action) {
            $args = $action->transformed($args);
        }

        $values = $args->values();

        return match (count($values)) {
            0 => '',
            1 => (string) $values[0],
            default => throw new PiquleException(
                'Formula must reduce to a single value, use join() to reduce list',
            ),
        };
    }
}
