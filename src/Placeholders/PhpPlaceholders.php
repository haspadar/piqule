<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

final readonly class PhpPlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/\[
                \s*(?:\'|")\$placeholder(?:\'|")\s*=>\s*(?:\'|")(?<name>[A-Z0-9_]+)(?:\'|")\s*,\s*
                (?:\'|")default(?:\'|")\s*=>\s*(?<default>
                    \[[^\]]*\]        # array
                    |
                    [^,\]]+           # scalar
                )
            \s*,?\s*
            \]/sx',
            $this->file->contents(),
            $matches,
            PREG_SET_ORDER,
        );

        foreach ($matches as $match) {
            yield new DefaultPlaceholder(
                $match[0],
                trim($match['default']),
            );
        }
    }
}
