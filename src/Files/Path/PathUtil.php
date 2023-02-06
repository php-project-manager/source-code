<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Path;

final class PathUtil
{
    public static function cleanDirectorySeparators(string $path): string
    {
        $path = (string) preg_replace(['/(\/(\.\/)+)/', '/\/+/'], \DIRECTORY_SEPARATOR, $path);

        if (str_starts_with($path, '.'.\DIRECTORY_SEPARATOR)) {
            $path = substr($path, 2);
        }

        if (str_ends_with($path, \DIRECTORY_SEPARATOR.'.')) {
            $path = substr($path, 0, -1);
        }

        if (\strlen($path) !== 1 && str_ends_with($path, \DIRECTORY_SEPARATOR)) {
            $path = substr($path, 0, -1);
        }

        if ($path === '.') {
            $path = '';
        }

        // TODO handle .. in path

        return $path;
    }
}
