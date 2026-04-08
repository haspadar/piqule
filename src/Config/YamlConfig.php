<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;
use Symfony\Component\Yaml\Exception\ParseException as YamlParseException;
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

    /**
     * @throws PiquleException
     * @throws YamlParseException
     */
    public function __construct(string $path, DefaultConfig $defaults)
    {
        try {
            $data = Yaml::parseFile($path);
        } catch (YamlParseException $e) {
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
        $overrides = array_key_exists('override', $data) && is_array($data['override'])
            ? $data['override']
            : [];
        /** @var array<string, mixed> $appends */
        $appends = array_key_exists('append', $data) && is_array($data['append'])
            ? $data['append']
            : [];

        $pathKeys = new YamlPathKeys($overrides, $appends, $defaults);
        $remaining = ['exclude', 'php.src'];

        $this->config = new AppendConfig(
            new OverrideConfig(
                new DefaultConfig(
                    $pathKeys->phpSrc(),
                    $pathKeys->exclude(),
                    $defaults->configPaths(),
                ),
                array_diff_key($overrides, array_flip($remaining)),
            ),
            array_diff_key($appends, array_flip($remaining)),
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
