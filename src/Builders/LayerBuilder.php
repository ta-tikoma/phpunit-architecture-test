<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Contracts\FilterContract;
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Enums\ObjectType;
use PHPUnit\Architecture\Filters\ClosureFilter;
use PHPUnit\Architecture\Filters\NameRegexFilter;
use PHPUnit\Architecture\Filters\NameStartFilter;
use PHPUnit\Architecture\Filters\ObjectTypeFilter;
use PHPUnit\Architecture\Filters\PathStartFilter;
use PHPUnit\Architecture\Storage\ObjectsStorage;

final class LayerBuilder
{
    /**
     * @var FilterContract[]
     */
    private array $include = [];

    /**
     * @var FilterContract[]
     */
    private array $exclude = [];

    /**
     * @var string[]
     */
    private array $objectNames = [];

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
     * @param string $regex '/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/'
     */
    public function includeNameRegex(string $regex): self
    {
        return $this->includeFilter(new NameRegexFilter($regex));
    }

    /**
     * @param string $regex '/^PHPUnit\\\\Architecture\\\\Asserts\\\\[^\\\\]+\\\\.+Asserts$/'
     */
    public function excludeNameRegex(string $regex): self
    {
        return $this->excludeFilter(new NameRegexFilter($regex));
    }

    /**
     * @param string $path Like 'app',  'app/Models', 'src', 'tests' ...
     */
    public function includePath(string $path): self
    {
        return $this->includeFilter(new PathStartFilter($path));
    }

    /**
     * @param string $path Like 'app',  'app/Models', 'src', 'tests' ...
     */
    public function excludePath(string $path): self
    {
        return $this->excludeFilter(new PathStartFilter($path));
    }

    /**
     * @param string $namespace Like 'App',  'App\\Models', 'App\\Http\\Controllers', 'tests' ...
     */
    public function includeNameStart(string $namespace): self
    {
        return $this->includeFilter(new NameStartFilter($namespace));
    }

    /**
     * @param string $namespace Like 'App',  'App\\Models', 'App\\Http\\Controllers', 'tests' ...
     */
    public function excludeNameStart(string $namespace): self
    {
        return $this->excludeFilter(new NameStartFilter($namespace));
    }

    public function includeObjectType(ObjectType $type): self
    {
        return $this->includeFilter(new ObjectTypeFilter($type));
    }

    public function excludeObjectType(ObjectType $type): self
    {
        return $this->excludeFilter(new ObjectTypeFilter($type));
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
