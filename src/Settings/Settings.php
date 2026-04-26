<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Settings;

use Haspadar\Piqule\Settings\Value\Value;

/**
 * Read-only access to configuration values keyed by flat dot-notated names.
 */
interface Settings
{
    /**
     * Returns true if the key is declared in this configuration.
     */
    public function has(string $name): bool;

    /**
     * Returns the configuration value bound to the given key.
     *
     * Throws when the key is not declared.
     */
    public function value(string $name): Value;
}
