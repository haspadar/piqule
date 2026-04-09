<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Check;

use Haspadar\Piqule\Config\Config;
use Override;

/**
 * Yields only checks that are not explicitly disabled via config.
 */
final readonly class EnabledChecks implements Checks
{
    /** Initializes with a check collection and project configuration. */
    public function __construct(private Checks $origin, private Config $config) {}

    #[Override]
    public function all(): iterable
    {
        foreach ($this->origin->all() as $check) {
            $key = $check->name() . '.cli';

            if ($this->config->has($key) && !(bool) ($this->config->list($key)[0] ?? true)) {
                continue;
            }

            yield $check;
        }
    }
}
