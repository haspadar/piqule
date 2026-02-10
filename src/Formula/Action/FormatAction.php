<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\RawArgs;
use Override;

final readonly class FormatAction implements Action
{
    public function __construct(private Args $template) {}

    #[Override]
    public function apply(Args $args): Args
    {
        return new RawArgs(
            sprintf(
                $this->template->text(),
                $args->text(),
            ),
        );
    }
}
