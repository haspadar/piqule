<?php

declare(strict_types=1);

namespace Haspadar\Piqule\EnvVar;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\PiquleException;
use Override;

/**
 * SonarCloud scanner token for local analysis
 */
final readonly class SonarEnvVar implements EnvVar
{
    #[Override]
    public function name(): string
    {
        return 'SONAR_TOKEN';
    }

    #[Override]
    public function url(): string
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
