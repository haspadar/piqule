<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

/**
 * Extracts XML placeholders defined as named placeholder container tags.
 *
 * The parser scans the XML contents and detects placeholder blocks
 * written in the following form:
 *
 * <placeholder name="PLACEHOLDER_NAME">
 * <file>../../src</file>
 * <file>../../app</file>
 * </placeholder>
 *
 * Each detected placeholder block is replaced with its inner XML fragment
 * via string substitution.
 *
 * Design constraints:
 * - XML documents remain syntactically valid before and after substitution.
 * - The file contents are treated as plain text.
 * - No XML parsing, tokenization, DOM, schema, or AST is performed.
 * - Placeholder detection relies solely on regular expression matching.
 *
 * Intended scope:
 * - XML configuration templates (e.g. phpcs.xml)
 * - Templates that require grouping and future override of XML fragments
 * - Safe to combine with other Placeholders implementations, as unmatched
 *   formats yield no placeholders.
 *
 * Explicit limitations:
 * - Placeholder blocks must declare a name attribute.
 * - Nested <placeholder> blocks are intentionally not supported.
 * - Placeholder contents must not rely on indentation or formatting.
 * - Placeholder block structure must remain syntactically stable.
 */
final readonly class XmlPlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    /**
     * Returns all named XML placeholder blocks found in the file.
     *
     * Each placeholder is returned as a DefaultPlaceholder where:
     * - expression() is the full <placeholder name="...">...</placeholder> block
     * - replacement() is the extracted inner XML fragment
     *
     * @return iterable<DefaultPlaceholder>
     */
    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/<placeholder\s+name="([A-Z0-9_]+)">\s*([\s\S]*?)\s*<\/placeholder>/',
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
