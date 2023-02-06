<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Classes\Resolver\Composer;

use Composer\Autoload\ClassLoader;
use PhpProject\SourceCode\Files\Path\AbsolutePath;

final readonly class ComposerReverseClassLoader
{
    /**
     * @param array<string, class-string> $reverseIndex
     * @param array<string, class-string> $reverseClassMap
     */
    public function __construct(
        private array $reverseIndex,
        private array $reverseClassMap
    ) {
    }

    /**
     * @return class-string|null
     */
    public function get(AbsolutePath $path): ?string
    {
        $realPath = (string) realpath((string) $path);

        return $this->getFromReverseClassMap($realPath) ?? $this->getFromReverseIndex($realPath);
    }

    /**
     * @return class-string|null
     */
    private function getFromReverseClassMap(string $fileRealPath): ?string
    {
        return $this->reverseClassMap[$fileRealPath] ?? null;
    }

    /**
     * @return class-string|null
     */
    private function getFromReverseIndex(string $fileRealPath): ?string
    {
        /** @var array<class-string> $candidates */
        $candidates = array_reduce(
            array_keys($this->reverseIndex),
            function (array $candidates, string $psr4Path) use ($fileRealPath): array {
                if (str_starts_with($fileRealPath, $psr4Path)) {
                    $baseNamespace = $this->reverseIndex[$psr4Path];
                    $remainingPath = str_replace($psr4Path.\DIRECTORY_SEPARATOR, '', $fileRealPath);
                    $candidates[]  = $baseNamespace.str_replace(
                        [\DIRECTORY_SEPARATOR, '.php'],
                        ['\\', ''],
                        $remainingPath
                    );
                }

                return $candidates;
            },
            []
        );

        // We keep the first existing class
        /** @var class-string|null $FQCN */
        $FQCN = array_reduce(
            $candidates,
            static fn (?string $classFQCN, string $classCandidate): ?string => $classFQCN ?? (class_exists($classCandidate) ? $classCandidate : null),
            null
        );

        return $FQCN;
    }

    public static function from(ClassLoader $loader): self
    {
        $psr4Index       = self::buildPsr4ReverseIndex($loader);
        $psr0Index       = self::buildPsr0ReverseIndex($loader);
        $reverseClassMap = self::buildReverseClassMap($loader);

        // TODO add the possibility to have multiple namespaces in one directory

        return new self([...$psr4Index, ...$psr0Index], $reverseClassMap);
    }

    /**
     * @return array<string, class-string>
     */
    private static function buildPsr4ReverseIndex(ClassLoader $loader): array
    {
        $psr4Index = $loader->getPrefixesPsr4();

        return array_reduce(
            array_keys($psr4Index),
            static function (array $reversedIndex, string $key) use ($psr4Index): array {
                $paths = $psr4Index[$key];

                foreach ($paths as $path) {
                    $reversedIndex[realpath($path)] = $key;
                }

                return $reversedIndex;
            },
            []
        );
    }

    /**
     * @return array<string, class-string>
     */
    private static function buildPsr0ReverseIndex(ClassLoader $loader): array
    {
        /** @var array<string, array<string>> $psr0Index */
        $psr0Index = $loader->getPrefixes();

        return array_reduce(
            $psr0Index,
            static function (array $reversedIndex, array $paths): array {
                foreach ($paths as $path) {
                    $reversedIndex[realpath($path)] = '';
                }

                return $reversedIndex;
            },
            []
        );
    }

    /**
     * @return array<string, class-string>
     */
    private static function buildReverseClassMap(ClassLoader $loader): array
    {
        $classMap = $loader->getClassMap();

        /** @var array<string, class-string> $reverseClassMap */
        $reverseClassMap = array_flip(
            array_map(
                static fn (string $path): string => (string) realpath($path),
                $classMap
            )
        );

        return $reverseClassMap;
    }
}
