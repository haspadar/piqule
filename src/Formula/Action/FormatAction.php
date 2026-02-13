<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Override;

final readonly class FormatAction implements Action
{
    public function __construct(
        private Args $template,
    ) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        $templateValues = $this->template->values();

        if ($templateValues === []) {
            return new ListArgs([]);
        }

        $template = (string) $templateValues[0];

        $formatted = array_map(
            static fn(int|float|string|bool $item): string =>
            sprintf($template, $item),
            $args->values(),
        );

        return new ListArgs($formatted);
    }
}
