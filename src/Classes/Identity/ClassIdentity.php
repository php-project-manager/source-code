<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Identity;

use Assert\Assert;
use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;

final readonly class ClassIdentity
{
    private function __construct(
        public string $fqcn,
        public string $shortName,
        public ReflectionClass $reflectionClass
    ) {
        Assert::that($fqcn)->notEmpty('The given FQCN is empty.');
    }

    /**
     * @param class-string $interfaceFQCN
     */
    public function implementsInterface(string $interfaceFQCN): bool
    {
        return $this->reflectionClass->implementsInterface($interfaceFQCN);
    }

    public static function fromReflectionClass(ReflectionClass $reflectionClass): self
    {
        return new self(
            $reflectionClass->getName(),
            $reflectionClass->getShortName(),
            $reflectionClass
        );
    }
}
