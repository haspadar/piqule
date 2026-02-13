<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
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

        $stringified = new StringifiedArgs($args);

        $formatted = array_map(
            static fn(string $item): string => sprintf($template, $item),
            $stringified->values(),
        );

        return new ListArgs($formatted);
    }
}
