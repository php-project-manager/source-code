<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Compound;

use PhpProject\SourceCode\Classes\Filter\MethodFilter;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;

final class CompoundMethodFilter implements CompoundFilter, MethodFilter
{
    /**
     * @use CompoundFilterTrait<MethodFilter>
     */
    use CompoundFilterTrait;

    public function matchesMethod(MethodIdentity $methodIdentity): bool
    {
        return $this->matches(
            $this->filters,
            static fn (MethodFilter $filter): bool => $filter->matchesMethod($methodIdentity)
        );
    }

    public static function Or(MethodFilter ...$filters): self
    {
        return new self(self::OR, $filters);
    }

    public static function And(MethodFilter ...$filters): self
    {
        return new self(self::AND, $filters);
    }
}
