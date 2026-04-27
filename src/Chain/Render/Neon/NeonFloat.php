<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Chain\Render\Neon;

use Haspadar\Piqule\Chain\Rendered;
use Haspadar\Piqule\Settings\Value\FloatValue;
use Override;

/**
 * Renders a FloatValue as a neon floating-point literal.
 *
 * Example:
 *
 *     (new NeonFloat(new FloatValue(0.5)))->rendered(); // "0.5"
 */
final readonly class NeonFloat implements Rendered
{
    /**
     * Initializes with the value to render.
     *
     * @param FloatValue $value Float payload rendered as a neon literal
     */
    public function __construct(private FloatValue $value) {}

    #[Override]
    public function rendered(): string
    {
        return (string) $this->value->raw;
    }
}
