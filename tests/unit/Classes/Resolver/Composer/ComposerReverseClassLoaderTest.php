<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Tests\Classes\Resolver\Composer;

use PhpProject\SourceCode\Classes\Resolver\Composer\ComposerReverseClassLoader;
use PhpProject\SourceCode\Files\Path\AbsolutePath;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('source-code')]
#[Group('source-code-class')]
#[Group('class-resolver')]
#[Group('composer')] final class ComposerReverseClassLoaderTest extends TestCase
{
    private const BASE_PATH = __DIR__.'/../../../../data/a-composer-project';

    private ComposerReverseClassLoader $reverseLoader;

    #[Test]
    public function it_creates_reverse_loader_from_composer_loader(): void
    {
        $_this = $this; // #ignoreLine
        $_this->given_the_reverse_loader_is_built_from_composer_loader();

        $_this->it_can_resolve_psr0_class_from_its_path();
        $_this->it_can_resolve_psr4_class_from_its_path();
        $_this->it_can_resolve_classmap_class_from_its_path();
        $_this->it_cannot_resolve_class_from_file();
    }

    // 1. Arrange

    private function given_the_reverse_loader_is_built_from_composer_loader(): void
    {
        $loader              = require self::BASE_PATH.'/vendor/autoload.php';
        $this->reverseLoader = ComposerReverseClassLoader::from($loader);
    }

    // 3. Assert

    private function it_can_resolve_psr0_class_from_its_path(): void
    {
        $psr0ClassFilePath = AbsolutePath::raw((string) realpath(self::BASE_PATH.'/psr0/DummyComposerPsr0/DummyComposerPsr0Class.php'));
        self::assertEquals('DummyComposerPsr0\\DummyComposerPsr0Class', $this->reverseLoader->get($psr0ClassFilePath));
    }

    private function it_can_resolve_psr4_class_from_its_path(): void
    {
        $psr4ClassFilePath = AbsolutePath::raw((string) realpath(self::BASE_PATH.'/psr4/DummyComposerPsr4Class.php'));
        self::assertEquals('DummyComposerPsr4\\DummyComposerPsr4Class', $this->reverseLoader->get($psr4ClassFilePath));
    }

    private function it_can_resolve_classmap_class_from_its_path(): void
    {
        $classMapClassFilePath = AbsolutePath::raw((string) realpath(self::BASE_PATH.'/classmap/ClassMapClass.php'));
        self::assertEquals('ClassMap\\ClassMapClass', $this->reverseLoader->get($classMapClassFilePath));
    }

    private function it_cannot_resolve_class_from_file(): void
    {
        $dummyFileClassFilePath = AbsolutePath::raw((string) realpath(self::BASE_PATH.'/files/DummyFileClass.php'));
        self::assertNull($this->reverseLoader->get($dummyFileClassFilePath));
    }
}
