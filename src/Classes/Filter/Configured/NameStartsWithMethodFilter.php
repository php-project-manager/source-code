<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\MethodFilter;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;

final readonly class NameStartsWithMethodFilter implements MethodFilter
{
    public function __construct(
        private string $start
    ) {
    }

    public function matchesMethod(MethodIdentity $methodIdentity): bool
    {
        return str_starts_with($methodIdentity->name, $this->start);
    }
}
