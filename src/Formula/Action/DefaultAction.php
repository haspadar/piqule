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
    public function __construct(
        private string $raw,
    ) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        if ($args->values() !== []) {
            return $args;
        }

        return new UnquotedArgs(
            new ParsedArgs(
                new ListArgs([$this->raw]),
            ),
        );
    }
}
