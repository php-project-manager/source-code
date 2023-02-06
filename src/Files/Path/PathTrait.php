<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Path;

trait PathTrait
{
    final private function __construct(
        private readonly string $path
    ) {
    }

    public function isSubPathOf(Path $path): bool
    {
        return str_ends_with((string) $path, $this->path);
    }

    public function __toString(): string
    {
        return $this->path;
    }

    /**
     * @throws \InvalidArgumentException
     */
    abstract protected static function checkPathString(string $path): void;

    protected static function cleanPathString(string $path): string
    {
        return PathUtil::cleanDirectorySeparators($path);
    }

    protected static function cleanPath(string $path): string
    {
        return static::cleanPathString($path);
    }

    public static function raw(string $path): static
    {
        static::checkPathString($path);

        return new static($path);
    }

    public static function clean(string $path): static
    {
        return static::raw(static::cleanPath($path));
    }
}
