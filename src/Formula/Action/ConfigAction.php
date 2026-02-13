<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Action;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\Formula\Args\Args;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Formula\Args\StringifiedArgs;
use Override;

final readonly class ConfigAction implements Action
{
    public function __construct(
        private Config $config,
        private string $key,
    ) {}

    #[Override]
    public function transformed(Args $args): Args
    {
        return new StringifiedArgs(
            new ListArgs($this->config->values($this->key)),
        );
    }
}
