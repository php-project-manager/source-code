<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes;

use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflectionClass;
use PhpProject\SourceCode\Classes\SourceClass;
use PhpProject\SourceCode\Classes\SourceClasses;
use PhpProject\SourceCode\Classes\SourceMethods;
use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Files\SourceFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')] final class SourceClassesTest extends TestCase
{
    #[Test]
    public function it_can_be_counted_and_crawled_and_returned_as_an_array(): void
    {
        $classOne = $this->given_a_source_class_named(MyClassOne::class);
        $classTwo = $this->given_a_source_class_named(MyClassTwo::class);

        $classes = new SourceClasses([$classOne, $classTwo]);

        self::assertCount(2, $classes);
        self::assertEquals([$classOne, $classTwo], $classes->asArray());

        $i = 0;
        foreach ($classes as $class) {
            ++$i;
        }
        self::assertEquals(2, $i);
    }

    /**
     * @param class-string $className
     */
    private function given_a_source_class_named(string $className): SourceClass
    {
        $reflectionClass = StandardReflectionClass::createFromName($className);
        $identity        = ClassIdentity::fromReflectionClass($reflectionClass);
        $file            = SourceFile::fromPath(RelativePath::raw('SourceClassesTest.php'));
        $methods         = new SourceMethods([]);

        return SourceClass::build($identity, $file, $methods);
    }
}

final class MyClassOne
{
}

final class MyClassTwo
{
}
