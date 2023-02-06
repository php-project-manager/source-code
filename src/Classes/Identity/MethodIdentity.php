<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Identity;

use Assert\Assert;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;

final readonly class MethodIdentity
{
    /**
     * @param non-empty-string $name
     */
    private function __construct(
        public ClassIdentity $class,
        public string $name,
        public ReflectionMethod $reflectionMethod
    ) {
        Assert::that($this->name)->notEmpty('The given method name is empty.');
    }

    public function FQN(): string
    {
        return $this->class->fqcn.'::'.$this->name;
    }

    public function isPublic(): bool
    {
        return $this->reflectionMethod->isPublic();
    }

    public function isProtected(): bool
    {
        return $this->reflectionMethod->isProtected();
    }

    public function isPrivate(): bool
    {
        return $this->reflectionMethod->isPrivate();
    }

    public static function fromReflectionMethod(ReflectionMethod $reflectionMethod): self
    {
        return new self(
            ClassIdentity::fromReflectionClass($reflectionMethod->getClass()),
            $reflectionMethod->getShortName(),
            $reflectionMethod
        );
    }
}
