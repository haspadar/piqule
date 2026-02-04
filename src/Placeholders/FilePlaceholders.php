<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

final readonly class FilePlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/\{\{\s*(?<expression>[A-Z0-9_]+\s*\|\s*default\((?<value>[^)]*)\))\s*}}/',
            $this->file->contents(),
            $matches,
            PREG_SET_ORDER,
        );

        foreach ($matches as $match) {
            yield new DefaultPlaceholder(
                $match[0],
                $this->unquoted($match['value']),
            );
        }
    }

    private function unquoted(string $value): string
    {
        return preg_replace('/^(["\'])(.*)\1$/', '$2', $value) ?? $value;
    }
}
