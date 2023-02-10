<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Dependencies;

use PhpParser\Node;
use PHPUnit\Architecture\Asserts\Dependencies\Elements\ObjectUses;
use PHPUnit\Architecture\Elements\ObjectDescriptionBase;
use PHPUnit\Architecture\Services\ServiceContainer;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\AggregatedType;
use phpDocumentor\Reflection\Types\Array_;

/**
 * Describe object dependencies
 */
class ObjectDependenciesDescription extends ObjectDescriptionBase
{
    /**
     * Names of uses objects
     */
    public ObjectUses $uses;

    public static function make(string $path): ?self
    {
        $description = parent::make($path);

        if ($description === null) {
            return null;
        }

        /** @var Node\Name [] $names */
        $names = ServiceContainer::$nodeFinder->findInstanceOf(
            $description->stmts,
            Node\Name::class
        );

        $names = array_values(array_filter($names, static function (Node\Name $name) {
            $nameAsString = $name->toString();

            return match (true) {
                enum_exists($nameAsString) => true,
                class_exists($nameAsString) => true,
                interface_exists($nameAsString) => true,
                function_exists($nameAsString) => true,
                trait_exists($nameAsString) => true,

                default => false,
            };
        }));

        $description->uses = new ObjectUses(
            array_map(
                static function (Node\Name $nodeName): string {
                    $name = $nodeName->toCodeString();
                    if ($name[0] !== '\\') {
                        return $name;
                    }
                    return substr($name, 1);
                },
                $names
            )
        );

        return $description;
    }

    public function getDocBlockTypeWithNamespace(
        Type $type
    ) {
        $result = [];
        if ($type instanceof AggregatedType) {
            foreach ($type as $_type) {
                /** @var Type $_type */
                $result[] = $this->getDocBlockTypeWithNamespace($_type);
            }
        }

        if ($type instanceof Array_) {
            $result[] = $this->getDocBlockTypeWithNamespace($type->getKeyType());
            $result[] = $this->getDocBlockTypeWithNamespace($type->getValueType());
        }

        // @todo
        if (count($result) !== 0) {
            $_result = [];
            foreach ($result as $item) {
                if (is_array($item)) {
                    $_result = array_merge($_result, $item);
                } else {
                    $_result[] = $item;
                }
            }

            return $_result;
        }

        return $this->uses->getByName((string) $type) ?? $type;
    }
}
