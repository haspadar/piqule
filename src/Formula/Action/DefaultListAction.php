<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Override;

final readonly class DefaultListAction implements Action
{
    public function __construct(private Args $default) {}

    #[Override]
    public function apply(Args $args): Args
    {
        if ($args->list() === []) {
            return $this->default;
        }

        return $args;
    }
}
