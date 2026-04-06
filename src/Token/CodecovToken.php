<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Token;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Codecov coverage upload token
 */
final readonly class CodecovToken implements Token
{
    #[Override]
    public function secret(): string
    {
        return 'CODECOV_TOKEN';
    }

    #[Override]
    public function url(string $org): string
    {
        return "https://app.codecov.io/account/gh/{$org}/repositories";
    }

    /** @throws PiquleException */
    #[Override]
    public function enabled(Config $config): bool
    {
        if (!$config->has('phpunit.enabled')) {
            return true;
        }

        return filter_var(
            $config->list('phpunit.enabled')[0] ?? true,
            FILTER_VALIDATE_BOOLEAN,
        );
    }
}
