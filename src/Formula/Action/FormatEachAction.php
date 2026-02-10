<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ArrayArgs;
use Override;

final readonly class FormatEachAction implements Action
{
    public function __construct(
        private Args $template,
    ) {}

    #[Override]
    public function apply(Args $args): Args
    {
        $formatted = array_map(
            fn(string $item): string => sprintf(
                $this->template->text(),
                $item,
            ),
            $args->list(),
        );

        return new ArrayArgs($formatted);
    }
}
