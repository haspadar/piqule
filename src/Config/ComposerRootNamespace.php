<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Config;

/**
 * Root namespace from the PSR-4 autoload section of composer.json
 */
final readonly class ComposerRootNamespace
{
    public function __construct(private string $path) {}

    public function toString(): string
    {
        if (!is_file($this->path)) {
            return '';
        }

        $contents = file_get_contents($this->path);

        if ($contents === false) {
            return '';
        }

        /** @var array{autoload?: array{psr-4?: array<string, string>}} $data */
        $data = json_decode($contents, true) ?? [];
        $psr4 = $data['autoload']['psr-4'] ?? [];

        if ($psr4 === []) {
            return '';
        }

        return rtrim(array_key_first($psr4), '\\');
    }
}
