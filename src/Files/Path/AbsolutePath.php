<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Path;

final class AbsolutePath implements Path
{
    use PathTrait;

    protected static function checkPathString(string $path): void
    {
        if (!str_starts_with($path, \DIRECTORY_SEPARATOR)) {
            throw new \InvalidArgumentException(sprintf('The given path is not absolute: %s', $path));
        }
    }

    public function append(RelativePath $relativePath): self
    {
        return new self($this->path.\DIRECTORY_SEPARATOR.$relativePath);
    }

    public function getRelativePath(self $path): RelativePath
    {
        return RelativePath::raw((string) preg_replace('/('.preg_quote($this->path, '/').'(\/)*)/', '', $path->path, 1));
    }
}
