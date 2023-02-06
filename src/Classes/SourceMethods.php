<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes;

/**
 * @template-implements \IteratorAggregate<int, SourceMethod>
 */
final readonly class SourceMethods implements \Countable, \IteratorAggregate
{
    /**
     * @param array<SourceMethod> $methods
     */
    public function __construct(
        private array $methods
    ) {
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->methods);
    }

    public function count(): int
    {
        return \count($this->methods);
    }

    /**
     * @return array<SourceMethod>
     */
    public function asArray(): array
    {
        return $this->methods;
    }
}
