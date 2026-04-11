<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

/**
 * Paths used to locate configuration files.
 */
final readonly class ConfigPaths
{
    /** Initializes with optional custom paths for composer.json and config.yaml. */
    public function __construct(
        private string $composer = '',
        private string $config = __DIR__ . '/../../templates/always/.piqule/config.yaml',
    ) {}

    /** Returns the composer.json file path. */
    public function composerJson(): string
    {
        return $this->composer;
    }

    /** Returns the config.yaml file path. */
    public function configYaml(): string
    {
        return $this->config;
    }
}
