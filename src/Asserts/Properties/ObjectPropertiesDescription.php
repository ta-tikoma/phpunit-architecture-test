<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Properties;

use PHPUnit\Architecture\Asserts\Methods\ObjectMethodsDescription;
use PHPUnit\Architecture\Elements\PropertyDescription;
use ReflectionProperty;

/**
 * Describe object methods
 */
class ObjectPropertiesDescription extends ObjectMethodsDescription
{
    /**
     * Object properties
     *
     * @var PropertyDescription[]
     */
    public array $properties;

    public static function make(string $path): ?self
    {
        $description = parent::make($path);

        if ($description === null) {
            return null;
        }

        $description->properties = array_map(static function (ReflectionProperty $reflectionProperty): PropertyDescription {
            return PropertyDescription::make($reflectionProperty);
        }, $description->reflectionClass->getProperties());

        return $description;
    }
}
