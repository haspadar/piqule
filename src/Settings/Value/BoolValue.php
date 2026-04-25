<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Settings\Value;

/**
 * Configuration value holding a boolean.
 *
 * Example:
 *
 *     new BoolValue(true);
 */
final readonly class BoolValue implements ScalarValue
{
    /**
     * Initializes with the boolean payload.
     *
     * @param bool $raw Boolean carried by this value
     */
    public function __construct(public bool $raw) {}
}
