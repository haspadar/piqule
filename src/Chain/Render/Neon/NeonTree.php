<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Chain\Render\Neon;

use Haspadar\Piqule\Chain\Rendered;
use Haspadar\Piqule\Settings\Value\TreeValue;
use Haspadar\Piqule\Settings\Value\Value;
use Override;
use TypeError;

/**
 * Renders a TreeValue as a neon block-style mapping at the given indent.
 *
 * Example:
 *
 *     (new NeonTree(new TreeValue([
 *         'haspadar' => new TreeValue(['ignoreAbstract' => new BoolValue(true)]),
 *     ])))->rendered();
 *     // haspadar:
 *     // ignoreAbstract: true
 */
final readonly class NeonTree implements Rendered
{
    private const string INDENT = '    ';

    /**
     * Initializes with the tree to render and the depth at which rendering starts.
     *
     * @param TreeValue $value Tree payload rendered as a neon block mapping
     * @param int $depth Indent level applied to nested entries
     */
    public function __construct(private TreeValue $value, private int $depth = 0) {}

    #[Override]
    public function rendered(): string
    {
        if ($this->value->entries === []) {
            return '{}';
        }

        $prefix = str_repeat(self::INDENT, $this->depth + 1);
        $lines = [];

        foreach ($this->value->entries as $key => $child) {
            $lines[] = sprintf('%s%s:%s', $prefix, $key, $this->lineFor($child));
        }

        return sprintf("\n%s", implode("\n", $lines));
    }

    /**
     * Renders a single entry value with its leading separator.
     *
     * @throws TypeError
     */
    private function lineFor(Value $child): string
    {
        if ($child instanceof TreeValue) {
            return $child->entries === []
                ? ' {}'
                : (new self($child, $this->depth + 1))->rendered();
        }

        return sprintf(' %s', (new NeonOf($child))->renderer()->rendered());
    }
}
