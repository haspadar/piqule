<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Actions;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\PiquleException;

/**
 * An ordered sequence of DSL pipeline actions
 */
interface Actions
{
    /**
     * Returns the ordered list of actions.
     *
     * @throws PiquleException
     * @return list<Action>
     */
    public function all(): array;
}
