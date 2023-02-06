<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Configured;

use PhpProject\SourceCode\Classes\Filter\ClassFilter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;

final readonly class ImplementsClassFilter implements ClassFilter
{
    /**
     * @param class-string $interfaceFQCN
     */
    public function __construct(private string $interfaceFQCN)
    {
    }

    public function matchesClass(ClassIdentity $classIdentity): bool
    {
        return $classIdentity->implementsInterface($this->interfaceFQCN);
    }
}
