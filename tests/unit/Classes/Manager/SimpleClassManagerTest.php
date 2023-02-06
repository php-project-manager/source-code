<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Manager;

use PhpProject\SourceCode\Classes\Filter\ClassFilter;
use PhpProject\SourceCode\Classes\Filter\Filter;
use PhpProject\SourceCode\Classes\Filter\MethodFilter;
use PhpProject\SourceCode\Classes\Identity\ClassIdentity;
use PhpProject\SourceCode\Classes\Identity\MethodIdentity;
use PhpProject\SourceCode\Classes\Manager\ClassManager;
use PhpProject\SourceCode\Classes\Manager\SimpleClassManager;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflector;
use PhpProject\SourceCode\Classes\Resolver\Composer\ComposerClassNameResolver;
use PhpProject\SourceCode\Files\Manager\FileManager;
use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PhpProject\SourceCode\Files\Path\RelativePath;
use PhpProject\SourceCode\Files\SourceFile;
use PhpProject\SourceCode\Files\SourceFiles;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('manager')] final class SimpleClassManagerTest extends TestCase
{
    private ClassManager $classManager;

    protected function setUp(): void
    {
        $projectPath        = AbsolutePath::raw(__DIR__.'/../../../..');
        $classLoader        = require $projectPath.'/vendor/autoload.php';
        $this->classManager = new SimpleClassManager(
            FileManager::build($projectPath),
            ComposerClassNameResolver::fromComposerClassLoader($classLoader),
            new StandardReflector()
        );
    }

    #[Test]
    public function it_cannot_retrieve_the_class_of_an_unknown_file(): void
    {
        $file = SourceFile::fromPath(RelativePath::raw('unknown.php'));

        self::assertNull($this->classManager->getClass($file));
    }

    #[Test]
    public function it_retrieves_the_class_from_file_when_not_filtering(): void
    {
        $file  = SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php'));
        $class = $this->classManager->getClass($file);

        self::assertNotNull($class);
        self::assertEquals(SourceFile::class, $class->FQCN());
    }

    #[Test]
    public function it_retrieves_the_class_from_file_when_matching_class_filter(): void
    {
        $file   = SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php'));
        $filter = Filter::forClass(new TestClassFilter(SourceFile::class));
        $class  = $this->classManager->getClass($file, $filter);

        self::assertNotNull($class);
        self::assertEquals(SourceFile::class, $class->FQCN());
    }

    #[Test]
    public function it_cannot_retrieve_the_class_from_file_when_not_matching_class_filter(): void
    {
        $file   = SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php'));
        $filter = Filter::forClass(new TestClassFilter(self::class));
        $class  = $this->classManager->getClass($file, $filter);

        self::assertNull($class);
    }

    #[Test]
    public function it_retrieves_the_classes_from_files_when_not_filtering(): void
    {
        $files   = SourceFiles::fromSourceFile(
            SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php')),
            SourceFile::fromPath(RelativePath::raw('src/Files/SourceFiles.php'))
        );

        $classes = $this->classManager->getClasses($files);
        self::assertCount(2, $classes);
    }

    #[Test]
    public function it_retrieves_the_classes_only_from_existing_files(): void
    {
        $files   = SourceFiles::fromSourceFile(
            SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php')),
            SourceFile::fromPath(RelativePath::raw('unknown.php'))
        );

        $classes = $this->classManager->getClasses($files);
        self::assertCount(1, $classes);
    }

    #[Test]
    public function it_retrieves_only_classes_from_files_when_matching_the_class_filter(): void
    {
        $files   = SourceFiles::fromSourceFile(
            SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php')),
            SourceFile::fromPath(RelativePath::raw('src/Files/SourceFiles.php'))
        );

        $filter  = Filter::forClass(new TestClassFilter(SourceFile::class));
        $classes = $this->classManager->getClasses($files, $filter);

        self::assertCount(1, $classes);
    }

    #[Test]
    public function it_retrieves_all_methods_from_class_when_not_filtering(): void
    {
        $file  = SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php'));
        $class = $this->classManager->getClass($file);

        self::assertNotNull($class);
        self::assertCount(3, $class->methods);
    }

    #[Test]
    public function it_retrieves_only_methods_from_class_when_matching_the_method_filter(): void
    {
        $file   = SourceFile::fromPath(RelativePath::raw('src/Files/SourceFile.php'));
        $filter = Filter::forMethod(new TestMethodFilter());
        $class  = $this->classManager->getClass($file, $filter);

        self::assertNotNull($class);
        self::assertCount(1, $class->methods);
    }
}

final readonly class TestClassFilter implements ClassFilter
{
    /**
     * @param class-string $fqcn
     */
    public function __construct(private string $fqcn)
    {
    }

    public function matchesClass(ClassIdentity $classIdentity): bool
    {
        return $classIdentity->fqcn === $this->fqcn;
    }
}

final readonly class TestMethodFilter implements MethodFilter
{
    public function matchesMethod(MethodIdentity $methodIdentity): bool
    {
        return $methodIdentity->isPublic();
    }
}
