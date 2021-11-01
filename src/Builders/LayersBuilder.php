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
    public static function fromNameRegex(string $nameRegex): array
    {
        return self::fromClosure(static function (ObjectDescription $objectDescription) use ($nameRegex): ?string {
            preg_match_all($nameRegex, $objectDescription->name, $matches, PREG_SET_ORDER, 0);

            if (isset($matches[0]['layer'])) {
                return $matches[0]['layer'];
            }

            return null;
        });
    }

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectNames): ?string
     * @return Layer[]
     */
    public static function fromClosure(Closure $closure): array
    {
        $data = self::byClosure($closure);

        $layers = array_map(static function (array $objectNames): Layer {
            return new Layer($objectNames);
        }, $data);

        return $layers;
    }

    /**
     * @param Closure $closure Contract: static function (ObjectDescription $objectNames): ?string
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
