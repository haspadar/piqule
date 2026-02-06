<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

/**
 * Extracts YAML-style placeholders from a file using a text-based pattern.
 *
 * This implementation treats the file contents as plain text and searches for
 * placeholders embedded in the following form:
 *
 * {{ PLACEHOLDER_NAME | default(DEFAULT_VALUE) }}
 *
 * Where:
 * - PLACEHOLDER_NAME consists of uppercase letters, digits, and underscores
 * - DEFAULT_VALUE is a literal value embedded directly in the template
 *
 * The entire placeholder expression is replaced with the resolved default value
 * via string substitution.
 *
 * Design notes:
 * - This parser does not parse YAML structurally.
 * - Placeholders are detected purely by regular expression matching.
 * - The default value is extracted as text and normalized by trimming
 *   surrounding quotes.
 * - If no placeholders are found, this parser is effectively a no-op.
 *
 * Intended usage:
 * - YAML configuration templates
 * - Text-based configuration files that embed placeholders inline
 * - Safe to combine with other Placeholders implementations (e.g. JSON),
 *   as unmatched formats simply yield no placeholders.
 *
 * Limitations (by design):
 * - Nested placeholders are not supported.
 * - DEFAULT_VALUE must not contain a closing parenthesis.
 * - Placeholder expressions must remain syntactically stable.
 */
final readonly class YamlPlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    /**
     * Returns all YAML placeholders found in the file.
     *
     * Each placeholder is represented as a DefaultPlaceholder where:
     * - expression() is the full placeholder expression, including {{ }}
     * - replacement() is the normalized default value as text
     *
     * @return iterable<DefaultPlaceholder>
     */
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
                trim($match['value'], "'\" "),
            );
        }
    }
}
