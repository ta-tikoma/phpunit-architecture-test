<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

final class Layer
{
    public string $name;

    /**
     * @var string[]
     */
    public array $objectsName = [];

    public function __construct(
        array $objectsName
    ) {
        sort($objectsName);

        $this->name = implode(',', $objectsName);
        $this->objectsName = $objectsName;
    }

    /**
     * Compare layers
     */
    public function equals(Layer $layer): bool
    {
        return $this->name === $layer->name;
    }
}
