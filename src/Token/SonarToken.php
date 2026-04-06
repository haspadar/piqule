<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Token;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;
use Override;

/**
 * SonarCloud analysis token
 */
final readonly class SonarToken implements Token
{
    #[Override]
    public function secret(): string
    {
        return 'SONAR_TOKEN';
    }

    #[Override]
    public function url(string $org): string
    {
        return 'https://sonarcloud.io/account/security';
    }

    /** @throws PiquleException */
    #[Override]
    public function enabled(Config $config): bool
    {
        if (!$config->has('sonar.enabled')) {
            return true;
        }

        return filter_var(
            $config->list('sonar.enabled')[0] ?? true,
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE,
        ) ?? true;
    }
}
