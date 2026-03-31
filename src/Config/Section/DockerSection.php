<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for Docker infrastructure
 */
final readonly class DockerSection implements ConfigSection
{
    #[Override]
    public function toArray(): array
    {
        return [
            'docker.image' => 'ghcr.io/haspadar/piqule-infra@sha256:a7d41e9fef08156778df6f9172145970a617962bee9e17f1484ebc9b41f6ac29',
        ];
    }
}
