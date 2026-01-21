<?php

declare(strict_types=1);

namespace Haspadar\Piqule\Tests\Unit\Fake\Target\Storage;

use Haspadar\Piqule\Artifact\File;
use Haspadar\Piqule\PiquleException;
use Haspadar\Piqule\Target\Storage\TargetStorage;

final class FakeTargetStorage implements TargetStorage
{
    /**
     * @var array<string, File>
     */
    private array $files = [];

    public function exists(string $relativePath): bool
    {
        return array_key_exists($relativePath, $this->files);
    }

    public function write(string $relativePath, File $source): void
    {
        $this->files[$relativePath] = $source;
    }

    public function read(string $relativePath): File
    {
        if (!$this->exists($relativePath)) {
            throw new PiquleException(
                sprintf('File "%s" does not exist', $relativePath),
            );
        }

        return $this->files[$relativePath];
    }

    /** @return array<string, File> */
    public function all(): array
    {
        return $this->files;
    }
}
