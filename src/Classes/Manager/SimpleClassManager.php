<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Manager;

use PhpProject\SourceCode\Classes\Filter\Filter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Reflection\ReflectionClass;
use PhpProject\SourceCode\Classes\Reflection\ReflectionMethod;
use PhpProject\SourceCode\Classes\Reflection\Reflector;
use PhpProject\SourceCode\Classes\Resolver\ClassNameResolver;
use PhpProject\SourceCode\Classes\SourceClass;
use PhpProject\SourceCode\Classes\SourceClasses;
use PhpProject\SourceCode\Classes\SourceMethod;
use PhpProject\SourceCode\Classes\SourceMethods;
use PhpProject\SourceCode\Files\Manager\FileManager;
use PhpProject\SourceCode\Files\SourceFile;
use PhpProject\SourceCode\Files\SourceFiles;

final readonly class SimpleClassManager implements ClassManager
{
    public function __construct(
        private FileManager $fileManager,
        private ClassNameResolver $classNameResolver,
        private Reflector $reflector
    ) {
    }

    public function getClass(SourceFile $file, Filter $filter = new Filter()): ?SourceClass
    {
        $absolutePath    = $this->fileManager->getAbsolutePath($file->path);
        try {
            $fqcn = $this->classNameResolver->resolveFromFilePath($absolutePath);
        } catch (\InvalidArgumentException) {
            return null;
        }

        $reflectionClass = $this->reflector->reflectClass($fqcn);
        $classIdentity   = ClassIdentity::fromReflectionClass($reflectionClass);

        if (!$filter->matchesClass($classIdentity)) {
            return null;
        }

        return SourceClass::build(
            $classIdentity,
            $file,
            $this->getMethodsForClass($reflectionClass, $filter)
        );
    }

    public function getClasses(SourceFiles $files, Filter $filter = new Filter()): SourceClasses
    {
        return new SourceClasses(
            array_filter(
                array_map(
                    fn (SourceFile $file): ?SourceClass => $this->getClass($file, $filter),
                    $files->asArray()
                )
            )
        );
    }

    private function getMethodsForClass(ReflectionClass $reflectionClass, Filter $filter): SourceMethods
    {
        return new SourceMethods(
            array_filter(
                array_map(
                    fn (ReflectionMethod $method): ?SourceMethod => $this->getMethod($method, $filter),
                    $reflectionClass->getMethods()
                )
            )
        );
    }

    private function getMethod(ReflectionMethod $method, Filter $filter): ?SourceMethod
    {
        $methodIdentity = MethodIdentity::fromReflectionMethod($method);

        if (!$filter->matchesMethod($methodIdentity)) {
            return null;
        }

        return SourceMethod::fromIdentity($methodIdentity);
    }
}
