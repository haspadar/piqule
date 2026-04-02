<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;
use Symfony\Component\Yaml\Exception\ParseException;
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
    public function __construct(string $path, DefaultConfig $defaults)
    {
        try {
            $data = Yaml::parseFile($path);
        } catch (ParseException $e) {
            throw new PiquleException(
                sprintf('Failed to parse "%s": %s', $path, $e->getMessage()),
                0,
                $e,
            );
        }

        if (!is_array($data)) {
            throw new PiquleException(
                sprintf('Invalid configuration file "%s": expected a mapping', $path),
            );
        }

        /** @var array<string, mixed> $overrides */
        $overrides = isset($data['override']) && is_array($data['override'])
            ? $data['override']
            : [];
        /** @var array<string, mixed> $appends */
        $appends = isset($data['append']) && is_array($data['append'])
            ? $data['append']
            : [];

        /** @var list<string> $include */
        $include = isset($overrides['php.src']) && is_array($overrides['php.src'])
            ? $overrides['php.src']
            : $defaults->list('php.src');

        /** @var list<string> $exclude */
        $exclude = isset($overrides['exclude']) && is_array($overrides['exclude'])
            ? $overrides['exclude']
            : $defaults->list('exclude');

        if (isset($appends['exclude']) && is_array($appends['exclude'])) {
            /** @var list<string> $extra */
            $extra = $appends['exclude'];
            $exclude = array_values(array_unique(array_merge($exclude, $extra)));
        }

        if (isset($appends['php.src']) && is_array($appends['php.src'])) {
            /** @var list<string> $extra */
            $extra = $appends['php.src'];
            $include = array_values(array_unique(array_merge($include, $extra)));
        }

        $pathKeys = ['exclude', 'php.src'];

        $this->config = new AppendConfig(
            new OverrideConfig(
                new DefaultConfig($include, $exclude, $defaults->composerJson()),
                array_diff_key($overrides, array_flip($pathKeys)),
            ),
            array_diff_key($appends, array_flip($pathKeys)),
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

    /** @throws PiquleException */
    #[Override]
    public function toArray(): array
    {
        return $this->config->toArray();
    }
}
