<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Resolver\Composer;

use PhpProject\SourceCode\Classes\Resolver\Composer\ComposerClassNameResolver;
use PhpProject\SourceCode\Classes\Resolver\Composer\ComposerReverseClassLoader;
use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('class-resolver')]
#[Group('composer')] final class ComposerClassNameResolverTest extends TestCase
{
    #[Test]
    public function it_resolves_the_class_name(): void
    {
        $loader   =  new ComposerReverseClassLoader([], [__FILE__ => self::class]);
        $resolver = new ComposerClassNameResolver($loader);

        self::assertSame(self::class, $resolver->resolveFromFilePath(AbsolutePath::raw(__FILE__)));
    }

    #[Test]
    public function it_fails_resolving_the_class_name_if_not_in_loader(): void
    {
        $loader   =  new ComposerReverseClassLoader([], []);
        $resolver = new ComposerClassNameResolver($loader);

        $this->expectException(\InvalidArgumentException::class);
        $resolver->resolveFromFilePath(AbsolutePath::raw(__FILE__));
    }

    #[Test]
    public function it_build_from_composer__class_loader(): void
    {
        $loader   = require __DIR__.'/../../../../../vendor/autoload.php';
        $resolver = ComposerClassNameResolver::fromComposerClassLoader($loader);

        self::assertSame(self::class, $resolver->resolveFromFilePath(AbsolutePath::raw(__FILE__)));
    }
}
