<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Filter\Compound;

use Assert\Assert;
use PhpProject\SourceCode\Classes\Filter\ClassFilter;
use PhpProject\SourceCode\Classes\Filter\MethodFilter;

/**
 * @template F of ClassFilter|MethodFilter
 */
trait CompoundFilterTrait
{
    /**
     * @param array<F> $filters
     */
    private function __construct(
        private readonly string $operator,
        private readonly array $filters
    ) {
        Assert::that($operator)->inArray([CompoundFilter::AND, CompoundFilter::OR]);
    }

    /**
     * @param array<F>         $filters
     * @param callable(F):bool $filteringCallback
     */
    private function matches(array $filters, callable $filteringCallback): bool
    {
        return array_reduce(
            $filters,
            fn (bool $satisfies, mixed $filter): bool => $this->operation($satisfies, $filteringCallback($filter)),
            $this->getBaseValue($filters)
        );
    }

    private function operation(bool $one, bool $two): bool
    {
        return match ($this->operator) {
            CompoundFilter::OR  => $one || $two,
            CompoundFilter::AND => $one && $two,
            default             => throw new \InvalidArgumentException('Bad operator given.'),
        };
    }

    /**
     * @param array<mixed> $filters
     */
    private function getBaseValue(array $filters): bool
    {
        return match ($this->operator) {
            self::OR  => $filters === [],
            self::AND => true,
            default   => throw new \InvalidArgumentException('Bad operator given.'),
        };
    }
}
