<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Inheritance;

use PHPUnit\Architecture\Asserts\Dependencies\ObjectDependenciesDescription;

/**
 * Describe object extends and implement
 */
class ObjectInheritanceDescription extends ObjectDependenciesDescription
{
    /**
     * Name extends class
     */
    public ?string $extendsClass = null;

    public array $interfaces = [];

    public array $traits = [];

    public static function make(string $path): ?self
    {
        $description = parent::make($path);

        if ($description === null) {
            return null;
        }

        if ($parentClass = $description->reflectionClass->getParentClass()) {
            $description->extendsClass = $parentClass->getName();
        }

        $description->interfaces = $description->reflectionClass->getInterfaceNames();
        $description->traits = $description->reflectionClass->getTraitNames();

        return $description;
    }
}
