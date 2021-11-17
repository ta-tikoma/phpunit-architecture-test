<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Properties\Elements;

use PHPUnit\Architecture\Enums\Visibility;
use ReflectionProperty;

final class PropertyDescription
{
    public string $name;

    public ?string $type;

    public Visibility $visibility;

    public static function make(ReflectionProperty $reflectionProperty): self
    {
        $description = new static;
        $description->name = $reflectionProperty->getName();
        $description->type = $reflectionProperty->getType() === null ? null : $reflectionProperty->getType()->getName();

        if ($reflectionProperty->isPrivate()) {
            $description->visibility = Visibility::PRIVATE();
        } elseif ($reflectionProperty->isProtected()) {
            $description->visibility = Visibility::PROTECTED();
        } else {
            $description->visibility = Visibility::PUBLIC();
        }

        return $description;
    }

    public function __toString()
    {
        return $this->name;
    }
}
