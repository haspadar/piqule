<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

use Haspadar\Piqule\PiquleException;
use Override;

/**
 * Loads project configuration from .piqule.yaml, .piqule.php, or defaults.
 *
 * Example:
 *
 *     new ProjectConfig('/path/to/project');
 */
final class ProjectConfig implements Config
{
    private ?Config $cache;

    /** Initializes with the project root directory path. */
    public function __construct(private readonly string $root)
    {
        $this->cache = null;
    }

    #[Override]
    public function has(string $name): bool
    {
        return $this->config()->has($name);
    }

    #[Override]
    public function list(string $name): array
    {
        return $this->config()->list($name);
    }

    #[Override]
    public function toArray(): array
    {
        return $this->config()->toArray();
    }

    /**
     * Resolves configuration from .piqule.yaml, .piqule.php, or defaults.
     *
     * @throws PiquleException
     */
    private function config(): Config
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $defaults = new DefaultConfig(
            [],
            [],
            new ConfigPaths($this->root . '/composer.json'),
        );

        $yamlPath = $this->root . '/.piqule.yaml';
        $phpPath = $this->root . '/.piqule.php';

        if (file_exists($yamlPath)) {
            $this->cache = new YamlConfig($yamlPath, $defaults);
        } elseif (file_exists($phpPath)) {
            $loaded = require $phpPath;

            if (!$loaded instanceof Config) {
                throw new PiquleException('.piqule.php must return an instance of Config');
            }

            $this->cache = $loaded;
        } else {
            $this->cache = $defaults;
        }

        return $this->cache;
    }
}
