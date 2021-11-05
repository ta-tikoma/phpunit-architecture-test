<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Properties;

use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Enums\Visibility;
use PHPUnit\Architecture\Storage\ObjectsStorage;

/**
 * Asserts for objects methods
 */
trait PropertiesAsserts
{
    abstract public static function assertEquals($expected, $actual, string $message = ''): void;

    /**
     * Search public properties in layerA
     *
     * @param Layer|Layer[] $layerA
     */
    public function assertHasNotPublicProperties($layerA): void
    {
        /** @var Layer[] $layers */
        $layers = is_array($layerA) ? $layerA : [$layerA];

        $result = [];
        foreach ($layers as $layer) {
            foreach ($layer->objectsName as $name) {
                $object = ObjectsStorage::getObjectMap()[$name];
                foreach ($object->properties as $property) {
                    if ($property->visibility->equals(Visibility::PUBLIC())) {
                        $result[] = "$name : {$property->name} <- public";
                    }
                }
            }
        }

        self::assertEquals(
            0,
            count($result),
            'Found public property: ' . implode("\n", $result)
        );
    }
}
