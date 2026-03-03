<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Formula;

use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;

final readonly class FakeAction implements Action
{
    /**
     * @param list<int|float|string|bool> $result
     */
    public function __construct(private array $result) {}

    public function transformed(Args $args): Args
    {
        return new ListArgs($this->result);
    }
}
