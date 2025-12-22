<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Project\Registry;

use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Target\Target;
use JsonException;

final readonly class JsonRegistry implements Registry
{
    /**
     * @param array<string,string> $remembered
     */
    public function __construct(
        private string $file,
        private array  $remembered = [],
    ) {}

    /**
     * @throws JsonException
     *
     * @return array<string,string>
     */
    private function stored(): array
    {
        if (!is_file($this->file)) {
            return [];
        }

        $content = file_get_contents($this->file);
        if ($content === false) {
            throw new PiquleException(
                sprintf('Cannot read registry file: %s', $this->file),
            );
        }

        return json_decode(
            $content,
            true,
            flags: JSON_THROW_ON_ERROR,
        );
    }

    /**
     * @throws JsonException
     *
     * @return array<string,string>
     */
    private function hashes(): array
    {
        return $this->remembered + $this->stored();
    }

    /**
     * @throws JsonException
     */
    public function knows(Target $target): bool
    {
        return array_key_exists(
            $target->relativePath(),
            $this->hashes(),
        );
    }

    /**
     * @throws JsonException
     */
    public function isUnchanged(Target $target): bool
    {
        return $this->knows($target)
            && $target->exists()
            && $this->hashes()[$target->relativePath()]
            === $target->file()->hash();
    }

    public function withRemembered(Target $target): Registry
    {
        return new self(
            $this->file,
            $this->remembered + [
                $target->relativePath() => $target->file()->hash(),
            ],
        );
    }

    /**
     * @throws JsonException
     */
    public function store(): void
    {
        $dir = dirname($this->file);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0o755, true) && !is_dir($dir)) {
                throw new PiquleException(
                    sprintf('Cannot create registry directory: %s', $dir),
                );
            }
        }

        $json = json_encode(
            $this->hashes(),
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES,
        );

        if (file_put_contents($this->file, $json) === false) {
            throw new PiquleException(
                sprintf('Failed to write registry file: "%s"', $this->file),
            );
        }
    }
}
