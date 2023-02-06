<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Path;

final class RelativePath implements Path
{
    use PathTrait;

    protected static function checkPathString(string $path): void
    {
        if (str_starts_with($path, \DIRECTORY_SEPARATOR)) {
            throw new \InvalidArgumentException(sprintf('The given path is not relative: %s', $path));
        }
    }
}
