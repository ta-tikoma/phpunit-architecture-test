<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Contracts\FilterContract;
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Filters\DirectoryStartFilter;
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

    public function includePath(string $path): self
    {
        return $this->includeFilter(new DirectoryStartFilter($path));
    }

    public function excludePath(string $path): self
    {
        return $this->excludeFilter(new DirectoryStartFilter($path));
    }

    public function includeNamespace(string $namespace): self
    {
        return $this->includeFilter(new NamespaceStartFilter($namespace));
    }

    public function excludeNamespace(string $namespace): self
    {
        return $this->excludeFilter(new NamespaceStartFilter($namespace));
    }

    public function build(): Layer
    {
        $name = md5(implode(',', $this->include) . implode(',', $this->exclude));

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

        return new Layer($name, $objectNames);
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
