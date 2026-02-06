<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Placeholders;

use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Placeholders\XmlPlaceholders;
use Haspadar\Piqule\Tests\Constraint\Placeholders\HasPlaceholders;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class XmlPlaceholdersTest extends TestCase
{
    #[Test]
    public function extractsGroupedElements(): void
    {
        self::assertThat(
            new XmlPlaceholders(
                new TextFile(
                    'config.xml',
                    '<config><placeholder><item>A</item><item>B</item></placeholder></config>',
                ),
            ),
            new HasPlaceholders([
                '<placeholder><item>A</item><item>B</item></placeholder>'
                => '<item>A</item><item>B</item>',
            ]),
        );
    }

    #[Test]
    public function extractsSingleWrappedElement(): void
    {
        self::assertThat(
            new XmlPlaceholders(
                new TextFile(
                    'layout.xml',
                    '<layout><placeholder><section>Main</section></placeholder></layout>',
                ),
            ),
            new HasPlaceholders([
                '<placeholder><section>Main</section></placeholder>'
                => '<section>Main</section>',
            ]),
        );
    }

    #[Test]
    public function extractsMultipleIndependentPlaceholders(): void
    {
        self::assertThat(
            new XmlPlaceholders(
                new TextFile(
                    'document.xml',
                    '<doc>'
                    . '<placeholder><header>Top</header></placeholder>'
                    . '<placeholder><footer>Bottom</footer></placeholder>'
                    . '</doc>',
                ),
            ),
            new HasPlaceholders([
                '<placeholder><header>Top</header></placeholder>'
                => '<header>Top</header>',

                '<placeholder><footer>Bottom</footer></placeholder>'
                => '<footer>Bottom</footer>',
            ]),
        );
    }

    #[Test]
    public function ignoresXmlWithoutPlaceholderBlocks(): void
    {
        self::assertThat(
            new XmlPlaceholders(
                new TextFile(
                    'plain.xml',
                    '<root><value>42</value></root>',
                ),
            ),
            new HasPlaceholders([]),
        );
    }

    #[Test]
    public function extractsMultilinePlaceholderBlock(): void
    {
        self::assertThat(
            new XmlPlaceholders(
                new TextFile(
                    'complex.xml',
                    '
                    <root>
                        <placeholder>
                        <node>one</node>
                        <node>two</node>
                        </placeholder>
                    </root>
                    ',
                ),
            ),
            new HasPlaceholders([
                '<placeholder>
                        <node>one</node>
                        <node>two</node>
                        </placeholder>'
                => '<node>one</node>
                        <node>two</node>',
            ]),
        );
    }
}
