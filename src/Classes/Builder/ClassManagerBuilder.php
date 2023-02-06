<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Builder;

use Assert\Assert;
use PhpProject\SourceCode\Classes\Manager\ClassManager;
use PhpProject\SourceCode\Classes\Manager\SimpleClassManager;
use PhpProject\SourceCode\Classes\Reflection\Standard\StandardReflector;
use PhpProject\SourceCode\Classes\Resolver\Composer\ComposerClassNameResolver;
use PhpProject\SourceCode\Files\Manager\FileManager;
use PhpProject\SourceCode\Files\Path\RelativePath;

final class ClassManagerBuilder
{
    private ?ComposerClassNameResolver $classNameResolver = null;

    private function __construct(
        private readonly FileManager $projectFileManager
    ) {
    }

    public static function for(FileManager $projectFileManager): self
    {
        return new self($projectFileManager);
    }

    public function usingComposer(?RelativePath $autoloadFile = null): self
    {
        $classLoader             = require (string) $this->projectFileManager->getAbsolutePath($autoloadFile ?? RelativePath::raw('vendor/autoload.php'));
        $this->classNameResolver = ComposerClassNameResolver::fromComposerClassLoader($classLoader);

        return $this;
    }

    public function build(): ClassManager
    {
        Assert::lazy()
            ->that($this->classNameResolver)->notNull('You have to configure a class name resolver.')
            ->verifyNow();

        return new SimpleClassManager(
            $this->projectFileManager,
            $this->classNameResolver, // @phpstan-ignore-line
            new StandardReflector()
        );
    }
}
