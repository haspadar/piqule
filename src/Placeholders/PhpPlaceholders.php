<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

/**
 * Extracts PHP-style placeholders from a file using a text-based pattern.
 *
 * This implementation treats the file contents as plain text and searches for
 * placeholder definitions expressed as PHP array literals in the following form:
 *
 * [
 *     '$placeholder' => 'PLACEHOLDER_NAME',
 *     'default' => DEFAULT_VALUE,
 * ]
 *
 * Where:
 * - PLACEHOLDER_NAME consists of uppercase letters, digits, and underscores
 * - DEFAULT_VALUE is a PHP literal embedded directly in the template
 *
 * The entire placeholder array expression is replaced with the resolved default
 * value via string substitution.
 *
 * Design notes:
 * - This parser does not parse PHP code structurally.
 * - No AST, tokenization, or PHP evaluation is performed.
 * - Placeholders are detected purely by regular expression matching.
 * - The default value is extracted as text and normalized by trimming
 *   surrounding whitespace.
 * - If no placeholders are found, this parser is effectively a no-op.
 *
 * Intended usage:
 * - PHP configuration templates
 * - Syntactically valid, but not necessarily executable PHP files
 * - Safe to combine with other Placeholders implementations (e.g. YAML, JSON),
 *   as unmatched formats simply yield no placeholders.
 *
 * Limitations (by design):
 * - DEFAULT_VALUE must be a literal whose textual boundaries can be determined
 *   without parsing PHP.
 * - Nested or complex PHP expressions are not supported.
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
     * Each placeholder is represented as a DefaultPlaceholder where:
     * - expression() is the full PHP array literal representing the placeholder
     * - replacement() is the normalized default value as text
     *
     * @return iterable<DefaultPlaceholder>
     */
    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/\[
                \s*(?:\'|")\$placeholder(?:\'|")\s*=>\s*(?:\'|")(?<name>[A-Z0-9_]+)(?:\'|")\s*,\s*
                (?:\'|")default(?:\'|")\s*=>\s*(?<default>
                    \[[^\]]*\]
                    |
                    [^,\]]+
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
