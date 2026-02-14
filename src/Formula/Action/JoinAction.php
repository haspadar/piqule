<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;

final readonly class JoinAction implements Action
{
    private string $delimiter;

    public function __construct(string $raw)
    {
        $values = (new UnquotedArgs(new ListArgs([$raw])))->values();
        $this->delimiter = (string) $values[0];
    }

    public function transformed(Args $args): Args
    {
        $items = $args->values();

        if ($items === []) {
            return new ListArgs(['']);
        }

        return new ListArgs([implode($this->delimiter, $items)]);
    }
}
