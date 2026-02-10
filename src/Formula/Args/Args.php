<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Formula\Args;

/**
 * Represents arguments of a single DSL action
 *
 * Exposes arguments in explicit DSL forms
 * Does not perform semantic interpretation
 */
interface Args
{
    /**
     * Returns argument in linear DSL form
     */
    public function text(): string;

    /**
     * Returns argument parsed as PHP-style list literal
     *
     * @return list<string>
     */
    public function list(): array;
}
