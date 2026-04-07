<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Secret;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Codecov coverage upload token
 */
final readonly class CodecovSecret implements Secret
{
    #[Override]
    public function name(): string
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
            FILTER_NULL_ON_FAILURE,
        ) ?? true;
    }
}
