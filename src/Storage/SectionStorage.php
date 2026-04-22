<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Storage;

use Haspadar\Piqule\File\File;
use Haspadar\Piqule\File\TextFile;
use Haspadar\Piqule\Storage\Reaction\StorageReaction;
use Override;

/**
 * Writes file contents as a delimited section inside an existing file.
 *
 * Behavior:
 * - file absent → writes the incoming contents as-is;
 * - file present, section pattern matches → replaces the matched section with the incoming contents;
 * - file present, section pattern does not match → leaves the file untouched and reports it as skipped.
 */
final readonly class SectionStorage implements Storage
{
    /**
     * Initializes with the underlying storage, a reaction, and the section pattern.
     *
     * The pattern is PCRE (with delimiters) and must match the entire managed section,
     * including both the begin and end markers.
     *
     * @param non-empty-string $pattern
     */
    public function __construct(
        private Storage $origin,
        private StorageReaction $reaction,
        private string $pattern,
    ) {}

    #[Override]
    public function write(File $file): self
    {
        $path = $file->name();

        if (!$this->origin->exists($path)) {
            $newOrigin = $this->origin->write($file);
            $this->reaction->created($path);

            return new self($newOrigin, $this->reaction, $this->pattern);
        }

        $current = $this->origin->read($path);
        $replaced = preg_match($this->pattern, $current) === 1
            ? (string) preg_replace(
                $this->pattern,
                $this->escapedReplacement($file->contents()),
                $current,
                1,
            )
            : $current;

        if ($replaced === $current) {
            $this->reaction->skipped($path);

            return $this;
        }

        $newOrigin = $this->origin->write(
            new TextFile($path, $replaced, $this->origin->mode($path)),
        );
        $this->reaction->updated($path);

        return new self($newOrigin, $this->reaction, $this->pattern);
    }

    #[Override]
    public function read(string $location): string
    {
        return $this->origin->read($location);
    }

    #[Override]
    public function exists(string $location): bool
    {
        return $this->origin->exists($location);
    }

    #[Override]
    public function entries(string $location): iterable
    {
        return $this->origin->entries($location);
    }

    #[Override]
    public function mode(string $location): int
    {
        return $this->origin->mode($location);
    }

    /**
     * Escapes backreference tokens so preg_replace treats the replacement literally.
     */
    private function escapedReplacement(string $contents): string
    {
        return strtr($contents, ['\\' => '\\\\', '$' => '\\$']);
    }
}
