<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Lock;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Target\Target;
use JsonException;

final readonly class JsonLock implements Lock
{
    /**
     * @param array<string, string> $hashes
     */
    public function __construct(
        private string $lockFile,
        private array $hashes = [],
    ) {}

    /**
     * @throws JsonException
     */
    public static function load(string $lockFile): self
    {
        if (!is_file($lockFile)) {
            return new self($lockFile);
        }

        $content = file_get_contents($lockFile);
        if ($content === false) {
            throw new PiquleException(
                sprintf('Cannot read lock file: %s', $lockFile),
            );
        }

        return new self(
            $lockFile,
            json_decode($content, true, flags: JSON_THROW_ON_ERROR),
        );
    }

    public function knows(Target $target): bool
    {
        return array_key_exists(
            $target->relativePath(),
            $this->hashes,
        );
    }

    public function isUnchanged(Target $target): bool
    {
        return $this->knows($target)
            && $target->exists()
            && $this->hashes[$target->relativePath()]
            === $target->file()->hash();
    }

    public function withRemembered(Target $target): Lock
    {
        return new self(
            $this->lockFile,
            $this->hashes + [
                $target->relativePath()
                => $target->sourceFile()->hash(),
            ],
        );
    }

    /**
     * @throws JsonException
     */
    public function store(): void
    {
        $dir = dirname($this->lockFile);

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0o755, true) && !is_dir($dir)) {
                throw new PiquleException(
                    sprintf('Cannot create lock directory: %s', $dir),
                );
            }
        }

        $json = json_encode(
            $this->hashes,
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR,
        );

        if (file_put_contents($this->lockFile, $json) === false) {
            throw new PiquleException(
                sprintf('Failed to write lock file: "%s"', $this->lockFile),
            );
        }
    }
}
