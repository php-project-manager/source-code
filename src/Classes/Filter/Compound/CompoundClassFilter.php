<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Compound;

use PhpProject\SourceCode\Classes\Filter\ClassFilter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;

final class CompoundClassFilter implements CompoundFilter, ClassFilter
{
    /**
     * @use CompoundFilterTrait<ClassFilter>
     */
    use CompoundFilterTrait;

    public function matchesClass(ClassIdentity $classIdentity): bool
    {
        return $this->matches(
            $this->filters,
            static fn (ClassFilter $filter): bool => $filter->matchesClass($classIdentity)
        );
    }

    public static function And(ClassFilter ...$filters): self
    {
        return new self(CompoundFilter::AND, $filters);
    }

    public static function Or(ClassFilter ...$filters): self
    {
        return new self(CompoundFilter::OR, $filters);
    }
}
