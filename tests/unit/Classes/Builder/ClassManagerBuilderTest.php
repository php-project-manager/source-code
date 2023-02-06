<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Builder;

use PhpProject\SourceCode\Classes\Builder\ClassManagerBuilder;
use PhpProject\SourceCode\Classes\Manager\SimpleClassManager;
use PhpProject\SourceCode\Files\Manager\FileManager;
use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ClassManagerBuilderTest extends TestCase
{
    private ClassManagerBuilder $builder;

    protected function setUp(): void
    {
        $fileManager   = FileManager::build(AbsolutePath::clean(__DIR__.'/../../../..'));
        $this->builder = ClassManagerBuilder::for($fileManager);
    }

    #[Test]
    public function it_builds_a_class_manager_using_composer(): void
    {
        $classManager = $this->builder
            ->usingComposer()
            ->build();

        self::assertInstanceOf(SimpleClassManager::class, $classManager);
    }

    #[Test]
    public function it_cannot_build_a_class_manager_if_not_given_a_class_loading_strategy(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->builder->build();
    }
}
