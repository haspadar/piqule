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
    private string $template;

    public function __construct(string $raw)
    {
        $args = new UnquotedArgs(new ListArgs([$raw]));
        $values = $args->values();

        $this->template = $values[0] ?? '';
    }

    #[Override]
    public function transformed(Args $args): Args
    {
        if ($this->template === '') {
            return new ListArgs([]);
        }

        $stringified = new StringifiedArgs($args);

        return new ListArgs(
            array_map(
                fn(string $item): string => sprintf($this->template, $item),
                $stringified->values(),
            ),
        );
    }
}
