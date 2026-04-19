<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Formula\Action;

use Haspadar\Piqule\Formula\Action\JsonEscapeAction;
use Haspadar\Piqule\Formula\Args\ListArgs;
use Haspadar\Piqule\Tests\Constraint\Formula\Args\HasArgsValues;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JsonEscapeActionTest extends TestCase
{
    #[Test]
    public function leavesPlainValueUnchanged(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(['value'])),
            new HasArgsValues(['value']),
            'JsonEscapeAction must leave values without special characters intact',
        );
    }

    #[Test]
    public function escapesDoubleQuote(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(['say "hi"'])),
            new HasArgsValues(['say \\"hi\\"']),
            'JsonEscapeAction must escape double quotes with backslash',
        );
    }

    #[Test]
    public function escapesBackslash(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(['a\\b'])),
            new HasArgsValues(['a\\\\b']),
            'JsonEscapeAction must double a backslash',
        );
    }

    #[Test]
    public function escapesNewline(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(["line1\nline2"])),
            new HasArgsValues(['line1\\nline2']),
            'JsonEscapeAction must convert newline to \\n escape sequence',
        );
    }

    #[Test]
    public function escapesTab(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(["a\tb"])),
            new HasArgsValues(['a\\tb']),
            'JsonEscapeAction must convert tab to \\t escape sequence',
        );
    }

    #[Test]
    public function escapesCarriageReturn(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(["a\rb"])),
            new HasArgsValues(['a\\rb']),
            'JsonEscapeAction must convert carriage return to \\r escape sequence',
        );
    }

    #[Test]
    public function escapesControlCharacterAsUnicodeEscape(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(["bell\x07here"])),
            new HasArgsValues(['bell\\u0007here']),
            'JsonEscapeAction must encode control characters as \\uXXXX',
        );
    }

    #[Test]
    public function preservesNonAsciiLiteral(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(['привет'])),
            new HasArgsValues(['привет']),
            'JsonEscapeAction must keep non-ASCII characters as valid UTF-8',
        );
    }

    #[Test]
    public function keepsForwardSlashLiteral(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(['path/to/file'])),
            new HasArgsValues(['path/to/file']),
            'JsonEscapeAction must keep forward slashes unescaped to avoid \\/ noise',
        );
    }

    #[Test]
    public function returnsEmptyStringForEmptyInput(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs([''])),
            new HasArgsValues(['']),
            'JsonEscapeAction must render empty string as empty JSON string content',
        );
    }

    #[Test]
    public function returnsEmptyListWhenInputIsEmpty(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs([])),
            new HasArgsValues([]),
            'JsonEscapeAction must return empty list when no values are provided',
        );
    }

    #[Test]
    public function escapesEachListElementSeparately(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs(['a"b', "c\nd", 'e'])),
            new HasArgsValues(['a\\"b', 'c\\nd', 'e']),
            'JsonEscapeAction must escape each list element independently',
        );
    }

    #[Test]
    public function coercesScalarsToEscapedStrings(): void
    {
        self::assertThat(
            (new JsonEscapeAction())
                ->transformed(new ListArgs([42, true, false])),
            new HasArgsValues(['42', 'true', 'false']),
            'JsonEscapeAction must stringify scalars via StringifiedArgs before escaping',
        );
    }
}
