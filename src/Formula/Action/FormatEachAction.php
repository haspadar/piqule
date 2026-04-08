<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Haspadar\Piqule\Formula\Args\UnquotedArgs;
use Override;

/**
 * Applies a sprintf template to each incoming value individually.
 */
final readonly class FormatEachAction implements Action
{
    /** Initializes with the raw sprintf template string. */
    public function __construct(private string $raw) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $templateArgs = new UnquotedArgs(new ListArgs([$this->raw]));
        $templateValues = $templateArgs->values();
        $template = (string) ($templateValues[0] ?? '');

        return new ListArgs(
            array_map(
                static fn(int|float|string|bool $item): string => sprintf($template, $item),
                (new StringifiedArgs($args))->values(),
            ),
        );
    }
}
