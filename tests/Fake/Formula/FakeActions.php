<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Formula;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Actions\Actions;
use Override;

final readonly class FakeActions implements Actions
{
    /**
     * @param list<Action> $actions
     */
    public function __construct(
        private array $actions,
    ) {}

    /**
     * @return list<Action>
     */
    #[Override]
    public function all(): array
    {
        return $this->actions;
    }
}