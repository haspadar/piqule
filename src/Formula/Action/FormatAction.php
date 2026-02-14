<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Override;

final readonly class FormatAction implements Action
{
    public function __construct(
        private string $raw,
    ) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $templateArgs = new UnquotedArgs(new ListArgs([$this->raw]));
        $templateValues = $templateArgs->values();
        $template = (string) $templateValues[0];

        return new ListArgs(
            array_map(
                static fn(string $item): string => sprintf($template, $item),
                (new StringifiedArgs($args))->values(),
            ),
        );
    }
}
