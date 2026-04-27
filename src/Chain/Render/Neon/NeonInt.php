<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Chain\Render\Neon;

use Haspadar\Piqule\Chain\Rendered;
use Haspadar\Piqule\Settings\Value\IntValue;
use Override;

/**
 * Renders an IntValue as a neon integer literal.
 *
 * Example:
 *
 *     (new NeonInt(new IntValue(8)))->rendered(); // "8"
 */
final readonly class NeonInt implements Rendered
{
    /**
     * Initializes with the value to render.
     *
     * @param IntValue $value Integer payload rendered as a neon literal
     */
    public function __construct(private IntValue $value) {}

    #[Override]
    public function rendered(): string
    {
        return (string) $this->value->raw;
    }
}
