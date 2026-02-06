<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

/**
 * Extracts PHP placeholders defined as comment-delimited blocks.
 *
 * The parser scans the file contents and detects placeholder blocks
 * written in the following form:
 *
 * /* @placeholder PLACEHOLDER_NAME *\/
 * <DEFAULT_VALUE>
 *
 * /* @endplaceholder *\/
 *
 * The entire placeholder block is replaced with its inner contents
 * via string substitution.
 *
 * Design constraints:
 * - The file contents are treated as plain text.
 * - No PHP parsing, tokenization, AST, or evaluation is performed.
 * - Placeholder detection relies solely on regular expression matching.
 *
 * Intended scope:
 * - PHP configuration templates
 * - Files that are syntactically valid PHP but not necessarily executable
 * - Safe to combine with other Placeholders implementations, as unmatched
 *   formats yield no placeholders.
 *
 * Explicit limitations:
 * - Placeholder blocks must be explicitly closed.
 * - Nested placeholder blocks are intentionally not supported.
 * - Placeholder block structure must remain syntactically stable.
 */
final readonly class PhpPlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    /**
     * Returns all PHP placeholder blocks found in the file.
     *
     * Each placeholder is returned as a DefaultPlaceholder where:
     * - expression() is the full placeholder block, including comments
     * - replacement() is the extracted inner contents as normalized text
     *
     * @return iterable<DefaultPlaceholder>
     */
    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/\/\*\s*@placeholder\s+([A-Z0-9_]+)\s*\*\/([\s\S]*?)\/\*\s*@endplaceholder\s*\*\//',
            $this->file->contents(),
            $matches,
            PREG_SET_ORDER,
        );

        foreach ($matches as $match) {
            yield new DefaultPlaceholder(
                $match[0],
                trim($match[2]),
            );
        }
    }
}
