<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Contracts\FilterContract;
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Filters\ClosureFilter;
use PHPUnit\Architecture\Filters\DirectoryStartFilter;
use PHPUnit\Architecture\Filters\NamespaceRegexFilter;
use PHPUnit\Architecture\Filters\NamespaceStartFilter;
use PHPUnit\Architecture\Storage\Filesystem;
use PHPUnit\Architecture\Storage\ObjectsStorage;

final class LayerBuilder
{
    /**
     * @var FilterContract[]
     */
    public array $include = [];

    /**
     * @var FilterContract[]
     */
    public array $exclude = [];

    /**
     * @var string[]
     */
    public array $objectNames = [];

    public function includeFilter(FilterContract $filter): self
    {
        $this->include[] = $filter;

        return $this;
    }

    public function excludeFilter(FilterContract $filter): self
    {
        $this->exclude[] = $filter;

        return $this;
    }

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectDescription): bool
     */
    public function includeBy(Closure $closure): self
    {
        return $this->includeFilter(new ClosureFilter($closure));
    }

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectDescription): bool
     */
    public function excludeBy(Closure $closure): self
    {
        return $this->excludeFilter(new ClosureFilter($closure));
    }

    /**
     * @param string $objectName Like: ProductController::class, Product::class, ...
     */
    public function includeObject(string $objectName): self
    {
        $this->objectNames[] = $objectName;

        return $this;
    }

    /**
     * @param string $regex
     */
    public function includeNamespaceRegex(string $regex): self
    {
        return $this->includeFilter(new NamespaceRegexFilter($regex));
    }

    /**
     * @param string $regex
     */
    public function excludeNamespaceRegex(string $regex): self
    {
        return $this->excludeFilter(new NamespaceRegexFilter($regex));
    }

    /**
     * @param string $path Like 'app',  'app/Models', 'src', 'tests' ...
     */
    public function includeDirectory(string $path): self
    {
        return $this->includeFilter(new DirectoryStartFilter($path));
    }

    /**
     * @param string $path Like 'app',  'app/Models', 'src', 'tests' ...
     */
    public function excludeDirectory(string $path): self
    {
        return $this->excludeFilter(new DirectoryStartFilter($path));
    }

    /**
     * @param string $namespace Like 'App',  'App\\Models', 'App\\Http\\Controllers', 'tests' ...
     */
    public function includeNamespace(string $namespace): self
    {
        return $this->includeFilter(new NamespaceStartFilter($namespace));
    }

    /**
     * @param string $namespace Like 'App',  'App\\Models', 'App\\Http\\Controllers', 'tests' ...
     */
    public function excludeNamespace(string $namespace): self
    {
        return $this->excludeFilter(new NamespaceStartFilter($namespace));
    }

    public function build(): Layer
    {
        $objectNames = self::byClosure(function (ObjectDescription $objectDescription): bool {
            foreach ($this->exclude as $filter) {
                /** @var FilterContract $filter */
                if ($filter->check($objectDescription) !== false) {
                    return false;
                }
            }

            foreach ($this->include as $filter) {
                /** @var FilterContract $filter */
                if ($filter->check($objectDescription) !== false) {
                    return true;
                }
            }

            return false;
        });

        return new Layer(
            array_merge(
                $this->objectNames,
                $objectNames
            )
        );
    }


    /**
     * @param string|string[] $include
     * @param string|string[] $exclude
     */
    public static function fromDirectory($include, $exclude = []): Layer
    {
        $include = is_array($include) ? $include : [$include];
        $exclude = is_array($exclude) ? $exclude : [$exclude];

        $include = array_map(static function (string $line): array {
            $line = realpath(Filesystem::getBaseDir() . $line);
            return [$line, strlen($line)];
        }, $include);

        $exclude = array_map(static function (string $line): array {
            $line = realpath(Filesystem::getBaseDir() . $line);
            return [$line, strlen($line)];
        }, $exclude);

        $objectNames = self::byClosure(static function (ObjectDescription $objectDescription) use ($include, $exclude): bool {
            foreach ($exclude as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->path, 0, $length) === $line) {
                    return false;
                }
            }

            foreach ($include as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->path, 0, $length) === $line) {
                    return true;
                }
            }

            return false;
        });

        return new Layer($objectNames);
    }

    /**
     * @param string|string[] $include
     * @param string|string[] $exclude
     */
    public static function fromNamespace($include, $exclude = []): Layer
    {
        $include = is_array($include) ? $include : [$include];
        $exclude = is_array($exclude) ? $exclude : [$exclude];

        $include = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, $include);

        $exclude = array_map(static function (string $line): array {
            return [$line, strlen($line)];
        }, $exclude);

        $objectNames = self::byClosure(static function (ObjectDescription $objectDescription) use ($include, $exclude): bool {
            foreach ($exclude as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->name, 0, $length) === $line) {
                    return false;
                }
            }

            foreach ($include as list($line, $length)) {
                /** @var int $length */
                if (substr($objectDescription->name, 0, $length) === $line) {
                    return true;
                }
            }

            return false;
        });

        return new Layer($objectNames);
    }

    /**
     * @param Closure $closure function (string $name, string $path): bool
     */
    private static function byClosure(Closure $closure): array
    {
        $objectNames = [];

        foreach (ObjectsStorage::getObjectMap() as $objectDescription) {
            if ($closure($objectDescription)) {
                $objectNames[] = $objectDescription->name;
            }
        }

        return $objectNames;
    }
}
