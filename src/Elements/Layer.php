<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Elements;

use ArrayIterator;
use IteratorAggregate;

final class Layer implements IteratorAggregate
{
    public string $name;

    /**
     * @var string[]
     */
    private array $objectsName = [];

    public function __construct(
        array $objectsName
    ) {
        sort($objectsName);

        $this->name = implode(',', $objectsName);
        $this->objectsName = $objectsName;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->objectsName);
    }

    /**
     * Compare layers
     */
    public function equals(Layer $layer): bool
    {
        return $this->name === $layer->name;
    }
}
