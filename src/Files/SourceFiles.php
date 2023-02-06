<?php

declare(strict_types=1);

namespace PhpProject\SourceCode\Files;

use PhpProject\SourceCode\Files\Path\RelativePath;

/**
 * @template-implements \IteratorAggregate<string, SourceFile>
 */
final readonly class SourceFiles implements \Countable, \IteratorAggregate
{
    /** @var array<string, SourceFile> */
    private array $files;

    private function __construct(
        SourceFile ...$files
    ) {
        $this->files = array_reduce(
            $files,
            static function (array $indexedArray, SourceFile $file): array {
                $indexedArray[(string) $file->path] = $file;

                return $indexedArray;
            },
            []
        );
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->files);
    }

    public function count(): int
    {
        return \count($this->files);
    }

    /**
     * @return array<SourceFile>
     */
    public function asArray(): array
    {
        return array_values($this->files);
    }

    public function add(self $files): self
    {
        return new self(
            ...array_reduce(
                $files->files,
                static function (array $mergedFiles, SourceFile $file): array {
                    $mergedFiles[(string) $file->path] = $file;

                    return $mergedFiles;
                },
                $this->files
            )
        );
    }

    public function remove(self $files): self
    {
        return new self(
            ...array_reduce(
                $files->files,
                static function (array $resultFiles, SourceFile $file): array {
                    $filePath = (string) $file->path;

                    if (\array_key_exists($filePath, $resultFiles)) {
                        unset($resultFiles[$filePath]);
                    }

                    return $resultFiles;
                },
                $this->files
            )
        );
    }

    public static function fromPaths(RelativePath ...$paths): self
    {
        return new self(
            ...array_map(
                static fn (RelativePath $path): SourceFile => SourceFile::fromPath($path),
                $paths
            )
        );
    }

    public static function fromSourceFile(SourceFile ...$file): self
    {
        return new self(...$file);
    }

    public static function fromSourceFiles(self ...$sourceFiles): self
    {
        return new self(
            ...array_reduce(
                $sourceFiles,
                static fn (array $files, self $sourceFiles): array => array_merge($files, $sourceFiles->files),
                []
            )
        );
    }

    public static function empty(): self
    {
        return new self(...[]);
    }
}
