<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Resolver;

use PhpProject\SourceCode\Files\Path\AbsolutePath;

interface ClassNameResolver
{
    /**
     * @return class-string
     */
    public function resolveFromFilePath(AbsolutePath $path): string;
}
