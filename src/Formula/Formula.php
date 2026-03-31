<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Formula;

/**
 * A DSL expression that resolves to a scalar string
 */
interface Formula
{
    /**
     * Evaluates the expression and returns the resulting string
     */
    public function result(): string;
}
