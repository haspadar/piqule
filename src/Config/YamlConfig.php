<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads project configuration from a .piqule.yaml file
 *
 * Supports two sections:
 * - override: replaces default values
 * - append: adds values to existing lists
 *
 * Example .piqule.yaml:
 *
 *     override:
 *         phpstan.level: 8
 *     append:
 *         phpstan.neon_includes:
 *             - ../../rules.neon
 *         exclude:
 *             - legacy
 */
final readonly class YamlConfig implements Config
{
    private Config $config;

    /** @throws PiquleException */
    public function __construct(string $path, Config $defaults)
    {
        /** @var array{override?: array<string, scalar>, append?: array<string, list<scalar>>}|null $data */
        $data = Yaml::parseFile($path);

        if (!is_array($data)) {
            throw new PiquleException(
                sprintf('Invalid configuration file "%s": expected a mapping', $path),
            );
        }

        /** @var array<string, scalar|list<scalar>> $overrides */
        $overrides = $data['override'] ?? [];
        /** @var array<string, list<scalar>> $appends */
        $appends = $data['append'] ?? [];

        $this->config = new AppendConfig(
            new OverrideConfig($defaults, $overrides),
            $appends,
        );
    }

    #[Override]
    public function has(string $name): bool
    {
        return $this->config->has($name);
    }

    /**
     * @throws PiquleException
     * @return list<scalar>
     */
    #[Override]
    public function list(string $name): array
    {
        return $this->config->list($name);
    }

    #[Override]
    public function toArray(): array
    {
        return $this->config->toArray();
    }
}
