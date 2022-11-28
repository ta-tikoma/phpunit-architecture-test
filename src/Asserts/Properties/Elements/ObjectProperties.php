<?php

declare(strict_types=1);

namespace PHPUnit\Architecture\Asserts\Properties\Elements;

use ArrayIterator;
use IteratorAggregate;

final class ObjectProperties implements IteratorAggregate
{
    /**
     * Object properties
     *
     * @var PropertyDescription[]
     */
    public array $properties;

    /**
     * @param PropertyDescription[] $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->properties);
    }
}
