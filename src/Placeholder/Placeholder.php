<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholder;

interface Placeholder
{
    /**
     * Returns the full placeholder expression as it appears in the file
     * Example: {{ COVERAGE_RANGE | default("80...100") }}
     */
    public function expression(): string;

    /**
     * Returns the replacement value
     */
    public function replacement(): string;
}
