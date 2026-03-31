<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Actions;

use Haspadar\Piqule\Formula\Action\Action;

/**
 * An ordered sequence of DSL pipeline actions
 */
interface Actions
{
    /** @return list<Action> */
    public function all(): array;
}
