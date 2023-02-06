<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter;

use PhpProject\SourceCode\Classes\Filter\Configured\NullClassFilter;
use PhpProject\SourceCode\Classes\Filter\Configured\NullMethodFilter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;

final readonly class Filter implements ClassFilter, MethodFilter
{
    public function __construct(
        private ClassFilter $classFilter = new NullClassFilter(),
        private MethodFilter $methodFilter = new NullMethodFilter()
    ) {
    }

    public function matchesClass(ClassIdentity $classIdentity): bool
    {
        return $this->classFilter->matchesClass($classIdentity);
    }

    public function matchesMethod(MethodIdentity $methodIdentity): bool
    {
        return $this->methodFilter->matchesMethod($methodIdentity);
    }

    public static function forClass(ClassFilter $classFilter): self
    {
        return new self(classFilter: $classFilter);
    }

    public static function forMethod(MethodFilter $methodFilter): self
    {
        return new self(methodFilter: $methodFilter);
    }

    public static function null(): self
    {
        return new self();
    }
}
