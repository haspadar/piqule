<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Secret;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;

/**
 * GitHub Secret required by a CI service
 */
interface Secret
{
    public function name(): string;

    public function url(string $org): string;

    /** @throws PiquleException */
    public function enabled(Config $config): bool;
}
