<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Haspadar\Piqule\Config\Config;
use Override;

/**
 * Discovers available checks from config keys ending in ".cli".
 */
final readonly class ConfigChecks implements Checks
{
    private const int CLI_SUFFIX_LENGTH = 4;

    /** Initializes with project configuration and root path. */
    public function __construct(private Config $config, private string $root) {}

    #[Override]
    public function all(): iterable
    {
        foreach (array_keys($this->config->toArray()) as $key) {
            if (!str_ends_with($key, '.cli')) {
                continue;
            }

            $name = substr($key, 0, -self::CLI_SUFFIX_LENGTH);
            $check = new ConfigCheck($name, $this->root);

            if (file_exists($check->command())) {
                yield $check;
            }
        }
    }
}
