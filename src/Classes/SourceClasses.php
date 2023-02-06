<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes;

/**
 * @template-implements \IteratorAggregate<int, SourceClass>
 */
final readonly class SourceClasses implements \Countable, \IteratorAggregate
{
    /**
     * @param array<SourceClass> $classes
     */
    public function __construct(
        private array $classes
    ) {
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->classes);
    }

    public function count(): int
    {
        return \count($this->classes);
    }

    /**
     * @return array<SourceClass>
     */
    public function asArray(): array
    {
        return $this->classes;
    }
}
