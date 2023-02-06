<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files;

use PhpProject\SourceCode\Files\Path\RelativePath;

final readonly class SourceFile
{
    private function __construct(
        public RelativePath $path,
        public string $shortName
    ) {
    }

    public static function fromPath(RelativePath $path): self
    {
        return new self($path, self::extractShortName((string) $path));
    }

    private static function extractShortName(string $path): string
    {
        $substrIndex = strrpos($path, \DIRECTORY_SEPARATOR);
        if ($substrIndex === false) {
            return $path;
        }

        return substr($path, $substrIndex + 1);
    }
}
