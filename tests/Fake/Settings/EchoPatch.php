<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Fake\Settings;

use Haspadar\Piqule\Settings\Patch;
use Haspadar\Piqule\Settings\Value\Value;

final readonly class EchoPatch implements Patch
{
    public function __construct(private string $key) {}

    public function key(): string
    {
        return $this->key;
    }

    public function applied(Value $base): Value
    {
        return $base;
    }
}
