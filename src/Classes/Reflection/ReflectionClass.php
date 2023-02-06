<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Reflection;

interface ReflectionClass
{
    public function getName(): string;

    public function getShortName(): string;

    /**
     * @param non-empty-string $methodName
     */
    public function getMethod(string $methodName): ReflectionMethod;

    /**
     * @return array<ReflectionMethod>
     */
    public function getMethods(): array;

    /**
     * @param class-string $interfaceFQCN
     */
    public function implementsInterface(string $interfaceFQCN): bool;
}
