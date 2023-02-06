<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Resolver\Composer;

use Composer\Autoload\ClassLoader;
use PhpProject\SourceCode\Classes\Resolver\ClassNameResolver;
use PhpProject\SourceCode\Files\Path\AbsolutePath;

final readonly class ComposerClassNameResolver implements ClassNameResolver
{
    public function __construct(
        private ComposerReverseClassLoader $loader
    ) {
    }

    public function resolveFromFilePath(AbsolutePath $path): string
    {
        return $this->loader->get($path) ?? throw new \InvalidArgumentException(sprintf('Unable to resolve class name for file %s', $path));
    }

    public static function fromComposerClassLoader(ClassLoader $classLoader): self
    {
        return new self(
            ComposerReverseClassLoader::from($classLoader)
        );
    }
}
