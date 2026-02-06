<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

/**
 * Extracts PHP-style placeholders from a file.
 *
 * The parser scans the file contents and detects placeholder definitions
 * expressed as PHP array literals in the following form:
 *
 * [
 *     '$placeholder' => 'PLACEHOLDER_NAME',
 *     'default' => DEFAULT_VALUE,
 * ]
 *
 * Each detected placeholder is replaced with its default value via
 * string substitution.
 *
 * Reasoning and design constraints:
 * - The file contents are treated as plain text.
 * - No PHP parsing, tokenization, AST, or evaluation is performed.
 * - Placeholder detection relies solely on regular expression matching.
 * - DEFAULT_VALUE is extracted verbatim and normalized by trimming
 *   surrounding whitespace.
 * - If no placeholders are found, this implementation produces no output.
 *
 * Intended scope:
 * - PHP configuration templates
 * - Files that are syntactically valid PHP but not necessarily executable
 * - Safe to combine with other Placeholders implementations, as unmatched
 *   formats yield no placeholders
 *
 * Explicit limitations:
 * - DEFAULT_VALUE must be a literal whose textual boundaries can be
 *   determined without parsing PHP.
 * - Nested or complex PHP expressions are intentionally not supported.
 * - Placeholder definitions must remain syntactically stable.
 */
final readonly class PhpPlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    /**
     * Returns all PHP placeholders found in the file.
     *
     * Each placeholder is returned as a DefaultPlaceholder where:
     * - expression() is the full PHP array literal representing the placeholder
     * - replacement() is the extracted default value as normalized text
     *
     * @return iterable<DefaultPlaceholder>
     */
    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/\[\s*["\']\$placeholder["\']\s*=>\s*["\'][A-Z0-9_]+["\']\s*,\s*["\']default["\']\s*=>\s*(\[[^\]]*\]|[^,\]]+)\s*,?\s*\]/s',
            $this->file->contents(),
            $matches,
            PREG_SET_ORDER,
        );

        foreach ($matches as $match) {
            yield new DefaultPlaceholder(
                $match[0],
                trim($match[1]),
            );
        }
    }
}
