<?php

declare(strict_types=1);

namespace PHPUnit\Architecture;

use PhpParser\{ParserFactory, Node, NodeFinder};
use PHPUnit\Architecture\Elements\Layer;
use PHPUnit\Architecture\Storage\ObjectsStorage;
use ReflectionClass;

/**
 * Asserts for testing architecture
 */
trait ArchitectureAsserts
{
    abstract public static function assertNotEquals($expected, $actual, string $message = ''): void;

    abstract public static function assertEquals($expected, $actual, string $message = ''): void;

    /**
     * Check layerA does not depend on layerB
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     */
    public function assertDoesNotDependOn($layerA, $layerB): void
    {
        self::assertEquals(
            0,
            count($this->getObjectsWhichUsesOnLayerAFromLayerB($layerA, $layerB)),
        );
    }

    /**
     * Check layerA does not depend on layerB
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     */
    public function assertDependOn($layerA, $layerB): void
    {
        self::assertNotEquals(
            0,
            count($this->getObjectsWhichUsesOnLayerAFromLayerB($layerA, $layerB))
        );
    }

    /**
     * Get objects which uses on layer A from layer B
     *
     * @param Layer|Layer[] $layerA
     * @param Layer|Layer[] $layerB
     *
     * @return string[]
     */
    private function getObjectsWhichUsesOnLayerAFromLayerB($layerA, $layerB): array
    {
        /** @var Layer[] $layers */
        $layers = is_array($layerA) ? $layerA : [$layerA];

        /** @var Layer[] $layersToSearch */
        $layersToSearch = is_array($layerB) ? $layerB : [$layerB];

        $result = [];

        foreach ($layers as $layer) {
            foreach ($layer->objectsName as $name) {
                $object = ObjectsStorage::getObjectMap()[$name];
                foreach ($object->uses as $use) {
                    foreach ($layersToSearch as $layerToSearch) {
                        foreach ($layerToSearch->objectsName as $nameToSearch) {
                            $objectToSearch = ObjectsStorage::getObjectMap()[$nameToSearch];
                            if ($objectToSearch->name === $use) {
                                $result[] = $nameToSearch;
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Return objects which uses in object
     * 
     * @return string[] 
     */
    private function getUsesObjects(string $object): array
    {
        $refClass = new ReflectionClass($object);
        $content = file_get_contents($refClass->getFileName());

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $nodeFinder = new NodeFinder;

        $ast = $parser->parse($content);

        /** @var Node\Stmt\UseUse[] $useUses */
        $useUses = $nodeFinder->findInstanceOf($ast, Node\Stmt\UseUse::class);

        $uses = array_map(static function (Node\Stmt\UseUse $useUse): string {
            return $useUse->name->toCodeString();
        }, $useUses);

        return $uses;
    }
}
