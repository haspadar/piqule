<?php

declare(strict_types=1);

namespace Haspadar\Piqule\File;

use Haspadar\Piqule\Config\Config;
use Haspadar\Piqule\Formula\Action\Action;
use Haspadar\Piqule\Formula\Action\ConfigAction;
use Haspadar\Piqule\Formula\Action\FormatAction;
use Haspadar\Piqule\Formula\Action\FormatEachAction;
use Haspadar\Piqule\Formula\Action\JoinAction;
use Haspadar\Piqule\Formula\Actions\ParsedActions;
use Haspadar\Piqule\Formula\ExecutedFormula;
use Haspadar\Piqule\Formula\NormalizedFormula;
use Haspadar\Piqule\PiquleException;
use Override;
use Throwable;

/**
 * Replaces DSL placeholders in a file's contents using configuration values
 */
final readonly class ConfiguredFile implements File
{
    public function __construct(
        private File $origin,
        private Config $config,
    ) {}

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

    private function replaced(string $expression): string
    {
        try {
            return (new ExecutedFormula(
                new ParsedActions(
                    (new NormalizedFormula($expression))->result(),
                    $this->actions(),
                ),
            ))->result();
        } catch (Throwable $e) {
            throw new PiquleException(
                sprintf(
                    'File "%s", formula "%s": %s',
                    $this->name(),
                    trim($expression),
                    $e->getMessage(),
                ),
                previous: $e,
            );
        }
    }

    /**
     * @return array<string, callable(string): Action>
     */
    private function actions(): array
    {
        return [
            'config' => fn(string $raw): Action => new ConfigAction($this->config, $raw),
            'format' => fn(string $raw): Action => new FormatAction($raw),
            'format_each' => fn(string $raw): Action => new FormatEachAction($raw),
            'join' => fn(string $raw): Action => new JoinAction($raw),
        ];
    }
}
