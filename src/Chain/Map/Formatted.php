<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Chain\Map;

use Haspadar\Piqule\Chain\Mapped;
use Haspadar\Piqule\Chain\Op;
use Override;

/**
 * Wraps an Op's rendered output into a sprintf template.
 *
 * The template is fed straight to PHP's sprintf, so it must contain exactly
 * one %s placeholder. Templates with no %s, several %s, or trailing % will
 * trigger ValueError or ArgumentCountError at render time.
 *
 * Example:
 *
 *     (new Formatted(new NeonBool(new BoolValue(true)), 'value: %s'))->rendered();
 *     // "value: true"
 */
final readonly class Formatted implements Mapped
{
    /**
     * Initializes with the source op and a sprintf template.
     *
     * @param Op $origin Source op whose rendered output is substituted into the template
     * @param string $template Sprintf template with exactly one %s placeholder
     */
    public function __construct(private Op $origin, private string $template) {}

    #[Override]
    public function rendered(): string
    {
        return sprintf($this->template, $this->origin->rendered());
    }
}
