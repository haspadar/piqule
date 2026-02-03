<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Override;

final readonly class PrefixedFile implements File
{
    public function __construct(
        private string $prefix,
        private File $origin,
    ) {}

    #[Override]
    /**
     * Builds the target file path by prefixing the original name
     *
     * Examples:
     * - prefix ".git" + name "hooks/pre-push" → ".git/hooks/pre-push"
     * - prefix ""     + name "config/app.yaml" → "config/app.yaml"
     *
     * Leading and trailing slashes are normalized
     */
    public function name(): string
    {
        $prefix = rtrim($this->prefix, '/');
        $name = ltrim($this->origin->name(), '/');

        return $prefix === ''
            ? $name
            : $prefix . '/' . $name;
    }

    #[Override]
    public function contents(): string
    {
        return $this->origin->contents();
    }
}
