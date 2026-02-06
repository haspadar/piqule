<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Placeholders;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\Placeholder\DefaultPlaceholder;
use Override;

/**
 * Extracts JSON placeholders from a file using a fixed, text-based pattern.
 *
 * This implementation does NOT parse JSON structurally.
 * Instead, it treats the file contents as plain text and searches for
 * well-defined JSON placeholder objects of the following form:
 *
 * {
 *   "$placeholder": "PLACEHOLDER_NAME",
 *   "default": <json-value>
 * }
 *
 * The entire placeholder object is replaced with the value of `default`
 * via string substitution.
 *
 * Design notes:
 * - Placeholders are detected purely by regular expression matching.
 * - The JSON placeholder object must contain exactly two keys:
 *   "$placeholder" and "default", in this order.
 * - The `default` value is captured verbatim as JSON text and reused
 *   as-is during replacement.
 * - If no placeholders are found, this parser is effectively a no-op.
 *
 * Intended usage:
 * - JSON configuration templates where controlled, predictable substitution
 *   is required.
 * - Safe to combine with other Placeholders implementations (e.g. YAML),
 *   as unmatched formats simply yield no placeholders.
 *
 * Limitations (by design):
 * - Arbitrary JSON parsing is not supported.
 * - Placeholder objects must not contain additional keys.
 * - Formatting inside the placeholder object must remain stable.
 */
final readonly class JsonPlaceholders implements Placeholders
{
    public function __construct(
        private File $file,
    ) {}

    /**
     * Returns all JSON placeholders found in the file.
     *
     * Each placeholder is represented as a DefaultPlaceholder where:
     * - expression() is the full JSON placeholder object as text
     * - replacement() is the JSON text of the `default` value
     *
     * @return iterable<DefaultPlaceholder>
     */
    #[Override]
    public function all(): iterable
    {
        preg_match_all(
            '/\{
                \s*"\$placeholder"\s*:\s*"(?<name>[A-Z0-9_]+)"\s*,\s*
                "default"\s*:\s*(?<default>.+?)
            \s*\}/sx',
            $this->file->contents(),
            $matches,
            PREG_SET_ORDER,
        );

        foreach ($matches as $match) {
            yield new DefaultPlaceholder(
                $match[0],
                $match['default'],
            );
        }
    }
}
