<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Token;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;

/**
 * GitHub Secret required by an external service
 */
interface Token
{
    public function secret(): string;

    public function url(string $org): string;

    /**
     * @throws PiquleException
     */
    public function enabled(Config $config): bool;
}
