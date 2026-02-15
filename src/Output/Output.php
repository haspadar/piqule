<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Output;

interface Output
{
    public function info(string $text): void;

    public function success(string $text): void;

    public function error(string $text): void;
}
