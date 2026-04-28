<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Chain\Plain;

use Haspadar\Piqule\Chain\Listed;
use Haspadar\Piqule\Chain\Op;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Settings\Value\BoolValue;
use Haspadar\Piqule\Settings\Value\FloatValue;
use Haspadar\Piqule\Settings\Value\IntValue;
use Haspadar\Piqule\Settings\Value\ListValue;
use Haspadar\Piqule\Settings\Value\StringValue;
use Haspadar\Piqule\Settings\Value\Value;
use Override;

/**
 * Exposes a ListValue as a Listed pipeline source of plain element ops.
 *
 * Each element is wrapped into the matching scalar Plain op. Nested lists or
 * trees are not allowed here, since they have no format-neutral text shape.
 * ListText itself refuses direct rendering and requires a Reduced step
 * (typically Joined) further down the pipeline to fold parts into a string.
 *
 * Example:
 *
 *     (new ListText(new ListValue([
 *         new StringValue('src'),
 *         new StringValue('tests'),
 *     ])))->parts(); // [StringText('src'), StringText('tests')]
 */
final readonly class ListText implements Listed
{
    /**
     * Initializes with the list value whose children become pipeline parts.
     *
     * @param ListValue $value Source list whose scalar children are wrapped into Plain ops
     */
    public function __construct(private ListValue $value) {}

    #[Override]
    public function parts(): array
    {
        return array_map(
            fn(Value $child): Op => $this->asText($child),
            $this->value->children,
        );
    }

    #[Override]
    public function rendered(): string
    {
        throw new PiquleException(
            'ListText cannot render directly — collapse it via a Reduced op such as Joined',
        );
    }

    /**
     * Wraps a single Value child into the matching scalar Plain op.
     *
     * @throws PiquleException
     */
    private function asText(Value $child): Op
    {
        return match (true) {
            $child instanceof BoolValue => new BoolText($child),
            $child instanceof IntValue => new IntText($child),
            $child instanceof FloatValue => new FloatText($child),
            $child instanceof StringValue => new StringText($child),
            default => throw new PiquleException(
                sprintf(
                    'ListText only accepts scalar children, got "%s"',
                    $child::class,
                ),
            ),
        };
    }
}
