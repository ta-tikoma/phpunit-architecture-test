<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Properties\Elements;

use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use PHPUnit\Architecture\Asserts\Properties\ObjectPropertiesDescription;
use PHPUnit\Architecture\Enums\Visibility;
use PHPUnit\Architecture\Services\ServiceContainer;
use ReflectionProperty;
use ReflectionUnionType;

final class PropertyDescription
{
    public string $name;

    /**
     * @var null|string|string[]
     */
    public $type;

    public Visibility $visibility;

    public static function make(
        ObjectPropertiesDescription $objectPropertiesDescription,
        ReflectionProperty $reflectionProperty
    ): self {
        $description = new static();
        $description->name = $reflectionProperty->getName();
        $description->type = self::getPropertyType($objectPropertiesDescription, $reflectionProperty);

        if ($reflectionProperty->isPrivate()) {
            $description->visibility = Visibility::PRIVATE();
        } elseif ($reflectionProperty->isProtected()) {
            $description->visibility = Visibility::PROTECTED();
        } else {
            $description->visibility = Visibility::PUBLIC();
        }

        return $description;
    }

    private static function getPropertyType(
        ObjectPropertiesDescription $objectPropertiesDescription,
        ReflectionProperty $reflectionProperty
    ) {
        if ($reflectionProperty->getType() !== null) {
            if ($reflectionProperty->getType() instanceof ReflectionUnionType) {
                return array_map(fn ($type) => $type->getName(), $reflectionProperty->getType()->getTypes());
            }

            return $reflectionProperty->getType()->getName();
        }


        $docComment = $reflectionProperty->getDocComment();
        if (empty($docComment)) {
            return null;
        }

        try {
            $docBlock = ServiceContainer::$docBlockFactory->create($docComment);
        } catch (Exception $e) {
            if (ServiceContainer::$showException) {
                echo "Can't parse: '$docComment'";
            }
            return null;
        }

        /** @var Var_[] $tags */
        $tags = $docBlock->getTagsWithTypeByName('var');
        if ($tag = array_shift($tags)) {
            return $objectPropertiesDescription->getDocBlockTypeWithNamespace($tag->getType());
        }

        return null;
    }


    public function __toString()
    {
        return $this->name;
    }
}
