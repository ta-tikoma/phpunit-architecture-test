<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements\Layer;

use Closure;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Enums\ObjectType;
use PHPUnit\Architecture\Storage\Filesystem;

trait LayerFilters
{
    abstract public function filter(Closure $closure): Layer;

    public function filterByPathStart(string $path): Layer
    {
        $path = realpath(Filesystem::getBaseDir() . $path);
        $length = strlen($path);

        return $this->filter(static function (ObjectDescription $objectDescription) use ($path, $length): bool {
            return substr($objectDescription->path, 0, $length) === $path;
        });
    }

    public function filterByNameStart(string $name): Layer
    {
        $length = strlen($name);

        return $this->filter(static function (ObjectDescription $objectDescription) use ($name, $length): bool {
            return substr($objectDescription->name, 0, $length) === $name;
        });
    }

    public function filterByNameRegex(string $name): Layer
    {
        return $this->filter(static function (ObjectDescription $objectDescription) use ($name): bool {
            return preg_match($name, $objectDescription->name) === 1;
        });
    }

    public function filterByType(ObjectType $type): Layer
    {
        return $this->filter(static function (ObjectDescription $objectDescription) use ($type): bool {
            return $objectDescription->type->equals($type);
        });
    }
}
