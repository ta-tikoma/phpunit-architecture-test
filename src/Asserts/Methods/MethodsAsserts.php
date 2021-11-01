<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Methods;

use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Storage\ObjectsStorage;

/**
 * Asserts for objects methods
 */
trait MethodsAsserts
{
    abstract public static function assertNotEquals($expected, $actual, string $message = ''): void;

    abstract public static function assertEquals($expected, $actual, string $message = ''): void;

    /**
     * Search objects from LayerB in arguments of methods from LayerA
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     * @param string[] $methods Search only this methods
     */
    public function assertIncomingsNotFrom($layerA, $layerB, array $methods = []): void
    {
        $incomings = $this->getIncomingsFrom($layerA, $layerB, $methods);

        self::assertEquals(
            0,
            count($incomings),
            'Found incomings: ' . implode("\n", $incomings)
        );
    }

    /**
     * Search objects from LayerB in arguments of methods from LayerA
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     * @param string[] $methods Search only this methods
     */
    public function assertIncomingsFrom($layerA, $layerB, array $methods = []): void
    {
        $incomings = $this->getIncomingsFrom($layerA, $layerB, $methods);

        self::assertNotEquals(
            0,
            count($incomings),
            'Not found incomings'
        );
    }

    /**
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     * @param string[] $methods Search only this methods
     *
     * @return string[]
     */
    protected function getIncomingsFrom($layerA, $layerB, array $methods): array
    {
        /** @var Layer[] $layers */
        $layers = is_array($layerA) ? $layerA : [$layerA];

        /** @var Layer[] $layersToSearch */
        $layersToSearch = is_array($layerB) ? $layerB : [$layerB];

        $result = [];

        foreach ($layers as $layer) {
            foreach ($layer->objectsName as $name) {
                $object = ObjectsStorage::getObjectMap()[$name];
                foreach ($object->methods as $method) {
                    if (count($methods) > 0) {
                        if (!in_array($method->name, $methods)) {
                            continue;
                        }
                    }

                    foreach ($method->args as list($aType, $aName)) {
                        foreach ($layersToSearch as $layerToSearch) {
                            // do not test layer with self
                            if ($layer->equals($layerToSearch)) {
                                continue;
                            }

                            foreach ($layerToSearch->objectsName as $nameToSearch) {
                                if ($nameToSearch === $aType) {
                                    $result[] = "$name : {$method->name} -> $aName <- $nameToSearch";
                                }
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Search objects from LayerB in methods return type from LayerA
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     * @param string[] $methods Search only this methods
     */
    public function assertOutgoingFrom($layerA, $layerB, array $methods = []): void
    {
        $outgoings = $this->getOutgoingFrom($layerA, $layerB, $methods);

        self::assertNotEquals(
            0,
            count($outgoings),
            'Outgoings not found'
        );
    }

    /**
     * Search objects from LayerB in methods return type from LayerA
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     * @param string[] $methods Search only this methods
     */
    public function assertOutgoingNotFrom($layerA, $layerB, array $methods = []): void
    {
        $outgoings = $this->getOutgoingFrom($layerA, $layerB, $methods);

        self::assertNotEquals(
            0,
            count($outgoings),
            'Found outgoings: ' . implode("\n", $outgoings)
        );
    }

    /**
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     * @param string[] $methods Search only this methods
     *
     * @return string[]
     */
    protected function getOutgoingFrom($layerA, $layerB, array $methods): array
    {
        /** @var Layer[] $layers */
        $layers = is_array($layerA) ? $layerA : [$layerA];

        /** @var Layer[] $layersToSearch */
        $layersToSearch = is_array($layerB) ? $layerB : [$layerB];

        $result = [];

        foreach ($layers as $layer) {
            foreach ($layer->objectsName as $name) {
                $object = ObjectsStorage::getObjectMap()[$name];
                foreach ($object->methods as $method) {
                    if (count($methods) > 0) {
                        if (!in_array($method->name, $methods)) {
                            continue;
                        }
                    }

                    foreach ($layersToSearch as $layerToSearch) {
                        // do not test layer with self
                        if ($layer->equals($layerToSearch)) {
                            continue;
                        }

                        foreach ($layerToSearch->objectsName as $nameToSearch) {
                            if ($nameToSearch === $method->return) {
                                $result[] = "$name : {$method->name} -> {$method->return} <- $nameToSearch";
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }
}
