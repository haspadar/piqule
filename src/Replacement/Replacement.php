<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Replacement;

interface Replacement
{
    public function value(): string;

    public function withDefault(self $default): self;
}
