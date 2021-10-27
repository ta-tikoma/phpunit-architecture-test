<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Storage\ObjectsStorage;

final class LayersBuilder
{
    /**
     * @return Layer[]
     */
    public static function fromNamespaceRegex(string $namespaceRegex): array
    {
        $objects = self::byClosure(static function (ObjectDescription $objectDescription) use ($namespaceRegex): ?string {
            preg_match_all($namespaceRegex, $objectDescription->name, $matches, PREG_SET_ORDER, 0);

            if (isset($matches[0]['name'])) {
                return $matches[0]['name'];
            }

            return null;
        });

        $layers = [];
        foreach ($objects as $name => $value) {
            $layers[] = new Layer($name, $value);
        }

        return $layers;
    }

    private static function byClosure(Closure $closure): array
    {
        $objectNames = [];

        foreach (ObjectsStorage::getObjectMap() as $objectDescription) {
            if ($name = $closure($objectDescription)) {
                if (!isset($objectNames[$name])) {
                    $objectNames[$name] = [];
                }

                $objectNames[$name][] = $objectDescription->name;
            }
        }

        return $objectNames;
    }
}
