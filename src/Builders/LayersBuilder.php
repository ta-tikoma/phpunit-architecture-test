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
     * Regex must contains group with name 'layer'
     *
     * @return Layer[]
     */
    public static function fromNamespaceRegex(string $namespaceRegex): array
    {
        $objects = self::byClosure(static function (ObjectDescription $objectDescription) use ($namespaceRegex): ?string {
            preg_match_all($namespaceRegex, $objectDescription->name, $matches, PREG_SET_ORDER, 0);

            if (isset($matches[0]['layer'])) {
                return $matches[0]['layer'];
            }

            return null;
        });

        $layers = [];

        foreach ($objects as $value) {
            $layers[] = new Layer($value);
        }

        return $layers;
    }

    /**
     * @return array<string, array>
     */
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
