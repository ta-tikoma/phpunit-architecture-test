<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use ReflectionProperty;

final class PropertyDescription
{
    public string $name;

    public ?string $type;

    public string $visibility;

    public static function make(ReflectionProperty $reflectionProperty): self
    {
        $description = new static;
        $description->name = $reflectionProperty->getName();
        $description->type = $reflectionProperty->getType() === null ? null : $reflectionProperty->getType()->getName();

        if ($reflectionProperty->isPrivate()) {
            $description->visibility = 'private';
        } elseif ($reflectionProperty->isProtected()) {
            $description->visibility = 'protected';
        } else {
            $description->visibility = 'public';
        }

        return $description;
    }
}
