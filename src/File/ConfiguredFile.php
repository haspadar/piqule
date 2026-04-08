<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\FirstAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Action\FormatEachAction;
use Haspadar\Piqule\Formula\Action\IfEmptyAction;
use Haspadar\Piqule\Formula\Action\IfNotEmptyAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Actions\ParsedActions;
use Haspadar\Piqule\Formula\ExecutedFormula;
use Haspadar\Piqule\Formula\NormalizedFormula;
use Haspadar\Piqule\PiquleException;
use InvalidArgumentException;
use Override;

/**
 * Replaces DSL placeholders in a file's contents using configuration values.
 */
final readonly class ConfiguredFile implements File
{
    /** Wraps a file and a configuration source for placeholder resolution. */
    public function __construct(private File $origin, private Config $config) {}

    #[Override]
    public function name(): string
    {
        return $this->origin->name();
    }

    #[Override]
    public function contents(): string
    {
        return (string) preg_replace_callback(
            '/<<\s*(.*?)\s*>>/s',
            fn(array $match): string => $this->replaced($match[1]),
            $this->origin->contents(),
        );
    }

    #[Override]
    public function mode(): int
    {
        return $this->origin->mode();
    }

    /**
     * Returns the result of evaluating a single DSL expression against the config.
     *
     * @throws PiquleException
     */
    private function replaced(string $expression): string
    {
        try {
            return (new ExecutedFormula(
                new ParsedActions(
                    (new NormalizedFormula($expression))->result(),
                    $this->actions(),
                ),
            ))->result();
        } catch (InvalidArgumentException | PiquleException $e) {
            throw new PiquleException(
                sprintf(
                    'File "%s", formula "%s": %s',
                    $this->name(),
                    trim($expression),
                    $e->getMessage(),
                ),
                0,
                $e,
            );
        }
    }

    /**
     * Returns the map of supported action names to their factory callables.
     *
     * @return array<string, callable(string): Action>
     */
    private function actions(): array
    {
        return [
            'config' => fn(string $raw): Action => new ConfigAction($this->config, $raw),
            'first' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new FirstAction(),
                default => throw new PiquleException('Action "first" does not accept arguments'),
            },
            'format' => static fn(string $raw): Action => new FormatAction($raw),
            'format_each' => static fn(string $raw): Action => new FormatEachAction($raw),
            'if_empty' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new IfEmptyAction(),
                default => throw new PiquleException('Action "if_empty" does not accept arguments'),
            },
            'if_not_empty' => static fn(string $raw): Action => match (trim($raw)) {
                '' => new IfNotEmptyAction(),
                default => throw new PiquleException('Action "if_not_empty" does not accept arguments'),
            },
            'join' => static fn(string $raw): Action => new JoinAction($raw),
        ];
    }
}
