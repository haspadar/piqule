<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\ParsedArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Override;

final readonly class DefaultAction implements Action
{
    private Args $default;

    public function __construct(string $raw)
    {
        $this->default = new UnquotedArgs(
            new ParsedArgs(
                new ListArgs([$raw]),
            ),
        );
    }

    #[Override]
    public function transformed(Args $args): Args
    {
        if ($args->values() === []) {
            return $this->default;
        }

        return $args;
    }
}
