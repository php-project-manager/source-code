<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files\Path;

interface Path extends \Stringable
{
    public function isSubPathOf(self $path): bool;

    public static function raw(string $path): static;

    public static function clean(string $path): static;
}
