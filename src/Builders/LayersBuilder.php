<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Builders;

use Closure;
use PHPUnit\Architecture\Elements\Layer;

final class LayersBuilder
{
    /**
     * @return Layer[]
     */
    public static function fromNamespaceRegex(string $namespaceRegex): array
    {
        $objects = self::byClosure(static function (string $objectName) use ($namespaceRegex): ?string {
            // var_dump($objectName);
            preg_match_all($namespaceRegex, $objectName, $matches, PREG_SET_ORDER, 0);

            var_dump($objectName);
            var_dump($matches);
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
        $objects = [];

        foreach (get_declared_classes() as $className) {
            if (!is_null($name = $closure($className))) {
                if (!isset($objects[$name])) {
                    $objects[$name] = [];
                }

                $objects[$name][] = $className;
            }
        }

        foreach (get_declared_traits() as $traitName) {
            if (!is_null($name = $closure($traitName))) {
                if (!isset($objects[$name])) {
                    $objects[$name] = [];
                }

                $objects[$name][] = $traitName;
            }
        }

        foreach (get_declared_interfaces() as $interfaceName) {
            if (!is_null($name = $closure($interfaceName))) {
                if (!isset($objects[$name])) {
                    $objects[$name] = [];
                }

                $objects[$name][] = $interfaceName;
            }
        }

        return $objects;
    }
}
