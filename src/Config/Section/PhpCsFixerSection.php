<?php

declare(strict_types = 1);

namespace Haspadar\Piqule\Config\Section;

use Override;

/**
 * Default configuration for PHP-CS-Fixer
 */
final readonly class PhpCsFixerSection implements ConfigSection
{
    /** @param list<string> $excludes */
    public function __construct(private array $excludes) {}

    #[Override]
    public function toArray(): array
    {
        return [
            'php_cs_fixer.allow_unsupported' => ['true'],
            'php_cs_fixer.exclude' => $this->excludes,
            'php_cs_fixer.paths' => ['../..'],
            'php-cs-fixer.enabled' => true,
        ];
    }
}
