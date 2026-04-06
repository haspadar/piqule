<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Token;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Stryker mutation testing dashboard token
 */
final readonly class InfectionToken implements Token
{
    #[Override]
    public function secret(): string
    {
        return 'STRYKER_DASHBOARD_API_KEY';
    }

    #[Override]
    public function url(string $org): string
    {
        // Stryker dashboard is org-agnostic
        return 'https://dashboard.stryker-mutator.io';
    }

    /** @throws PiquleException */
    #[Override]
    public function enabled(Config $config): bool
    {
        if (!$config->has('infection.enabled')) {
            return true;
        }

        return filter_var(
            $config->list('infection.enabled')[0] ?? true,
            FILTER_VALIDATE_BOOLEAN,
        );
    }
}
